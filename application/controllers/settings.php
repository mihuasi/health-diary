<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function index()
    {
        $this->load->model('Tracker_model');
        $this->load->model('Settings_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('table');
        $this->load->helper('settings');
        $aspects = $this->Tracker_model->get_aspects();
        $influences = $this->Tracker_model->get_influences();
        $data = array(
            'aspects' => $aspects,
            'influences' => $influences,
            'tableClass' => $this->table
        );

        $this->load->view('settings', $data);
    }

    public function saveTableField() {
        $this->load->model('Settings_model');
        $this->load->helper('settings');
        $postData = $this->input->post();
        $typeAndId = explode('-', $postData['id']);
        $type = $typeAndId[0];
        $field = $typeAndId[1];
        $id = $typeAndId[2];
        $value = prepareUpdateValue($field, $postData['value']);
        $this->Settings_model->updateField($type, $field, $id, $value);
        echo prepareReturnValue($field, $postData['value']);
    }

    public function removeInfluence() {
        $this->load->model('Settings_model');
        $postData = $this->input->post();
        $id = $postData['id'];
        $this->Settings_model->deleteInfluence($id);
        echo $id;
    }

    public function addInfluence() {
        $this->load->model('Settings_model');
        $postData = $this->input->post();
        $id = $postData['id'];
        $time = $this->Settings_model->getInfluenceTime($id);
        $time ++;
        $influence = new stdClass();
        $nextTime = mysqlTimeFormat($time);
        $influence->name = '[New]';
        $influence->time_taken = $nextTime;
        $influence->user_id = GUEST_UID;
        $this->Settings_model->insertInfluence($influence);

        echo $id;
    }

    public function removeAspect() {
        $this->load->model('Settings_model');
        $postData = $this->input->post();
        $id = $postData['id'];
        $this->Settings_model->deleteAspect($id);
        echo $id;
    }

    public function addAspect() {
        $this->load->model('Settings_model');
        $postData = $this->input->post();
        $id = $postData['id'];
        $position = $this->Settings_model->getAspectPosition($id);
        $position ++;
        $aspect = new stdClass();
        $aspect->name = '[New]';
        $aspect->display_order = $position;
        $aspect->user_id = GUEST_UID;
        $this->Settings_model->insertAspect($aspect);

        echo $id;
    }
}
