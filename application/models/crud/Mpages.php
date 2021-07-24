<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud.php');

class Mpages extends Mcrud
{
    protected static $TABLE_NAME = "dbo_crud_pages";
    protected static $PRIMARY_KEY = "id";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'name';
    protected static $COL_VALUE = 'id';

    protected static $SOFT_DELETE = true;

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $arr = parent::list($filter, $limit, $offset, $orderby);
        if ($arr == null)   return $arr;

        foreach($arr as $key => $row) {
            if (empty($row['page_title'])) {
                $arr[$key]['page_title'] = ucwords( str_replace('.', ' ', $row['name']) );
            }    
        }

        return $arr;
    }    

    function get_page($name, $page_group = null) {
        //name based
        $this->db->where('name', $name);
        $this->db->where('is_deleted', 0);

        if ($page_group != null) {
            $this->db->group_start();
            $this->db->where('page_group', $page_group);
            $this->db->or_where('page_group', NULL);
            $this->db->group_end();
        }

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }
        $this->db->select($str);

        //get the data
        $arr = $this->db->get($table_name)->row_array();
        if ($arr == null)   return $arr;

        if (empty($arr['page_title'])) {
            $arr['page_title'] = ucwords( str_replace('.', ' ', $arr['name']) );
        }    
        
        return $arr;
    }

    function get_api_page($name, $enable_crud_api = true) {
        $filter = array();

        //only show api page
        if (!$enable_crud_api) {
            $filter['page_type'] = 'api';
        }

        //name based
        $filter['name'] = $name;

        $arr = parent::list($filter);
        if ($arr == null)   return $arr;

        //just get the first entry
        $arr = $arr[0];

        if (empty($arr['page_title'])) {
            $arr['page_title'] = ucwords( str_replace('.', ' ', $arr['name']) );
        }    
        
        return $arr;
    }

    function subtables($id, $with_table_meta = false) {
        $filter = array();

        $filter['page_id'] = $id;
        $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = 'dbo_crud_pages_subtables';

        $this->db->select('*');
        $this->db->order_by('order_no asc');
        $arr = $this->db->get_where($table_name, $filter)->result_array();

        if ($arr == null)   return $arr;

        if ($with_table_meta) {
            $ci =& get_instance();
            $ci->load->model('crud/Mtable');

            foreach($arr as $key => $val) {
                if (!$ci->Mtable->init($val['subtable_id'], true)) {
                    continue;
                }
                $tablemeta = $ci->Mtable->tablemeta();

                //dont autoload
                $tablemeta['initial_load'] = false;            

                $arr[$key]['crud'] = $tablemeta;
            }
        }

        return $arr;
    }

    function subtable_detail($id, $subtable_id, $with_table_meta = false) {
        $filter = array();

        $filter['page_id'] = $id;
        $filter['subtable_id'] = $subtable_id;
        $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = 'dbo_crud_pages_subtables';

        $this->db->select('*');
        $this->db->order_by('order_no asc');
        $arr = $this->db->get_where($table_name, $filter)->row_array();

        if ($arr == null)   return $arr;

        if ($with_table_meta) {
            $ci =& get_instance();
            $ci->load->model('crud/Mtable');
    
            if ($ci->Mtable->init($arr['subtable_id'], true)) {
                $tablemeta = $ci->Mtable->tablemeta();
    
                //dont autoload
                $tablemeta['initial_load'] = false;            
    
                $arr['crud'] = $tablemeta;
            }
        }

        return $arr;

    }
}

  