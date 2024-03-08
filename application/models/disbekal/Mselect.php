<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mselect extends CI_Model
{

    public function __construct()
    {
    }

    public function store() {
        $sql = "select storeid as value, description as label from tcg_store where is_deleted=0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function lk_store() {
        $sql = "select storeid, description, storecode, latitude, longitude, orgcode, sitecode from tcg_store where is_deleted=0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}

  