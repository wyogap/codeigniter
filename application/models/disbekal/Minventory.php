<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Minventory extends Mcrud_tablemeta
{

    public function __construct()
    {
    }

    /**
     * Change invreceive status from DRAFT to COMP. Create correspondingn new entry in inventory
     */
    public function approve_receiving($ids) {
        if (empty($ids)) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invreceive a
        set a.tag=?
        where a.status='DRAFT' and a.is_deleted=0 and a.invreceiveid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_stockin_approvebytag(?,?)";
        $query = $this->db->query($sql, array($tag, $userid));

        return $affected;
    }

    /**
     * Change invusage status from DRAFT to COMP
     */
    public function approve_usage($ids) {
        if (empty($ids)) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invusage a
        set a.tag=?
        where a.status='DRAFT' and a.is_deleted=0 and a.invusageid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_stockout_approvebytag(?,?)";
        $query = $this->db->query($sql, array($tag,$userid));

        return $affected;
    }    

    /**
     * Set invstatus.writeoff = 1, substract the stockcount in inventory.
     */
    public function writeoff($ids) {
        if (empty($ids)) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invstatus a
        set a.tag=?
        where a.writeoff=0 and a.is_deleted=0 and a.invstatusid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_stock_writeoffbytag(?,?)";
        $query = $this->db->query($sql, array($tag, $userid));

        return $affected;
    }

    /**
     * Set stockcheck status fron INPROG to COMP. Update inventory stockcount. Create invstatus if necessary
     */
    public function approve_stockcheck($id) {
        $userid = $this->session->userdata('user_id');

        //execute the change
        $sql = "call usp_stockcheck_complete(?,?)";
        $query = $this->db->query($sql, array($id,$userid));

        return 1;
    }

    /**
     * Generate snapshot list of items for stock check
     */
    public function generate_stockcheck($id) {
        $userid = $this->session->userdata('user_id');

        //execute the change
        $sql = "call usp_stockcheck_snapshot(?,?)";
        $query = $this->db->query($sql, array($id,$userid));

        return 1;
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

        //get list of files
        $subquery1 = "";
        $this->db->select("a.invreceiveid");
        $this->db->select("group_concat(b.filename separator ';') as filename");
        $this->db->select("group_concat(b.web_path separator ';') as web_path");
        $this->db->select("group_concat(b.thumbnail_path separator ';') as thumbnail_path");
        $this->db->from("tcg_invreceive a");
        $this->db->join("dbo_uploads b", "FIND_IN_SET(b.id, a.file1)", "INNER");
        $this->db->join("tcg_store c", "c.storeid=a.storeid and c.is_deleted=0", "INNER");
        $this->db->join("tcg_item d", "d.itemid=a.itemid and d.is_deleted=0", "INNER");
        $this->db->join("tcg_itemcategory e", "e.categoryid=d.categoryid and e.is_deleted=0", "LEFT OUTER");
        $this->db->group_by("a.invreceiveid");
        
        $this->db->where($filter);
        if (!empty($year)) {
            $this->db->where("YEAR(a.receiveddate)", $year);
        }
        if (!empty($itemtypeid)) {
            $this->db->where("e.typeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=c.siteid AND x.is_deleted=0", "INNER");
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

        $this->db->select("a.*");
        $this->db->select("b.`description` as `itemid_label`");
        $this->db->select("c.`name` as `manufacturerid_label`");
        $this->db->select("d.`name` as `vendorid_label`");
        $this->db->select("e.`storecode` as `storeid_label`");
        $this->db->select("f.`locationcode` as `locationid_label`");
        $this->db->select("g.`ponum` as `poid_label`");
        $this->db->select("h.`contractnum` as `contractid_label`");
        $this->db->select("i.`donum` as `doid_label`");
        $this->db->select("j.`linenum` as `doitemid_label`");
        $this->db->select("k.`nama` as `approvedby_label`");
        $this->db->select("a.`status` as `status_label`");
        $this->db->select("m.`filename` as `file1_filename`, m.`web_path` as `file1_path`, m.`thumbnail_path` as `file1_thumbnail`");
        $this->db->select("n.`label` as `unit_label`, o.label as shelflifeunit_label");
        //$this->db->from("tcg_invreceive a");
        $this->db->join("tcg_item b", "a.`itemid`=b.`itemid` AND b.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_manufacturer c", "a.`manufacturerid`=c.`manufacturerid` AND c.`is_deleted`=0 ", "LEFT OUTER");
        $this->db->join("tcg_vendor d", "a.`vendorid`=d.`vendorid` AND d.`is_deleted`=0 ", "LEFT OUTER");
        $this->db->join("tcg_store e", "a.`storeid`=e.`storeid` AND e.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_location f", "a.`locationid`=f.`locationid` AND f.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_po g", "a.`poid`=g.`poid` AND g.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_contract h", "a.`contractid`=h.`contractid` AND h.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_do i", "a.`doid`=i.`doid` AND i.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("tcg_doitem j", "a.`doitemid`=j.`doitemid` AND j.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("dbo_users k", "a.`approvedby`=k.`user_id` AND k.`is_deleted`=0", "LEFT OUTER");
        //$this->db->join("lk_status l", "a.`status`=l.`value` AND l.`is_deleted`=0 AND l.`domain`='stock'", "LEFT OUTER");
        $this->db->join("(" .$subquery1. ") m", "a.invreceiveid=m.invreceiveid", "LEFT OUTER");
        $this->db->join("lk_unit n", "a.`unit`=n.`unit` AND n.`is_deleted`=0", "LEFT OUTER");
        $this->db->join("dbo_lookups o", "a.`shelflifeunit`=o.`value` AND o.`is_deleted`=0 AND o.`group`='date_unit'", "LEFT OUTER");
        $this->db->join("tcg_itemcategory p", "p.categoryid=b.categoryid and p.is_deleted=0", "LEFT OUTER");

        //filter
        $this->db->where($filter);
        if (!empty($itemtypeid)) {
            $this->db->where("p.typeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=f.siteid AND x.is_deleted=0", "INNER");
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

        //instock
        $this->db->where('a.status', 'INSTOCK');
        $this->db->where('a.currentstock>0');
        
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

        $arr = $this->db->get("tcg_inventory a", $limit, $offset)->result_array();
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
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //default
        $siteid = $this->session->userdata("siteid");
        if (!empty($filter['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($filter['siteid']);
        }
        unset($filter['siteid']);

        $typeid = $this->session->userdata("itemtypeid");
        if (empty($typeid)) {
            $typeid = empty($filter['typeid']) ? null : $filter['typeid'];
        }
        unset($filter['typeid']);

        $this->db->select("a.inventoryid as value, concat(b.description, ' (', a.inventorycode, '-', year(a.receiveddate),')') as label");
        $this->db->select("c.categoryid, c.typeid as itemtypeid");
        //$this->db->from("tcg_po a");
        $this->db->join("tcg_item b", "b.itemid=a.itemid AND b.is_deleted=0", "INNER");
        $this->db->join("tcg_itemcategory c", "c.categoryid=b.categoryid AND c.is_deleted=0", "LEFT OUTER");
        //$this->db->join("tcg_itemtype d", "d.typeid=c.typeid AND d.is_deleted=0", "LEFT OUTER");

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
            $this->db->where("c.typeid", $typeid);
        }

        //soft delete
        $this->db->where('a.is_deleted', '0');

        //instock
        $this->db->where('a.status', 'INSTOCK');
        $this->db->where('a.availableamount>0');

        $this->db->order_by("b.description asc, year(a.receiveddate)");

        $arr = $this->db->get("tcg_inventory a")->result_array();
        if ($arr == null)       return $arr;
        
        return $arr;
    }

}

  