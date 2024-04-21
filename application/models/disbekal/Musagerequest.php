<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_ext.php');

class Musagerequest extends Mcrud_ext
{
    protected static $TABLE_NAME = "tcg_usagerequest";
    protected static $PRIMARY_KEY = "usagerequestid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'usagerequestnum';
    protected static $COL_VALUE = 'usagerequestid';

    protected static $SOFT_DELETE = true;

    function close($id) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_usage_close(?, ?)";

        $this->db->query($sql, array($id, $userid));
 
        $detail = $this->detail($id);
        if ($detail == null)    return null;
 
        //var_dump($detail); exit;

        if ($detail['status'] != 'CLOSED') {
            return null;
        }

        return $detail;
    }

    function approve($id) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_usage_approve(?, ?)";

        $this->db->query($sql, array($id, $userid));
 
        $detail = $this->detail($id);
        if ($detail == null)    return null;

        if ($detail['status'] != 'APPR') {
            return null;
        }

        return $detail;
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
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
        $this->db->select("b.ponum as poid_label, c.demandid, c.demandnum as demandid_label, x.name as siteid_label");
        $this->db->select("d.typecode as itemtypeid_label, g.name as requestby_label");
        //$this->db->from("tcg_usagerequest a");
        $this->db->join("tcg_po b", "b.poid=a.poid and b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_demand c", "c.demandid=b.demandid and c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype d", "d.typeid=a.itemtypeid and d.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_person g", "g.personid=a.requestby and g.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site x", "x.siteid=a.siteid AND x.is_deleted=0", "LEFT OUTER");

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
                $this->db->where("a.financial_year", $year);
                $this->db->or_where("(a.status='DRAFT')");
                $this->db->group_end();                
            }
            else {
                $this->db->where("a.financial_year", $year);
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

        $arr = $this->db->get("tcg_usagerequest a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }    

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['usagerequestid'] = $id;
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
        $this->db->select("a.usagerequestid as value, a.usagerequestnum as label");
        //$this->db->from("tcg_tenderid a");
        //$this->db->join("tcg_po b", "b.poid=a.poid and b.is_deleted=0", "INNER");

        //filter
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($year)) {
            $this->db->where("a.financial_year", $year);
        }
        if (!empty($itemtypeid)) {
            $this->db->where("a.itemtypeid", $itemtypeid);
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

        $arr = $this->db->get("tcg_usagerequest a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $valuepair['itemtypeid'] = $typeid;
        }

        $siteid = $this->session->userdata("siteid");
        if (!empty($valuepair['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($valuepair['siteid']);
        }
        if (!empty($siteid)) {
            //enforce
            $valuepair['siteid'] = $siteid;
        }

        $valuepair['status'] = 'DRAFT';
        
        //default
        if (empty($valuepair['usagerequestnum'])) {
            $valuepair['usagerequestnum'] = 'UR0000';
        }

        //TODO: check against userdata('typeid') and userdata('siteid')

        $id = parent::add($valuepair, $enforce_edit_columns);

        if ($id > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_usage_dataconsistency(?,?)", array($id,$pengguna_id));
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

        $siteid = $this->session->userdata("siteid");
        if (!empty($valuepair['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($valuepair['siteid']);
        }
        if (!empty($siteid)) {
            //enforce
            $filter['siteid'] = $siteid;
            $valuepair['siteid'] = $siteid;
        }

        $result = parent::update($id, $valuepair, $filter, $enforce_edit_columns);

        if ($result > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_usage_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $result;
    }   

    function delete($id, $filter = null) {
 
        //TODO: check against userdata('typeid') and userdata('siteid')

        parent::delete($id, $filter);

        //run data consistency
        $pengguna_id = $this->session->userdata("user_id");
        $this->db->query("call usp_usage_dataconsistency(?,?)", array($id,$pengguna_id));
    }  
}

  