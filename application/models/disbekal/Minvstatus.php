<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Minvstatus extends Mcrud_tablemeta
{
    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        // $offset = 0;
        // if (empty($limit) && $this->table_metas['search_max_result'] > 0) {
        //     $limit = $this->table_metas['search_max_result'];
        // }

        //check whether $filter['siteid'] or $this->session->userdata("siteid") is lower in the hierarchy
        $siteid = $this->session->userdata("siteid");
        if (!empty($filter['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($filter['siteid']);
        }
        unset($filter['siteid']);

        //default
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

        $this->db->select("a.*");
        $this->db->select("p.inventorycode as inventoryid_label, p.itemid, p.manufacturerid, p.vendorid, p.storeid, p.locationid, p.unit");
        $this->db->select("b.`description` as `itemid_label`, b.categoryid, b.typeid as itemtypeid, b.fastmoving");
        $this->db->select("c.`name` as `manufacturerid_label`");
        $this->db->select("e.`storecode` as `storeid_label`");
        $this->db->select("f.`locationcode` as `locationid_label`");
        $this->db->select("i.`nama` as `statusupdateby_label`, k.`nama` as `writeoffby_label`");
        $this->db->select("l.`label` as `status_label`");
        $this->db->select("g.`description` as `categoryid_label`, h.typecode as itemtypeid_label");
        $this->db->select("n.`label` as `unit_label`");
        //$this->db->from("tcg_invusage a");
        $this->db->join("tcg_inventory p", "p.inventoryid=a.inventoryid AND p.`is_deleted`=0", "INNER");
        $this->db->join("tcg_item b", "p.`itemid`=b.`itemid` AND b.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_manufacturer c", "p.`manufacturerid`=c.`manufacturerid` AND c.`is_deleted`=0 ", "LEFT OUTER");
        $this->db->join("tcg_store e", "p.`storeid`=e.`storeid` AND e.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_location f", "p.`locationid`=f.`locationid` AND f.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory g", "b.`categoryid`=g.`categoryid` AND g.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_itemtype h", "b.`typeid`=h.`typeid` AND h.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("dbo_users i", "a.`statusupdateby`=i.`user_id` AND i.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("dbo_users k", "a.`writeoffby`=k.`user_id` AND k.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("lk_status l", "a.`status`=l.`value` AND l.`is_deleted`=0 AND l.`domain`='stock_status'", "LEFT OUTER");
        $this->db->join("lk_unit n", "p.`unit`=n.`unit` AND n.`is_deleted`=0", "LEFT OUTER");

        $this->db->where($filter);
        if (!empty($year)) {
            if ($year == date("Y")) {
                //for current year, include draft version
                $this->db->group_start();
                $this->db->where("YEAR(a.writeoffdate)", $year);
                $this->db->or_where("(a.writeoff=0)");
                $this->db->group_end();                
            }
            else {
                $this->db->where("YEAR(a.writeoffdate)", $year);
            }
        }
        if (!empty($itemtypeid)) {
            $this->db->where("b.typeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=a.forsiteid AND x.is_deleted=0", "INNER");
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
        
        if (!empty($this->table_metas['where_clause']))  { 
            $str = str_replace("tcg_invstatus", "a", $this->table_metas['where_clause']);
            $this->db->group_start();
            $this->db->where($str);
            $this->db->group_end();
        }

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

        $arr = $this->db->get("tcg_invstatus a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['invstatusid'] = $id;

        $arr = $this->list($filter);
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

}

  