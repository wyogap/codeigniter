<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mnavigation extends CI_Model
{

    public function get_navigation($role_id = null, $page_group = null) {
        if ($role_id == null) {
            $role_id = $this->session->userdata('role_id');
        }

        //table metas
        $this->db->select('a.*, b.name as page_name');
        $this->db->order_by('a.order_no asc');
        $this->db->join('dbo_crud_pages b', 'b.id=a.page_id and b.is_deleted=0', 'LEFT OUTER');

        $this->db->where('a.role_id', $role_id);
        $this->db->where('a.is_deleted', 0);

        if ($page_group != null) {
            $this->db->group_start();
            $this->db->where('a.page_group', $page_group);
            $this->db->or_where('a.page_group', NULL);
            $this->db->group_end();
        }

        $arr = $this->db->get('dbo_crud_navigations a')->result_array();
        if ($arr == null) {
            return false;
        }

        $cur_navitem = null;
        foreach($arr as $key => $row) {
            if ($row['nav_type'] == 'item') {
                $cur_navitem = $key;

                $arr[$key]['pages'] = array();
                $arr[$key]['tags'] = array();
                if ($row['action_type'] == 'page' && !empty($row['page_name'])) {
                    $arr[$key]['pages'][] = $row['page_name'];
                    if (!empty($row['nav_tag'])) {
                        $arr[$key]['tags'][] = $row['nav_tag'];
                    }
                }
                $arr[$key]['subitems'] = array();
            }
            else if ($row['nav_type'] == 'subitem'){
                //add as subitem in the current navitem
                if (isset($arr[$cur_navitem])) {
                    $arr[$cur_navitem]['subitems'][] = $row;
                    $arr[$cur_navitem]['pages'][] = $row['page_name'];
                    if (!empty($row['nav_tag'])) {
                        $arr[$cur_navitem]['tags'][] = $row['nav_tag'];
                    }
                }
                //remove
                unset($arr[$key]);
            }
        }

        return $arr;
    }

}