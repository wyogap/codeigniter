<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msession extends CI_Model
{

    public function get_session() {

        //table metas
        $this->db->select('a.*');
        $this->db->order_by('a.order_no asc');

        $this->db->where('a.is_deleted', 0);

        $arr = $this->db->get('dbo_sessions a')->result_array();
        if ($arr == null) {
            return false;
        }

        return $arr;
    }

    public function get_user_session($user_id) {
        $this->db->select('a.*');

        $this->db->where('a.user_id', $user_id);
        $this->db->where('a.is_deleted', 0);

        $arr = $this->db->get('dbo_user_sessions a')->result_array();
        if ($arr == null) {
            return false;
        }

        return $arr;
    }
}