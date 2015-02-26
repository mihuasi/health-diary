<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracker extends CI_Controller {

	public function index()
	{
        $this->load->model('Tracker_model');
        $this->load->helper('tracker_view');
        $aspects = $this->Tracker_model->get_aspects();
        $influences = $this->Tracker_model->get_influences();
        $currentDay = $this->Tracker_model->get_current_day();
        $previousDays = $this->Tracker_model->getPreviousDays();
        $influencesSuggestions = $this->Tracker_model->getSuggestions($influences);
        $influences = setSuggestions($influences, $influencesSuggestions);
        $this->load->helper('url');
        $this->load->helper('form');
        $data = array(
            'aspects'=>$aspects,
            'influences'=>$influences,
            'currentDay'=>$currentDay,
            'previousDays'=>$previousDays
        );
		$this->load->view('tracker', $data);
	}

        public function refreshForm() {
            $this->load->model('Tracker_model');
            $this->load->helper('tracker_view');
            $postData = $this->input->post();
            $date = (isset($postData['date'])) ? datePickerToMysqlFormat($postData['date']) : null;

            $aspects = $this->Tracker_model->get_aspects();
            $influences = $this->Tracker_model->get_influences();
            if ($date) {
                $currentDay = $date;
                $currentInfluences = $this->Tracker_model->getCurrentItems($currentDay, 'influence');
                $influences = setCurrent($influences, $currentInfluences);
                $currentAspects = $this->Tracker_model->getCurrentItems($currentDay, 'health_aspect');
                $aspects = setCurrent($aspects, $currentAspects);
                $currentAspectRatings = $this->Tracker_model->getCurrentRatings($currentDay, 'health_aspect');
                $aspects = setCurrentRatings($aspects, $currentAspectRatings);
            } else {
                $currentDay = $this->Tracker_model->get_current_day();
            }

            $influencesSuggestions = $this->Tracker_model->getSuggestions($influences);
            $influences = setSuggestions($influences, $influencesSuggestions);

            $this->load->helper('url');
            $this->load->helper('form');
            $isNew = (empty($currentInfluences) && empty($currentAspects));
            echo json_encode(renderForm($currentDay, $aspects, $influences, $isNew));
        }

        public function addNewDayToPrevious() {
            $this->load->model('Tracker_model');
            $this->load->helper('form');
            $this->load->helper('url');
            $previousDays = $this->Tracker_model->getPreviousDays();
            $this->load->helper('tracker_view');
            echo json_encode(renderPreviousDays($previousDays));
        }

        public function processNewEntry() {
            $this->load->model('Tracker_model');

            $postData = $this->input->post();
            $tags = $postData['tags'];
            $ratings = $postData['ratings'];
            $date = $postData['date'];
            $dateMysql = datePickerToMysqlFormat($date);
            $userId = GUEST_UID;

            $this->Tracker_model->removeItemsOnDay($dateMysql, 'influence');
            $this->Tracker_model->removeItemsOnDay($dateMysql, 'health_aspect');

            $aspectEntries = array();
            $influenceEntries = array();
            foreach ($tags as $tag) {
                $key = $tag['key'];
                $keySplitFirst = explode('/', $key);
                $keySplitSecond = explode('-', $keySplitFirst[0]);
                $tagIndex = $keySplitFirst[1];
                $id = $keySplitSecond[1];
                $type = $keySplitSecond[0];
                if ($type === 'aspects') {
                    if (!isset($aspectEntries[$id])) {
                        $aspectEntries[$id] = new stdClass();
                    }
                    if (!empty($aspectEntries[$id]->comment)) {
                        $aspectEntries[$id]->comment .= DB_DELIMITER . $tag['value'];
                    } else {
                        $aspectEntries[$id]->comment = $tag['value'];
                    }
                } else if ($type === 'influences') {
                    if (!isset($influenceEntries[$id])) {
                        $influenceEntries[$id] = new stdClass();
                    }
                    if (!empty($influenceEntries[$id]->comment)) {
                        $influenceEntries[$id]->comment .= DB_DELIMITER . $tag['value'];
                    } else {
                        $influenceEntries[$id]->comment = $tag['value'];
                    }
                }
            }

            foreach ($ratings as $rating) {
                $type = $rating['type'];
                $id = $rating['key'];
                $value = $rating['value'];
                if ($type === 'aspect') {
                    if (!isset($aspectEntries[$id])) {
                        $aspectEntries[$id] = new stdClass();
                    }
                    $aspectEntries[$id]->value = $value;
                } else if ($type === 'influences') {
                    
                }
            }
            $entries = array();
            foreach ($influenceEntries as $typeId => $influenceEntry) {
                $entry = $influenceEntry;
                $entry->influence_id = $typeId;
                $entry->date = $dateMysql;
                $this->Tracker_model->put_influence($entry);
                $entries[] = $entry;
            }
            foreach ($aspectEntries as $typeId => $aspectEntry) {
                $entry = $aspectEntry;
                $entry->health_aspect_id = $typeId;
                $entry->date = $dateMysql;
                $this->Tracker_model->put_aspect($entry);
                $entries[] = $entry;
            }
            echo json_encode($entries);

        }
}
