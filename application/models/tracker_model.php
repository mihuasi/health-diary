<?php
class Tracker_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function get_aspects() {
        $query = $this->db->get_where('health_aspect', array('user_id'=>GUEST_UID));
        return $query->result();
    }

    public function put_aspect($entry) {
        $this->db->insert('health_aspect_values', $entry); 
    }

    public function get_influences() {
        $query = $this->db->order_by('time_taken', 'ASC')
            ->get_where('influence', array('user_id'=>GUEST_UID));
        return $query->result();
    }

    public function put_influence($entry) {
        $this->db->insert('influence_values', $entry); 
    }

    private function currentLatestQuery($baseTable) {
        return 'SELECT date FROM ' . $baseTable . '_values v '
                . ' INNER JOIN ' . $baseTable . ' a ON (a.user_id = ? AND v.' . $baseTable . '_id = a.id)'
                . ' ORDER BY date DESC LIMIT 1';
    }

    public function get_current_day() {
        $aspectResults = $this->currentLatestQuery('health_aspect');
        $influenceResults = $this->currentLatestQuery('influence');
        $aspectDate = $this->db->query($aspectResults, array(GUEST_UID))->result();
        $influenceDate = $this->db->query($influenceResults, array(GUEST_UID))->result();
        $mysqlDate = offsetFromMySqlDate(date('Y-m-d'), '-1 day');

        if (!empty($aspectDate)) {
            $aspectDate = reset($aspectDate);
            $mysqlDate = $aspectDate->date;
        }
        if (!empty($influenceDate)) {
            $influenceDate = reset($influenceDate);
        }
        if (!empty($influenceDate)) {
            if (!$aspectDate || dateCompare($influenceDate->date, $aspectDate->date)) {
                $mysqlDate = $influenceDate->date;
            }
        }
        $currentDay = offsetFromMySqlDate($mysqlDate, '+1 day');
        return $currentDay;
    }

    private function getPreviousDaysQuery($baseTable) {
        $sql = 'SELECT v.*, h.name FROM ' . $baseTable . '_values v 
            JOIN ' . $baseTable . ' h ON (h.id = v.' . $baseTable . '_id AND h.user_id = ?)
            ORDER BY v.date DESC, h.display_order ASC';
        return $sql;
    }

    public function getPreviousDays() {
        $aspectResults = $this->getPreviousDaysQuery('health_aspect');
        $influenceResults = $this->getPreviousDaysQuery('influence');
        $aspects = $this->db->query($aspectResults, array(GUEST_UID))->result();
        $influences = $this->db->query($influenceResults, array(GUEST_UID))->result();
        $days = array();
        foreach ($aspects as $aspect) {
            $date = $aspect->date;
            $aspect->comments = explode(DB_DELIMITER, $aspect->comment);
            $days[$date] = new stdClass();
            $days[$date]->aspects[$aspect->health_aspect_id] = $aspect;
        }
        foreach ($influences as $influence) {
            $date = $influence->date;
            $influence->comments = explode(DB_DELIMITER, $influence->comment);
            if (!isset($days[$date])) {
                $days[$date] = new stdClass();
            }
            $days[$date]->influences[$influence->influence_id] = $influence;
        }
        return $days;
    }

    public function getCurrentRatings($date, $type) {
        $sql = 'SELECT ' . $type . '_id, v.value FROM ' . $type . '_values v
            JOIN ' . $type . ' i ON (v.' . $type . '_id = i.id AND i.user_id = ?)
            WHERE v.date = ? AND v.value IS NOT NULL';
        $itemRatings = $this->db->query($sql, array(GUEST_UID, $date))->result();
        $items = array();
        if ($itemRatings) {
            foreach ($itemRatings as $itemRating) {
                $id = $itemRating->{$type . '_id'};
                $items[$id] = $itemRating->value;

            }
        }
        return $items;
    }

    public function getCurrentItems($date, $type) {
        $sql = 'SELECT ' . $type . '_id, v.comment FROM ' . $type . '_values v
            JOIN ' . $type . ' i ON (v.' . $type . '_id = i.id AND i.user_id = ?)
            WHERE v.date = ?';
        $itemComments = $this->db->query($sql, array(GUEST_UID, $date))->result();
        $items = array();
        foreach ($itemComments as $itemComment) {
            $id = $itemComment->{$type . '_id'};
            if (!isset($items[$id])) {
                $items[$id] = array();
            }
            $comments = explode(DB_DELIMITER, $itemComment->comment);
            foreach ($comments as $comment) {
                if (!in_array($comment, $items[$id])) {
                    $items[$id][] = $comment;
                }
            }
        }
        return $items;
    }

    public function removeItemsOnDay($day, $type) {
        $categories = $this->db->query('SELECT * FROM ' . $type . ' WHERE user_id = ? ', array(GUEST_UID))->result();
        foreach ($categories as $category) {
            $this->db->delete($type . '_values', array('date' => $day, $type . '_id' => $category->id));
        }
    }

    public function getSuggestions() {
    $sql = 'SELECT influence_id, v.comment FROM influence_values v
            JOIN influence i ON (v.influence_id = i.id AND i.user_id = ?)';
    $influenceComments = $this->db->query($sql, array(GUEST_UID))->result();
    $influences = array();
    foreach ($influenceComments as $influenceComment) {
        $id = $influenceComment->influence_id;
        if (!isset($influences[$id])) {
            $influences[$id] = array();
        }
        $comments = explode(DB_DELIMITER, $influenceComment->comment);
        foreach ($comments as $comment) {
            if (!in_array($comment, $influences[$id])) {
                $influences[$id][] = $comment;
            }
        }
    }
    return $influences;
}
}