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

    public function merge($mergedid, $ids) {
        if (empty($ids)) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_item a
        set a.tag=?
        where a.is_merged=0 and a.is_deleted=0 and a.itemid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_item_mergebytag(?, ?, ?)";
        $query = $this->db->query($sql, array($tag, $mergedid, $userid));

        return $affected;
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $typeid = $this->session->userdata("itemtypeid");
        if (empty($typeid)) {
            $typeid = empty($filter['typeid']) ? null : $filter['typeid'];
        }
        unset($filter['typeid']);

        $fastmoving = (!isset($filter['fastmoving']) || $filter['fastmoving'] == null || $filter['fastmoving'] == '') ? null : $filter['fastmoving'];
        unset($filter['fastmoving']);
        
        $this->db->select("a.*");
        $this->db->select("b.name as manufacturerid_label");
        $this->db->select("c.description as categoryid_label");
        $this->db->select("d.typecode as typeid_label");
        $this->db->select("e.name as preferredvendorid_label");
        $this->db->select("f.label as issueunit_label, g.label as orderunit_label");
        //$this->db->from("tcg_item a");
        $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=a.typeid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");
        $this->db->join("lk_unit f", "f.unit=a.issueunit AND f.is_deleted=0", "LEFT OUTER");
        $this->db->join("lk_unit g", "g.unit=a.orderunit AND g.is_deleted=0", "LEFT OUTER");

        //filter
        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            if ($val == null || $val == '') continue;

            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($typeid)) {
            $this->db->where("a.typeid", $typeid);
        }
        if ($fastmoving != null) {
            $this->db->where("a.fastmoving", $fastmoving);
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        //not merged
        $this->db->where('a.is_merged', '0');

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
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['itemid'] = $id;

        $arr = $this->list($filter);
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
        
        // $this->db->select("a.*");
        // $this->db->select("b.name as manufacturerid_label");
        // $this->db->select("c.description as categoryid_label");
        // $this->db->select("d.typeid, d.description as typeid_label");
        // $this->db->select("e.name as preferredvendorid_label");
        // //$this->db->from("tcg_item a");
        // $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        // $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        // $this->db->join("tcg_itemtype d", "d.typeid=a.typeid AND d.is_deleted=0", "LEFT OUTER");
        // $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");

        // //filter
        // if (!empty($filter) && is_array($filter)) {
        //     //clean up non existing filter columns
        //     $ci_name = null;
        //     foreach($filter as $key => $val) {
        //         if ($key == 'typeid') {
        //             $this->db->where("d.typeid", $val);
        //             continue;
        //         }
        //         $ci_name = strtoupper($key);
        //         if (false !== array_search($ci_name, $this->columns)) {
        //             $this->db->where("a.$key", $val);
        //         }
        //     }
        // }

        // //soft delete
        // $this->db->where('a.is_deleted', '0');
        // $this->db->where("a.itemid", $id);

        // $arr = $this->db->get("tcg_item a")->row_array();
        // if ($arr == null)       return $arr;

        // return $arr;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $valuepair['typeid'] = $typeid;
        }

        return parent::add($valuepair, $enforce_edit_columns);
    }   

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        if ($filter == null)    $filter = array();

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $filter['typeid'] = $typeid;
        }

        return parent::update($id, $valuepair, $filter, $enforce_edit_columns);
    }   

    function delete($id, $filter = null) {
        if ($filter == null)    $filter = array();

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $filter['typeid'] = $typeid;
        }

        return parent::delete($id, $filter);
    }   

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $typeid = $this->session->userdata("itemtypeid");
        if (empty($typeid)) {
            $typeid = empty($filter['typeid']) ? null : $filter['typeid'];
        }
        unset($filter['typeid']);

        $fastmoving = (!isset($filter['fastmoving']) || $filter['fastmoving'] == null || $filter['fastmoving'] == '') ? null : $filter['fastmoving'];
        unset($filter['fastmoving']);

        $this->db->select("a.itemid as value, a.description as label, c.categoryid, d.typeid as itemtypeid");
        //$this->db->from("tcg_item a");
        $this->db->join("tcg_manufacturer b", "b.manufacturerid=a.manufacturerid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=a.categoryid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=a.typeid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.preferredvendorid AND e.is_deleted=0", "LEFT OUTER");

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            if ($val == null || $val == '') continue;

            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($typeid)) {
            $this->db->where("a.typeid", $typeid);
        }
        if ($fastmoving != null) {
            $this->db->where("a.fastmoving", $fastmoving);
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        $this->db->order_by("a.description asc");

        $arr = $this->db->get("tcg_item a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }
}

  