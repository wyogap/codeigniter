<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mitem extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_item";
    protected static $PRIMARY_KEY = "itemid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'description';
    protected static $COL_VALUE = 'itemid';

    protected static $SOFT_DELETE = true;

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;
        
        $this->db->select("a.*");
        $this->db->select("b.name as manufacturerid_label");
        $this->db->select("c.description as categoryid_label");
        $this->db->select("d.typeid, d.description as typeid_label");
        $this->db->select("e.name as preferredvendorid_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=c.typeid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");

        //filter
        if (!empty($filter) && is_array($filter)) {
            //clean up non existing filter columns
            $ci_name = null;
            foreach($filter as $key => $val) {
                if ($key == 'typeid') {
                    $this->db->where("d.typeid", $val);
                    continue;
                }
                $ci_name = strtoupper($key);
                if (false !== array_search($ci_name, $this->columns)) {
                    $this->db->where("a.$key", $val);
                }
            }
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

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

        $arr = $this->db->get("tcg_item a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }    

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;
        
        $this->db->select("a.*");
        $this->db->select("b.name as manufacturerid_label");
        $this->db->select("c.description as categoryid_label");
        $this->db->select("d.typeid, d.description as typeid_label");
        $this->db->select("e.name as preferredvendorid_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=c.typeid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");

        //filter
        if (!empty($filter) && is_array($filter)) {
            //clean up non existing filter columns
            $ci_name = null;
            foreach($filter as $key => $val) {
                if ($key == 'typeid') {
                    $this->db->where("d.typeid", $val);
                    continue;
                }
                $ci_name = strtoupper($key);
                if (false !== array_search($ci_name, $this->columns)) {
                    $this->db->where("a.$key", $val);
                }
            }
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');
        $this->db->where("a.itemid", $id);

        $arr = $this->db->get("tcg_item a")->row_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        $this->db->select("a.itemid as value, a.description as label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=c.typeid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");

        //filter
        if (!empty($filter) && is_array($filter)) {
            //clean up non existing filter columns
            $ci_name = null;
            foreach($filter as $key => $val) {
                if ($key == 'typeid') {
                    $this->db->where("d.typeid", $val);
                    continue;
                }
                $ci_name = strtoupper($key);
                if (false !== array_search($ci_name, $this->columns)) {
                    $this->db->where("a.$key", $val);
                }
            }
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        $arr = $this->db->get("tcg_item a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }
}

  