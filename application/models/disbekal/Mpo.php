<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mpo extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_po";
    protected static $PRIMARY_KEY = "poid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'ponum';
    protected static $COL_VALUE = 'poid';

    protected static $SOFT_DELETE = true;

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        $this->db->select("a.poid, group_concat(e.doid separator ',') as doid, group_concat(concat('{\"doid\":\"',e.doid,'\",\"donum\":\"',e.donum,'\",\"dodate\":\"',e.dodate,'\",\"status\":\"',e.status,'\"}') separator ',') as dolabel");
        $this->db->from("tcg_po a");
        //$this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "INNER");
        $this->db->join("tcg_do e", "e.contractid=a.contractid AND e.is_deleted=0", "INNER");
        $this->db->group_by("a.poid");
        $this->db->where($filter);
        $subquery = $this->db->get_compiled_select();

        $this->db->reset_query();

        $this->db->select("a.*");
        $this->db->select("b.demandnum as demandid_label, b.description as demandid_desc, b.status as demandid_status");
        $this->db->select("c.tendernum as tenderid_label, c.startdate as tenderid_startdate, c.enddate as tenderid_enddate");
        $this->db->select("d.contractnum as contractid_label, d.description as contractid_desc, d.status as contractid_status, d.contractdate, d.contractvalue, d.vendorid");
        $this->db->select("e.doid as doid, e.dolabel as doid_label");
        $this->db->select("f.evaluationcode as evaluationid_label, f.status as evaluationid_status");
        $this->db->select("g.name as siteid_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_demand b", "b.demandid=a.demandid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_tender c", "c.tenderid=a.tenderid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("(" .$subquery. ") e", "e.poid=a.poid", "LEFT OUTER");
        $this->db->join("tcg_poevaluation f", "f.evaluationid=a.evaluationid AND f.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site g", "g.siteid=a.siteid AND g.is_deleted=0", "LEFT OUTER");
        $this->db->where($filter);

        
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
        
        $this->db->select("a.poid, group_concat(e.doid separator ',') as doid, group_concat(concat('{\"doid\":\"',e.doid,'\",\"donum\":\"',e.donum,'\",\"dodate\":\"',e.dodate,'\",\"status\":\"',e.status,'\"}') separator ',') as dolabel");
        $this->db->from("tcg_po a");
        //$this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "INNER");
        $this->db->join("tcg_do e", "e.contractid=a.contractid AND e.is_deleted=0", "INNER");
        $this->db->group_by("a.poid");
        $this->db->where($filter);
        $subquery = $this->db->get_compiled_select();

        $this->db->reset_query();

        $this->db->select("a.*");
        $this->db->select("b.demandnum as demandid_label, b.description as demandid_desc, b.status as demandid_status");
        $this->db->select("c.tendernum as tenderid_label, c.startdate as tenderid_startdate, c.enddate as tenderid_enddate");
        $this->db->select("d.contractnum as contractid_label, d.description as contractid_desc, d.status as contractid_status, d.contractdate, d.contractvalue, d.vendorid");
        $this->db->select("e.doid as doid, e.dolabel as doid_label");
        $this->db->select("f.evaluationcode as evaluationid_label, f.status as evaluationid_status");
        $this->db->select("g.name as siteid_label");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_demand b", "b.demandid=a.demandid AND b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_tender c", "c.tenderid=a.tenderid AND c.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_contract d", "d.contractid=a.contractid AND d.is_deleted=0", "LEFT OUTER");
        $this->db->join("(" .$subquery. ") e", "e.poid=a.poid", "LEFT OUTER");
        $this->db->join("tcg_poevaluation f", "f.evaluationid=a.evaluationid AND f.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site g", "g.siteid=a.siteid AND g.is_deleted=0", "LEFT OUTER");
        $this->db->where($filter);

        $this->db->where("a.poid", $id);

        $arr = $this->db->get("tcg_po a")->row_array();
        if ($arr == null)       return $arr;

        return $arr;
    }
}

  