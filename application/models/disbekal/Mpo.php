<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mpo extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_po";
    protected static $PRIMARY_KEY = "poid";

    /* List of column names. For use in select validation if necessary. 
     * IMPORTANT: Always use all uppercase for column name
     * */
    protected static $COLUMNS = array('POID', 'PONUM');
    /* List of column names. For use in filtering. */
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'ponum';
    protected static $COL_VALUE = 'poid';

    protected static $SOFT_DELETE = true;

    function __construct() {
        parent::__construct();

        $this->columns = static::$COLUMNS;
    }

    function approve($id) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_po_approve(?, ?)";

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
        $sql = "call usp_po_close(?, ?)";

        $arr = $this->db->query($sql, array($id, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'CLOSED') {
            return null;
        }

        return $detail;
    }

    function reset($id, $state) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_po_reset(?, ?, ?)";

        $arr = $this->db->query($sql, array($id, $state, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != $state) {
            return null;
        }

        return $detail;
    }

    function createtender($id, $tendernum, $startdate, $enddate = null) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_tender_createfrompo(?, ?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($id, $tendernum, $startdate, $enddate, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'TENDER') {
            return null;
        }

        return $detail;
    }

    function completetender($id, $tenderid, $vendorid, $quotationvalue) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_tender_complete(?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($tenderid, $vendorid, $quotationvalue, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'TENDER' || $detail['tenderid_status'] != 'COMP') {
            return null;
        }

        return $detail;
    }

    function createcontract($id, $contractnum, $contractdate, $vendorid, $quotationvalue) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_contract_createfrompo(?, ?, ?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($id, $contractnum, $contractdate, $vendorid, $quotationvalue, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'CONTRACT') {
            return null;
        }

        return $detail;

    }

    function createcontractfromtender($id, $tenderid, $contractnum, $contractdate) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_contract_createfromtender(?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($tenderid, $contractnum, $contractdate, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'CONTRACT') {
            return null;
        }

        return $detail;
    }

    function approvecontract($id, $contractid) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_contract_approve(?, ?)";

        $arr = $this->db->query($sql, array($contractid, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'CONTRACT' && $detail['contractid_status'] != 'APPR') {
            return null;
        }

        return $detail;
    }

    function createdofromcontract($id, $contractid, $donum, $dodate, $storeid, $targetdeliverydate) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_do_createfromcontract(?, ?, ?, ?, ?, ?)";

        $arr = $this->db->query($sql, array($contractid, $donum, $dodate, $storeid, $targetdeliverydate, $userid));
        if ($arr == null)    return $arr;
 
        $detail = $this->detail($id);
        if ($detail['status'] != 'DELIVR') {
            return null;
        }

        return $detail;
    }

    function approvedo($id, $doid) {
        $userid = $this->session->userdata('user_id');

        //call sp
        $sql = "call usp_do_approve(?, ?)";

        $arr = $this->db->query($sql, array($doid, $userid));
        if ($arr == null)    return $arr;
 
        $status = $this->_get_do_status($doid);
        if ($status != 'APPR') {
            return null;
        }

        return $this->detail($id);
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();

        if (empty($filter))     $filter = array();

        //default
        $siteid = $this->session->userdata("siteid");
        if (!empty($filter['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($filter['siteid']);
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

        //filter
        $arr = array();

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $arr["a.$key"] = $val;
            }
        }
        $filter = $arr;

        //list delivery orders
        $this->db->select("a.poid, group_concat(e.doid separator ',') as doid, group_concat(concat('{\"doid\":\"',e.doid,'\",\"donum\":\"',e.donum,'\",\"dodate\":\"',e.dodate,'\",\"status\":\"',e.status,'\",\"storeid\":\"',e.storeid,'\",\"store\":\"',f.description,'\"}') separator ',') as dolabel");
        $this->db->from("tcg_po a");
        //$this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "INNER");
        $this->db->join("tcg_do e", "e.poid=a.poid AND e.is_deleted=0", "INNER");
        $this->db->join("tcg_store f", "f.storeid=e.storeid AND f.is_deleted=0", "INNER");
        $this->db->group_by("a.poid");
        
        $this->db->where($filter);
        if (!empty($year)) {
            $this->db->where("a.financial_year", $year);
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

        $subquery1 = $this->db->get_compiled_select();

        $this->db->reset_query();

         //list delivery orders
        $this->db->select("a.poid, group_concat(e.evaluationid separator ',') as evaluationid");
        $this->db->select("group_concat(concat('{\"evaluationid\":\"',e.evaluationid,'\",\"evaluationcode\":\"',e.evaluationcode,
                                                '\",\"evaluationby\":\"',g.nama,'\",\"status\":\"',e.status,'\"}') separator ',') as evaluationlabel");
        $this->db->from("tcg_po a");
        //$this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "INNER");
        $this->db->join("tcg_poevaluation e", "e.poid=a.poid AND e.is_deleted=0", "INNER");
        $this->db->join("dbo_users g", "g.user_id=e.evaluationby AND g.is_deleted=0", "LEFT OUTER");
        $this->db->group_by("a.poid");
        
        $this->db->where($filter);
        if (!empty($year)) {
            $this->db->where("a.financial_year", $year);
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

        $subquery2 = $this->db->get_compiled_select();

        $this->db->reset_query();

        $this->db->select("a.*");
        $this->db->select("b.demandnum as demandid_label, b.description as demandid_desc, b.status as demandid_status");
        $this->db->select("c.tendernum as tenderid_label, c.startdate as tenderid_startdate, c.enddate as tenderid_enddate, c.status as tenderid_status, c.vendorid as tenderid_vendorid, c.quotationvalue");
        $this->db->select("d.contractnum as contractid_label, d.description as contractid_desc, d.status as contractid_status, d.contractdate, d.contractvalue, d.vendorid");
        $this->db->select("e.doid as doid, e.dolabel as doid_label");
        $this->db->select("j.evaluationid, j.evaluationlabel as evaluationid_label");
        $this->db->select("g.name as siteid_label");
        $this->db->select("h.typecode as itemtypeid_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_demand b", "b.demandid=a.demandid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_tender c", "c.tenderid=a.tenderid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("(" .$subquery1. ") e", "e.poid=a.poid", "LEFT OUTER");
        //$this->db->join("tcg_poevaluation f", "f.evaluationid=a.evaluationid AND f.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site g", "g.siteid=a.siteid AND g.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype h", "h.typeid=a.itemtypeid AND h.is_deleted=0", "LEFT OUTER");
        $this->db->join("(" .$subquery2. ") j", "j.poid=a.poid", "LEFT OUTER");

        //filter
        $this->db->where($filter);
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

        $arr = $this->db->get("tcg_po a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;


        //special transformation
        // foreach($this->table_metas['columns'] as $key => $col) {
        //     //TODO
        //     if ($col['type'] == "tcg_multi_select") {
        //         foreach($arr as $idx => $row) {
        //             if (isset( $row[$col['name']] )) {
        //                 $arr[$idx][$col['name']] = explode(',', $row[$col['name']]);
        //             }
        //         }
        //     }
        // }
        
        return $arr;
    }    

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['poid'] = $id;

        $arr = $this->list($filter);
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

    function lookup($filter = null) {
        $this->reset_error();

        if (empty($filter))     $filter = array();

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

        $this->db->select("a.poid as value, a.ponum as label");
        //$this->db->from("tcg_po a");
 
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
        
        $arr = $this->db->get("tcg_po a")->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        $typeid = $this->session->userdata("itemtypeid");
        if (!empty($typeid)) {
            //enforce
            $valuepair['typeid'] = $typeid;
        }

        $id = parent::add($valuepair, $enforce_edit_columns);

        if ($id > 0) {
            //run data consistency
            $pengguna_id = $this->session->userdata("user_id");
            $this->db->query("call usp_po_dataconsistency(?,?)", array($id,$pengguna_id));
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
            $this->db->query("call usp_po_dataconsistency(?,?)", array($id,$pengguna_id));
        }

        return $result;
    }   

    function delete($id, $filter = null) {
        parent::delete($id, $filter);
        //run data consistency
        $pengguna_id = $this->session->userdata("user_id");
        $this->db->query("call usp_po_dataconsistency(?,?)", array($id,$pengguna_id));
    }    
    
    private function _get_do_status($doid) {
        $sql = "select status from tcg_do where doid=? and is_deleted=0";

        $arr = $this->db->query($sql, array($doid))->row_array();
        if ($arr == null)   return null;

        return $arr['status'];
    }
}

  