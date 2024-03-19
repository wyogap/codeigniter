<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mcontract extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_contract";
    protected static $PRIMARY_KEY = "contractid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'description';
    protected static $COL_VALUE = 'contractid';

    protected static $SOFT_DELETE = true;

    function approve($id) {
        //TODO
        return 1;

    }

    function close($id) {
        //TODO
        return 0;

    }

    function buatperintahterima($id) {
        //TODO
        return 0;

    }

    // function list($filter = null, $limit = null, $offset = null, $orderby = null) {
    //     $this->reset_error();
        
    //     if (!$this->initialized)   return null;

    //     // if (!empty($filter)) {
    //     //     $f_year = (!empty($filter['year'])) ? $this->db->escape($filter['year']) : null;
    //     //     $f_siteid = (!empty($filter['siteid'])) ? $this->db->escape($filter['siteid']) : null;
    //     //     unset($filter['year']);
    //     //     unset($filter['siteid']);
    //     // }

    //     // $sql = "
    //     // SELECT a.* 
    //     //     x.name as siteid_label, d.typecode as itemtypeid_label
    //     // FROM tcg_demand a
    //     // left join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
    //     // left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
    //     // left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
    //     // left join tcg_itemtype d on d.typeid=a.itemtypeid and d.is_deleted=0
    //     // left join (
    //     //     select demandid, year(demanddate) as `year`, count(*) as cnt from tcg_demandinstance a 
    //     //     where a.is_deleted=0"
    //     //     .(!empty($f_year) ? " and year(a.demanddate)=$f_year" : "")
    //     //     ." group by demandid, year(demanddate)
    //     // ) e on e.demandid=a.demandid
    //     // where a.is_deleted=0"
    //     //     .(!empty($f_siteid) ? " and (x.siteid=$f_siteid or y.siteid=$f_siteid or z.siteid=$f_siteid)" : "")
    //     //     .(!empty($f_year) ? " and (e.year=$f_year or ($f_year=year(curdate()) and a.status='DRAFT'))" : "")
    //     //     ;

    //     // //filter
    //     // if (!empty($filter) && is_array($filter)) {
    //     //     //clean up non existing filter columns
    //     //     $ci_name = null;
    //     //     foreach($filter as $key => $val) {
    //     //         $ci_name = strtoupper($key);
    //     //         if (false !== array_search($ci_name, $this->columns)) {
    //     //             $sql .= " and a.$key=" .$this->db->escape($val);
    //     //         }
    //     //     }
    //     // }

    //     $this->db->select("a.demandid, year(a.demanddate) as `year`, count(*) as cnt");
    //     $this->db->from("tcg_demandinstance a");
    //     $this->db->group_by("a.demandid, year(a.demanddate)");
    //     $this->db->where("a.is_deleted=0");
    //     if (!empty($filter) && !empty($filter['year'])) {
    //         $this->db->where("year(a.demanddate)", $filter['year']);
    //     }
    //     $subquery1 = $this->db->get_compiled_select();

    //     $this->db->reset_query();

    //     $this->db->select("a.*");
    //     $this->db->select("x.name as siteid_label, d.typecode as itemtypeid_label, f.label as frequencyunit_label");
    //     //$this->db->from("tcg_po a");
    //     $this->db->join("tcg_itemtype d", "d.typeid=a.itemtypeid and d.is_deleted=0", "LEFT OUTER");
    //     if (!empty($filter['year'])) {
    //         $this->db->join("(" .$subquery1. ") e", "e.demandid=a.demandid", "LEFT OUTER");
    //     }
    //     $this->db->join("dbo_lookups f", "f.value=a.frequencyunit and f.group='date_unit' and f.is_deleted=0", "LEFT OUTER");
    //     $this->db->join("tcg_site x", "x.siteid=a.siteid and x.is_deleted=0", "LEFT OUTER");
    //     $this->db->join("tcg_site y", "y.siteid=x.parentid and y.is_deleted=0", "LEFT OUTER");
    //     $this->db->join("tcg_site z", "z.siteid=y.parentid and z.is_deleted=0", "LEFT OUTER");

    //     //filter
    //     if (!empty($filter) && is_array($filter)) {
    //         if (!empty($filter['year'])) {
    //             $this->db->group_start();
    //             $this->db->where('e.year', $filter['year']);
    //             $this->db->or_where("(a.status='DRAFT' and YEAR(curdate())=" .$this->db->escape($filter['year']) .")");
    //             $this->db->group_end();                
    //             unset($filter['year']);
    //         }
            
    //         if (!empty($filter['siteid'])) {
    //             $this->db->group_start();
    //             $this->db->where('x.siteid', $filter['siteid']);
    //             $this->db->or_where('y.siteid', $filter['siteid']);
    //             $this->db->or_where('z.siteid', $filter['siteid']);
    //             $this->db->group_end();                
    //             unset($filter['siteid']);
    //         }

    //         //clean up non existing filter columns
    //         $ci_name = null;
    //         foreach($filter as $key => $val) {
    //             $ci_name = strtoupper($key);
    //             if (false !== array_search($ci_name, $this->columns)) {
    //                 $this->db->where("a.$key", $val);
    //             }
    //         }
    //     }

    //     //soft delete
    //     $this->db->where('a.is_deleted', '0');

    //     //order by
    //     if (!empty($orderby)) {
    //         if (is_array($orderby)) {
    //             foreach($orderby as $value) {
    //                 $this->db->order_by($value);
    //             }
    //         }
    //         else {
    //             $this->db->order_by($orderby);
    //         }
    //     }

    //     // $query = $this->db->get_compiled_select();
    //     // var_dump($query); exit;

    //     $arr = $this->db->get("tcg_demand a", $limit, $offset)->result_array();
    //     if ($arr == null)       return $arr;
        
    //     return $arr;
    // }    

    // function detail($id, $filter = null) {
    //     $this->reset_error();
        
    //     if ($filter == null)    $filter = array();

    //     //add filter based on key
    //     $filter['demandid'] = $id;
    //     $arr = $this->list($filter);
        
    //     if ($arr == null || count($arr) == 0)       return $arr;

    //     return $arr[0];
    // }

}

  