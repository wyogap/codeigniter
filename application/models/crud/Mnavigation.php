<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mnavigation extends CI_Model
{

    public function get_navigation($role_id = null) {
        if ($role_id == null) {
            $role_id = $this->session->userdata('role_id');
        }

        //table metas
        $this->db->select('a.*, b.name as page_name');
        $this->db->order_by('a.order_no asc');
        $this->db->join('dbo_crud_pages b', 'b.id=a.page_id and b.is_deleted=0', 'LEFT OUTER');
        $arr = $this->db->get_where('dbo_crud_navigations a', array('a.role_id'=>$role_id, 'a.is_deleted'=>0))->result_array();
        if ($arr == null) {
            return false;
        }

        $cur_navitem = null;
        foreach($arr as $key => $row) {
            if ($row['nav_type'] == 'item') {
                $cur_navitem = $key;
                $arr[$key]['pages'] = array();
                if ($row['action_type'] == 'page' && !empty($row['page_name'])) {
                    $arr[$key]['pages'][] = $row['page_name'];
                }
                $arr[$key]['subitems'] = array();
            }
            else if ($row['nav_type'] == 'subitem'){
                //add as subitem in the current navitem
                $arr[$cur_navitem]['subitems'][] = $row;
                $arr[$cur_navitem]['pages'][] = $row['page_name'];
                //remove
                unset($arr[$key]);
            }
        }

        return $arr;
    }

}