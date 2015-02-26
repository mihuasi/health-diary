<?php
class Settings_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function updateField($table, $field, $id, $value) {
        $data = array($field=>$value);
        $this->db->update($table, $data, array('id'=>$id));
    }

    public function deleteInfluence($id) {
        $this->db->delete('influence', array('id' => $id, 'user_id' => GUEST_UID));
    }

    public function deleteAspect($id) {
        $this->db->delete('health_aspect', array('id' => $id, 'user_id' => GUEST_UID));
    }

    public function getInfluenceTime($id) {
        $query = $this->db->get_where('influence', array('id'=>$id));
        $influence = $query->result();
        return (int) reset($influence)->time_taken;
    }

    public function insertInfluence($influence) {
        $influence->date_added = mysqlNow();
        $this->db->insert('influence', $influence);
    }

    public function insertAspect($aspect) {
        $aspect->date_added = mysqlNow();
        $this->db->insert('health_aspect', $aspect);
    }

    public function getAspectPosition($id) {
        $query = $this->db->get_where('health_aspect', array('id'=>$id));
        $aspect = $query->result();
        return (int) reset($aspect)->display_order;
    }
}