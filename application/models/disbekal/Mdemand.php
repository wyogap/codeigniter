<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mdemand extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_demand";
    protected static $PRIMARY_KEY = "demandid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'description';
    protected static $COL_VALUE = 'demandid';

    protected static $SOFT_DELETE = true;

    function approve($id) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_demand_approve(?, ?)";

        $arr = $this->db->query($sql, array($id, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'APPR') {
            return null;
        }

        return $detail;
    }

    function close($id) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_demand_close(?, ?)";

        $query = $this->db->query($sql, array($id, $userid));
        if ($query == null)    return $query;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'CLOSED') {
            return null;
        }

        return $detail;
    }

    function buatpengadaan($id, $year, $ponum) {
        $userid = $this->session->userdata('user_id');
      
        //call sp
        $tag = uniqid();
        $sql = "call usp_po_createfromdemand(?, ?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($id, $year, $ponum, $tag, $userid));
        if ($arr == null)    return $arr;
 
        return $this->_get_poid_bytag($tag);
    }

    function _get_poid_bytag($tag) {
        $sql = "select poid from tcg_po where tag=?";

        $arr = $this->db->query($sql, array($tag))->row_result();
        if ($arr == null)   return 0;

        return $arr['poid'];
    }

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

        //filter based on demanddate of the instance (in case of periodic demand)
        $subquery1 = "";
        if (!empty($year)) {
            $this->db->select("a.demandid, year(a.demanddate) as `year`, count(*) as cnt");
            $this->db->from("rpt_demandinstance a");
            $this->db->join("tcg_demand b", "b.demandid=a.demandid and b.is_deleted=0", "LEFT OUTER");
            $this->db->group_by("a.demandid, year(a.demanddate)");
            $this->db->where("a.is_deleted=0");
            
            $this->db->where("year(a.demanddate)", $year);
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

            $subquery1 = $this->db->get_compiled_select();
        
            $this->db->reset_query();
        }

        $this->db->select("a.*");
        $this->db->select("x.name as siteid_label, d.typecode as itemtypeid_label, f.label as frequencyunit_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_itemtype d", "d.typeid=a.itemtypeid and d.is_deleted=0", "LEFT OUTER");
        if (!empty($year)) {
            $this->db->join("(" .$subquery1. ") e", "e.demandid=a.demandid", "LEFT OUTER");
        }
        $this->db->join("dbo_lookups f", "f.value=a.frequencyunit and f.group='date_unit' and f.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site x", "x.siteid=a.siteid AND x.is_deleted=0", "INNER");

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
                $this->db->where("e.demandid is not null");
                $this->db->or_where("(a.status='DRAFT')");
                $this->db->group_end();                
            }
            else {
                $this->db->where("e.demandid is not null");
            }
        }
        if (!empty($itemtypeid)) {
            $this->db->where("a.itemtypeid", $itemtypeid);
        }
        if (!empty($siteid)) {
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

        // $query = $this->db->get_compiled_select();
        // var_dump($query); exit;

        $arr = $this->db->get("tcg_demand a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }    

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['demandid'] = $id;
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

        //filter based on demanddate of the instance (in case of periodic demand)
        $subquery1 = "";
        if (!empty($year)) {
            $this->db->select("a.demandid, year(a.demanddate) as `year`, count(*) as cnt");
            $this->db->from("rpt_demandinstance a");
            $this->db->join("tcg_demand b", "b.demandid=a.demandid and b.is_deleted=0", "LEFT OUTER");
            $this->db->group_by("a.demandid, year(a.demanddate)");
            $this->db->where("a.is_deleted=0");
            
            $this->db->where("year(a.demanddate)", $year);
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

            $subquery1 = $this->db->get_compiled_select();
        
            $this->db->reset_query();
        }

        $this->db->select("a.demandid as value, a.demandnum as label");
        //$this->db->from("tcg_demand a");
        if (!empty($year)) {
            $this->db->join("(" .$subquery1. ") e", "e.demandid=a.demandid", "LEFT OUTER");
        }

        //filter
        $this->db->where($filter);
        if (!empty($year)) {
            $this->db->where("e.demandid is not null");
        }
        if (!empty($itemtypeid)) {
            $this->db->where("a.itemtypeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=a.siteid AND x.is_deleted=0", "INNER");
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
        
        $arr = $this->db->get("tcg_demand a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $valuepair['typeid'] = $typeid;
        }

        //default
        if (empty($valuepair['demandnum'])) {
            $valuepair['demandnum'] = 'RB0000';
        }

        $id = parent::add($valuepair, $enforce_edit_columns);

        if ($id > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_demand_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $id;
    }   

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        if ($filter == null)    $filter = array();

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $filter['typeid'] = $typeid;
            $valuepair['typeid'] = $typeid;
        }

        $result = parent::update($id, $valuepair, $filter, $enforce_edit_columns);

        if ($result > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_demand_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $result;
    }   

    function delete($id, $filter = null) {
        parent::delete($id, $filter);
        //run data consistency
        $pengguna_id = $this->session->userdata("user_id");
        $this->db->query("call usp_demand_dataconsistency(?,?)", array($id,$pengguna_id));
    }   

}

  