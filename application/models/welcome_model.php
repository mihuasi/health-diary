<?php
class Welcome_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function get_hello() {
        $query = $this->db->get('hello', 1);
        return $query->result();
    }
}