<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mitemtype extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_itemtype";
    protected static $PRIMARY_KEY = "typeid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'typecode';
    protected static $COL_VALUE = 'typeid';

    protected static $SOFT_DELETE = true;

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            $this->db->where("a.typeid", $typeid);
        }

        $this->db->select("a.typeid as value, a.typecode as label");

        //soft delete
        $this->db->where('a.is_deleted', '0');

        $this->db->order_by("a.typecode asc");

        $arr = $this->db->get("tcg_itemtype a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }
}

  