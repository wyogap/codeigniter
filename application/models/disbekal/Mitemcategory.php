<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mitemcategory extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_itemcategory";
    protected static $PRIMARY_KEY = "categoryid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'description';
    protected static $COL_VALUE = 'categoryid';

    protected static $SOFT_DELETE = true;

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        // //default
        // $itemtypeid = $this->session->userdata("itemtypeid");
        // if (empty($itemtypeid) && !empty($filter['typeid'])) {
        //     $itemtypeid = $filter['typeid'];
        // }
        // unset($filter['typeid']);

        $this->db->select("a.categoryid as value, a.description as label");

        //filter
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        // if (!empty($itemtypeid)) {
        //     $this->db->where("a.typeid", $itemtypeid);
        // }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        $arr = $this->db->get("tcg_itemcategory a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }
}

  