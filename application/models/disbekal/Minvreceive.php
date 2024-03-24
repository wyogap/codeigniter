<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Minvreceive extends Mcrud_tablemeta
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
            $this->db->where("d.typeid", $itemtypeid);
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

        $this->db->where($filter);
        if (!empty($year)) {
            if ($year == date("Y")) {
                //for current year, include draft version
                $this->db->group_start();
                $this->db->where("YEAR(a.receiveddate)", $year);
                $this->db->or_where("(a.status='DRAFT')");
                $this->db->group_end();                
            }
            else {
                $this->db->where("YEAR(a.receiveddate)", $year);
            }
        }
        if (!empty($itemtypeid)) {
            $this->db->where("p.typeid", $itemtypeid);
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site x", "x.siteid=e.siteid AND x.is_deleted=0", "INNER");
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
            $str = str_replace("tcg_invreceive", "a", $this->table_metas['where_clause']);
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

        $arr = $this->db->get("tcg_invreceive a", $limit, $offset)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['invreceiveid'] = $id;

        $arr = $this->list($filter);
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;
        if ($enforce_edit_columns && !$this->table_actions['edit'])    return 0;
    
        unset($valuepair['file1_filename']);
        unset($valuepair['file1_path']);
        unset($valuepair['file1_thumbnail']);

       return parent::update($id, $valuepair, $filter, $enforce_edit_columns);
    }

    function delete($id, $filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;

        unset($valuepair['file1_filename']);
        unset($valuepair['file1_path']);
        unset($valuepair['file1_thumbnail']);

        return parent::delete($id, $filter);
    }

    //when importing, automatically create master item if not exist yet
    protected function __update_custom_column($temp_table_name) {

        $safe_name = $this->db->escape($temp_table_name);

        //update the reference
        $sql = "
        update " .$temp_table_name. " a
        left join tcg_item b on upper(b.description)=upper(a.itemid) and b.is_deleted=0
        set
            a.itemid = concat('*', a.itemid, '*')
        where b.itemid is null
        ";
        $this->db->query($sql);

        //create missing master data (ITEM)
        $sql = "
        INSERT INTO tcg_item (itemcode, description)
        SELECT 
            'IT0000' as itemcode, a.itemid as description
        FROM " .$temp_table_name. " a
        left join tcg_item b on upper(b.description)=upper(a.itemid) and b.is_deleted=0
        where b.itemid is null;
        ";
        $this->db->query($sql);

        //update itemcode
        $sql = "update tcg_item set itemcode=concat('IT', lpad(itemid,8,'0')) where itemcode='IT0000'";
        $this->db->query($sql);

        //update the reference
        $sql = "
        update " .$temp_table_name. " a
        left join tcg_vendor b on upper(b.name)=upper(a.vendorid) and b.is_deleted=0
        set
            a.vendorid = concat('*', a.vendorid, '*')
        where b.vendorid is null
        ";
        $this->db->query($sql);

        //create missing master data (VENDOR)
        $sql = "
        INSERT INTO tcg_vendor (vendorcode, name)
        SELECT 
            'VE0000' as vendorcode, a.vendorid as name
        FROM " .$temp_table_name. " a
        left join tcg_vendor b on upper(b.name)=upper(a.vendorid) and b.is_deleted=0
        where b.vendorid is null;
        ";
        $this->db->query($sql);

        //update itemcode
        $sql = "update tcg_vendor set vendorcode=concat('VE', lpad(vendorid,8,'0')) where vendorcode='VE0000'";
        $this->db->query($sql);

        //update the reference
        $sql = "
        update " .$temp_table_name. " a
        left join tcg_manufacturer b on upper(b.name)=upper(a.manufacturerid) and b.is_deleted=0
        set
            a.manufacturerid = concat('*', a.manufacturerid, '*')
        where b.manufacturerid is null
        ";
        $this->db->query($sql);

        //create missing master data (MANUFACTURER)
        $sql = "
        INSERT INTO tcg_manufacturer (manufacturercode, name)
        SELECT 
            'VE0000' as manufacturercode, a.manufacturerid as name
        FROM " .$temp_table_name. " a
        left join tcg_manufacturer b on upper(b.name)=upper(a.manufacturerid) and b.is_deleted=0
        where b.manufacturerid is null;
        ";
        $this->db->query($sql);

        //update itemcode
        $sql = "update tcg_manufacturer set manufacturercode=concat('VE', lpad(manufacturerid,8,'0')) where manufacturercode='VE0000'";
        $this->db->query($sql);
        
        //store ponum/contractnum/donum/dolinenum as-is
        $sql = "
        update " .$temp_table_name. " set ponum=poid, contractnum=contractid, donum=doid, dolinenum=doitemid;
        ";
        $this->db->query($sql);
    }

    protected function __get_custom_column($columns) {
        //set latlong column to be visible for import
        for($i=0; $i<count($columns); $i++) {
            if ($columns[$i]['name'] == 'ponum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'contractnum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'donum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
            else if ($columns[$i]['name'] == 'dolinenum') {
                $columns[$i]['visible'] = 1;
                $columns[$i]['allow_insert'] = 1;
                $columns[$i]['allow_edit'] = 1;
            }
        }

        return $columns;
    }

}

  