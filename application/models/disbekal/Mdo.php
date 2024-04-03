<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_ext.php');

class Mdo extends Mcrud_ext
{
    protected static $TABLE_NAME = "tcg_do";
    protected static $PRIMARY_KEY = "doid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'donum';
    protected static $COL_VALUE = 'doid';

    protected static $SOFT_DELETE = true;

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $siteid = $this->session->userdata("siteid");
        if (empty($siteid) && !empty($filter['siteid'])) {
            $siteid = $filter['siteid'];
        }
        unset($filter['siteid']);

        $itemtypeid = $this->session->userdata("itemtypeid");
        if (empty($itemtypeid) && !empty($filter['itemtypeid'])) {
            $itemtypeid = $filter['itemtypeid'];
        }
        unset($filter['itemtypeid']);

        $year = "";
        if (!empty($filter['year']))    $year = $filter['year'];
        unset($filter['year']);

        //build query
        $this->db->select("a.*");
        $this->db->select("b.ponum as poid_label, c.demandid, c.demandnum as demandid_label");
        $this->db->select("d.typeid as itemtypeid, d.typecode as itemtypeid_label, e.name as vendorid_label");
        $this->db->select("f.contractnum as contractid_label, g.nama as closedby_label, h.description as storeid_label");
        //$this->db->from("tcg_do a");
        $this->db->join("tcg_po b", "b.poid=a.poid and b.is_deleted=0", "INNER");
        $this->db->join("tcg_demand c", "c.demandid=b.demandid and c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=b.itemtypeid and d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_vendor e", "e.vendorid=a.vendorid and e.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_contract f", "f.contractid=a.contractid and f.is_deleted=0", "LEFT OUTER");
        $this->db->join("dbo_users g", "g.user_id=a.closedby and g.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_store h", "h.storeid=a.storeid and h.is_deleted=0", "LEFT OUTER");

        //filter
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($year)) {
            if ($year == date("Y")) {
                //for current year, include draft version
                $this->db->group_start();
                $this->db->where("b.financial_year", $year);
                $this->db->or_where("(a.status='DRAFT')");
                $this->db->group_end();                
            }
            else {
                $this->db->where("b.financial_year", $year);
            }
        }
        if (!empty($itemtypeid)) {
            $this->db->where("b.itemtypeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=b.siteid AND x.is_deleted=0", "INNER");
            $this->db->join("tcg_site y", "y.siteid=x.parentid AND y.is_deleted=0", "LEFT OUTER");
            $this->db->join("tcg_site z", "z.siteid=y.parentid AND z.is_deleted=0", "LEFT OUTER");
            $this->db->group_start();
            $this->db->where("x.siteid", $siteid);
            $this->db->or_where("y.siteid", $siteid);
            $this->db->or_where("z.siteid", $siteid);
            $this->db->group_end();
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

        $arr = $this->db->get("tcg_do a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }    

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['doid'] = $id;
        $arr = $this->list($filter);
        
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $siteid = $this->session->userdata("siteid");
        if (empty($siteid) && !empty($filter['siteid'])) {
            $siteid = $filter['siteid'];
        }
        unset($filter['siteid']);

        $itemtypeid = $this->session->userdata("itemtypeid");
        if (empty($itemtypeid) && !empty($filter['itemtypeid'])) {
            $itemtypeid = $filter['itemtypeid'];
        }
        unset($filter['itemtypeid']);

        $year = "";
        if (!empty($filter['year']))    $year = $filter['year'];
        unset($filter['year']);

        //build query
        $this->db->select("a.doid as value, a.donum as label");
        //$this->db->from("tcg_tenderid a");
        $this->db->join("tcg_po b", "b.poid=a.poid and b.is_deleted=0", "INNER");

        //filter
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($year)) {
            $this->db->where("b.financial_year", $year);
        }
        if (!empty($itemtypeid)) {
            $this->db->where("b.itemtypeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=b.siteid AND x.is_deleted=0", "INNER");
            $this->db->join("tcg_site y", "y.siteid=x.parentid AND y.is_deleted=0", "LEFT OUTER");
            $this->db->join("tcg_site z", "z.siteid=y.parentid AND z.is_deleted=0", "LEFT OUTER");
            $this->db->group_start();
            $this->db->where("x.siteid", $siteid);
            $this->db->or_where("y.siteid", $siteid);
            $this->db->or_where("z.siteid", $siteid);
            $this->db->group_end();
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        //not draft
        $this->db->where('a.status!=', 'DRAFT');

        $arr = $this->db->get("tcg_do a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        //must have poid
        if (empty($valuepair['poid'])) {
            return 0;
        }

        $valuepair['status'] = 'DRAFT';

        //default
        if (empty($valuepair['donum'])) {
            $valuepair['donum'] = 'DO0000';
        }

        //TODO: check against userdata('typeid') and userdata('siteid')

        $id = parent::add($valuepair, $enforce_edit_columns);

        if ($id > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_do_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $id;
    }   

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        if ($filter == null)    $filter = array();

        //TODO: check against userdata('typeid') and userdata('siteid')

        $result = parent::update($id, $valuepair, $filter, $enforce_edit_columns);

        if ($result > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_do_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $result;
    }   

    function delete($id, $filter = null) {

        //TODO: check against userdata('typeid') and userdata('siteid')

        parent::delete($id, $filter);

        //run data consistency
        $pengguna_id = $this->session->userdata("user_id");
        $this->db->query("call usp_do_dataconsistency(?,?)", array($id,$pengguna_id));
    }  
}

  