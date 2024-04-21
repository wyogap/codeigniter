<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mstore extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_store";
    protected static $PRIMARY_KEY = "storeid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'storecode';
    protected static $COL_VALUE = 'storeid';

    protected static $SOFT_DELETE = true;

    /**
     * Akses gudang:
     * - Satuan kerja langsung
     * - Satuan kerja di bawahnya (semua level)
     * - Satuan kerja 1 level di atasnya
     */
    function lookup($filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter=array();

        $siteid = $this->session->userdata('siteid');
        if (empty($siteid)) {
            $siteid = empty($filter['siteid']) ? '' : $filter['siteid'];
        } 
        unset($filter['siteid']);

        $this->db->select("a.storeid as value, a.description as label");
        //$this->db->from("tcg_store a");

        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=a.siteid and x.is_deleted=0", "INNER");
            $this->db->join("tcg_site y", "y.siteid=x.parentid and y.is_deleted=0", "LEFT OUTER");
            $this->db->join("tcg_site z", "z.siteid=y.parentid and z.is_deleted=0", "LEFT OUTER");
            $this->db->group_start();
            $this->db->where("x.siteid", $siteid);
            $this->db->or_where("y.siteid", $siteid);
            $this->db->or_where("z.siteid", $siteid);
            $this->db->group_end();
        }

        //soft delete
        $this->db->where("a.is_deleted=0");
 
        $arr = $this->db->get("tcg_store a")->result_array();
        if ($arr == null)     return $arr;

        return $arr;
    }
}

  