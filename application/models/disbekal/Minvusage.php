<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Minvusage extends Mcrud_tablemeta
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
        $this->db->select("b.`description` as `itemid_label`");
        $this->db->select("c.`name` as `manufacturerid_label`");
        $this->db->select("e.`storecode` as `storeid_label`");
        $this->db->select("f.`locationcode` as `locationid_label`");
        $this->db->select("g.`name` as `forsiteid_label`");
        $this->db->select("i.`usagerequestnum` as `urid_label`");
        $this->db->select("j.`linenum` as `usagerequestitemid_label`");
        $this->db->select("k.`nama` as `approvedby_label`");
        $this->db->select("a.`status` as `status_label`");
        $this->db->select("n.`label` as `unit_label`");
        //$this->db->from("tcg_invusage a");
        $this->db->join("tcg_inventory p", "p.inventoryid=a.inventoryid AND p.`is_deleted`=0", "INNER");
        $this->db->join("tcg_item b", "p.`itemid`=b.`itemid` AND b.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_manufacturer c", "p.`manufacturerid`=c.`manufacturerid` AND c.`is_deleted`=0 ", "LEFT OUTER");
        $this->db->join("tcg_store e", "p.`storeid`=e.`storeid` AND e.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_location f", "p.`locationid`=f.`locationid` AND f.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_site g", "a.`forsiteid`=g.`siteid` AND g.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_itemcategory h", "b.`categoryid`=h.`categoryid` AND h.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_usagerequest i", "a.`urid`=i.`usagerequestid` AND i.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_usagerequestitem j", "a.`uritemid`=j.`usagerequestitemid` AND j.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("dbo_users k", "a.`approvedby`=k.`user_id` AND k.`is_deleted`=0", "LEFT OUTER");
        //$this->db->join("lk_status l", "a.`status`=l.`value` AND l.`is_deleted`=0 AND l.`domain`='stock'", "LEFT OUTER");
        $this->db->join("lk_unit n", "p.`unit`=n.`unit` AND n.`is_deleted`=0", "LEFT OUTER");

        $this->db->where($filter);
        if (!empty($year)) {
            if ($year == date("Y")) {
                //for current year, include draft version
                $this->db->group_start();
                $this->db->where("YEAR(a.checkoutdate)", $year);
                $this->db->or_where("(a.status='DRAFT')");
                $this->db->group_end();                
            }
            else {
                $this->db->where("YEAR(a.checkoutdate)", $year);
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
            $str = str_replace("tcg_invusage", "a", $this->table_metas['where_clause']);
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

        $arr = $this->db->get("tcg_invusage a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['invusageid'] = $id;

        $arr = $this->list($filter);
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

    //when importing, automatically create master item if not exist yet
    protected function __update_custom_column($temp_table_name) {

        //store usagerequestnum/usagerequestlinenum as-is
        $sql = "
        update " .$temp_table_name. " set urnum=urid, urlinenum=uritemid;
        ";
        $this->db->query($sql);
    }

    protected function __get_custom_column($columns) {
        //set latlong column to be visible for import
        for($i=0; $i<count($columns); $i++) {
            if ($columns[$i]['name'] == 'urnum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'urlinenum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'trnum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'trlinenum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
        }

        return $columns;
    }

    function add($valuepair, $enforce_edit_columns = true) {

        // $typeid = $this->session->userdata("itemtypeid");
        // if (!empty($typeid)) {
        //     //enforce
        //     $valuepair['itemtypeid'] = $typeid;
        // }

        // $siteid = $this->session->userdata("siteid");
        // if (!empty($valuepair['siteid'])) {
        //     $this->load->model('disbekal/Msite');
        //     $siteid = $this->Msite->check_siteid($valuepair['siteid']);
        // }
        // if (!empty($siteid)) {
        //     //enforce
        //     $valuepair['siteid'] = $siteid;
        // }

        $valuepair['status'] = 'DRAFT';
        if (empty($valuepair['stockouttype'])) {
            $valuepair['stockouttype'] = 'USAGE';
        }
        
        //default
        unset($valuepair['inventoryid_label']);
        unset($valuepair['itemid_label']);
        unset($valuepair['unit']);

        $id = parent::add($valuepair, $enforce_edit_columns);

        // if ($id > 0) {
        //     //run data consistency
        //     $pengguna_id = $this->session->userdata("user_id");
        //     $this->db->query("call usp_usage_dataconsistency(?,?)", array($id,$pengguna_id));
        // }

        return $id;
    }       
}

  