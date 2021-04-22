<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mcrud extends CI_Model
{
    protected static $TABLE_NAME = "table";
    protected static $PRIMARY_KEY = "id";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();
    protected static $SEARCHES = array();
    protected static $COL_LABEL = 'label';
    protected static $COL_VALUE = 'value';

    protected static $VIEW_TABLE_NAME = null;
    protected static $SOFT_DELETE = true;

    protected static $JOIN_TABLES = array();
    
    function __construct() {
        if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
            if (!isset(static::$FILTERS) || !is_array(static::$FILTERS) || count(static::$FILTERS) == 0) {
                static::$FILTERS = static::$COLUMNS;
            }
        }
    }

    function distinct_lookup($column_name, $filter = null) {
        if (empty(static::$COL_LABEL) || empty(static::$COL_VALUE)) return null;

        if ($filter == null || !is_array($filter)) {
            $filter = array();
        }

        //clean up non existing filter columns
        if (isset(static::$FILTERS) && is_array(static::$FILTERS) && count(static::$FILTERS) > 0 && count($filter)) {
            foreach($filter as $id => $value) {
                if (false === array_search($id, static::$FILTERS)) {
                    //invalid filter columns
                    unset($filter[$id]);
                }
            }
        }

        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $this->db->distinct();
        $this->db->select($column_name .' as value ');
        $arr = $this->db->get_where($table_name, $filter)->result_array();
        if ($arr == null)       return $arr;

        foreach($arr as $key => $row) {
            $arr[$key]['label'] = $row['value'];
        }

        return $arr;
    }
    
    function lookup($filter = null) {
        if (empty(static::$COL_LABEL) || empty(static::$COL_VALUE)) return null;

        if ($filter == null || !is_array($filter)) {
            $filter = array();
        }

        //clean up non existing filter columns
        if (isset(static::$FILTERS) && is_array(static::$FILTERS) && count(static::$FILTERS) > 0 && count($filter)) {
            foreach($filter as $id => $value) {
                if (false === array_search($id, static::$FILTERS)) {
                    //invalid filter columns
                    unset($filter[$id]);
                }
            }
        }

        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $this->db->select(static::$COL_LABEL .' as label, '. static::$COL_VALUE .' as value');
        return $this->db->get_where($table_name, $filter)->result_array();
    }

    function search($query, $filter = null, $limit = null, $offset = null, $orderby = null) {
        if ($filter == null) $filter = array();

        //clean up non existing filter columns
        if ($query != "" && $query != null) {
            $this->db->group_start();
            foreach($this->SEARCHES as $key => $val) {
                $this->db->or_like($val, $query);
            }
            //if no column list specified for search, at least search in label an value column
            if (count($this->SEARCHES) == 0) {
                if (!empty(static::$COL_LABEL)) {
                    $this->db->or_like(static::$COL_LABEL, $query);
                }
                if (!empty(static::$COL_VALUE)) {
                    $this->db->or_like(static::$COL_VALUE, $query);
                }
            }
            $this->db->group_end();
        }

        if ($filter != null && count($filter) > 0) {
            foreach($filter as $key => $val) {
                if (count(static::$FILTERS) == 0 || false !== array_search($key, static::$FILTERS)) {
                    $this->db->where($key, $val);
                }
            }
        }

        if (static::$SOFT_DELETE)   $this->db->where('is_deleted', 0);

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $this->db->select($str);
        $this->db->from($table_name);

        //order by
        if (!empty($orderby)) {
            if (is_array($orderby)) {
                foreach($orderby as $value) {
                    $this->db->order_by($value);
                }
            }
            else {
                $this->db->order_by($orderby);
            }
        }

        return $this->db->get($limit, $offset)->result_array();
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if ($filter == null) $filter = array();

        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            if (count(static::$FILTERS) == 0 || false !== array_search($key, static::$FILTERS)) {
                $this->db->where($key, $val);
            }
        }

        if (static::$SOFT_DELETE)   $this->db->where('is_deleted', 0);

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $this->db->select($str);
        $this->db->from($table_name);

        //order by
        if (!empty($orderby)) {
            if (is_array($orderby)) {
                foreach($orderby as $value) {
                    $this->db->order_by($value);
                }
            }
            else {
                $this->db->order_by($orderby);
            }
        }

        return $this->db->get($limit, $offset)->result_array();
    }

    function detail($id, $filter = null) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;
        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $this->db->select($str);

        return $this->db->get_where($table_name, $filter)->row_array();       
    }

    function update($id, $valuepair, $filter = null) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;

        //clean up non existing columns
        if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
            foreach(array_keys($valuepair) as $key1) {
                if (false === array_search($key1, static::$COLUMNS)) {
                    //invalid columns
                    unset($valuepair[$key1]);
                }
            }
        }

        //inject updated 
        $valuepair['updated_on'] = date('Y/m/d H:i:s');
        $valuepair['updated_by'] = $this->session->userdata('user_id');

        $this->db->update(static::$TABLE_NAME, $valuepair, $filter);
        
        $affected = $this->db->affected_rows();
        if ($affected > 0) {
            //audit trail
            audittrail_update(static::$TABLE_NAME, $id, $valuepair);
        }

        return $id;
    }

    function delete($id, $filter = null) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;

        //var_dump($filter); exit;

        if (static::$SOFT_DELETE) {
            $valuepair = array (
                'is_deleted' => 1,
                'updated_on' => date('Y/m/d H:i:s'),
                'updated_by' => $this->session->userdata('user_id')
            );

            $this->db->update(static::$TABLE_NAME, $valuepair, $filter);   
        }
        else {
            $this->db->delete(static::$TABLE_NAME, $filter);
        }

        $affected = $this->db->affected_rows();
        if ($affected > 0) {
            //audit trail
            audittrail_delete(static::$TABLE_NAME, $id);
        }

        return $affected;
    }

    function add($valuepair) {
        //clean up non existing columns
        if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
            foreach(array_keys($valuepair) as $id) {
                if (false === array_search($id, static::$COLUMNS)) {
                    //invalid columns
                    unset($valuepair[$id]);
                }
            }
        }

        //inject
        $valuepair['created_by'] = $this->session->userdata('user_id');

        $query = $this->db->insert(static::$TABLE_NAME, $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //audit trail
            audittrail_insert(static::$TABLE_NAME, $id, $valuepair);

            return $id;
        } else {
            return 0;
        } 
    }

}

  