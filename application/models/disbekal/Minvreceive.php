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
        $this->db->select("b.`description` as `itemid_label`, b.categoryid, b.typeid as itemtypeid, b.fastmoving");
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
        $this->db->select("p.`description` as `categoryid_label`, q.typecode as itemtypeid_label");
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
        $this->db->join("tcg_itemtype q", "q.typeid=b.typeid and q.is_deleted=0", "LEFT OUTER");

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
            $this->db->where("b.typeid", $itemtypeid);
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

        return parent::delete($id, $filter);
    }

    function import($file) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;

        if (!$this->table_actions['import'])    return 0;

        // $this->error['message'] = "";
        $userid = $this->session->userdata('user_id');
        
        $table_id = $this->table_id;
        $table_name = $this->table_name;
        $columns = $this->table_metas['columns'];
        $key_col_name = $this->table_metas['key_column'];
        $join_tables = $this->table_metas['join_tables'];

        //custom columns
        $columns = $this->__get_custom_column($columns);

        //upload file
        $data = $this->__upload_xls($file, $table_id, $table_name);
        if ($data == null) {
            return 0;
        }
        $import_id = $data['import_id'];
        $filepath = $data['filepath'];

        //create temporary table
        $data = $this->__create_temp_table($table_name, $columns);
        if ($data == null) {
            $sql = "update dbo_imports set status='" .$this->error_message. "' where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }
        $temp_table_name = $data['temp_table_name'];
        $export_columns = $data['export'];
        $import_columns = $data['import'];
        $column_types = $data['type'];
        $upload_columns = $data['upload'];

        //make sure reference col is in export col
        //in case when col is set display=0 but edit=1, then it will not be in list of export col but will be in reference col (for editing purpose)
        $arr = array();
        foreach($join_tables as $idx => $tbl) {
            $col_name = $tbl['name'];
            if (in_array($col_name, $import_columns)) {
                $arr[] = $tbl;
            }
        }
        $join_tables = $arr;

        //import xls
        $status = $this->__import_xls($filepath, $import_id, $temp_table_name, $export_columns, $column_types);
        if($status == 0) {
            $sql = "update dbo_imports set status='" .$this->error_message. "', processing_finish_on=now() where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }

        //since it could be huge file, continue processing in the bg (use size as parameter?)
        //update status
        $sql = "update dbo_imports set status='partial' where id=?";
        $this->db->query($sql, array($import_id));

        //audit trail
        audittrail_trail($table_name, $import_id, "IMPORT", "Import from file " .$filepath);

        //create event to do bg processing
        $sql = "create event import_invreceive_$import_id on schedule at current_timestamp + interval 30 second "
                ."do call usp_job_import_invreceive($import_id, $userid)";
        $this->db->query($sql);

        return 2;

        // //custom processing
        // $this->__update_custom_column($temp_table_name);

        // //intermediate process
        // if (count($upload_columns)) {
        //     $this->__update_upload_columns($temp_table_name, $upload_columns);
        // }

        // //process import
        // $this->__process_import($table_name, $key_col_name, $temp_table_name, $import_columns, $join_tables);

        // //drop temporary table
        // $this->db->query("DROP TEMPORARY TABLE $temp_table_name;");

        // //update status
        // $sql = "update dbo_imports set status='completed', processing_finish_on=now() where id=?";
        // $this->db->query($sql, array($import_id));

        // //audit trail
        // audittrail_trail($table_name, $import_id, "IMPORT", "Import from file " .$filepath);

        // return 1;
    }

    protected function __create_temp_table($table_name, $columns) {
        $this->reset_error();

        //Note: Ideally, all editable columns (regardless visible or not) should be exported and can be imported.
        //      But since, we are using internal datatable export function, we can only export visible columns.

        $temp_table_name = "tmp_invreceive";

        $column_def = array();
        $column_names = array();

        $import_columns = array();
        $upload_columns = array();
        $export_columns = array();
        $column_type = array();
        
        //id column
        $column_def[] = "__id__ int(11) NOT NULL AUTO_INCREMENT";

        //import id
        $column_def[] = "__import_id__ int(11) NOT NULL";

        foreach($columns as $key => $col) {
            if ($col['visible'] == 0 && $col['export'] == 0)    continue;

            //prevent double columm
            if (in_array($col['name'],$column_names))   continue;
            $column_names[] = $col['name'];

            //column definition
            if($col['type'] == 'tcg_text') {
                $column_def[] = $col['name'] ." varchar(250)";
            }
            else if($col['type'] == 'tcg_textarea') {
                $column_def[] = $col['name'] ." longtext";
            }
            else if($col['type'] == 'tcg_number') {
                $column_def[] = $col['name'] ." int";
            }
            else if($col['type'] == 'tcg_date') {
                $column_def[] = $col['name'] ." date";
            }
            else if($col['type'] == 'tcg_datetime') {
                $column_def[] = $col['name'] ." datetime";
            }
            else if($col['type'] == 'tcg_select') {
                $column_def[] = $col['name'] ." varchar(100)";
            }
            else if($col['type'] == 'tcg_select2') {
                $column_def[] = $col['name'] ." varchar(100)";
            }
            else if($col['type'] == 'tcg_currency') {
                $column_def[] = $col['name'] ." varchar(50)";
            }
            else if($col['type'] == 'tcg_toggle') {
                $column_def[] = $col['name'] ." smallint";
            }
            else if($col['type'] == 'tcg_upload') {
                $column_def[] = $col['name'] ." varchar(50)";
            }
            else {
                $column_def[] = $col['name'] ." varchar(250)";
            }
            //exported columns
            if ($col['export']) {
                $export_columns[] = $col['name'];
                $column_type[] = $col['type'];
            }
            //imported columns
            if ($col['allow_insert'] && $col['allow_edit']) {
                $import_columns[] = $col['name'];
            }
        }

        if (count($import_columns) == 0) {
            $this->set_error(-1, "Tidak ada kolom untuk diimpor");
            return null;
        }

        //always drop first
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS $temp_table_name;");

        //add status columns
        $column_def[] = "_update_ varchar(100) default 0";
        $column_def[] = "_tag2_ varchar(100) default null";
        $column_def[] = "_tag3_ varchar(100) default null";

        //enforce level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $column_def[] = "$key varchar(100) default $val";
            }
        }

        //create the table => use pre-created table
        // $sql = "create temporary table " .$temp_table_name. "(" .implode(',', $column_def). ", PRIMARY KEY (`__id__`))";
        // $query = $this->db->query($sql);
        // if (!$query) {
        //     $this->set_error(-1, "Gagal membuat temporary table");
        //     return null;
        // }

        $retval = array(
            'temp_table_name'   => $temp_table_name,
            'export'    => $export_columns,
            'type'      => $column_type,
            'import'    => $import_columns,
            'upload'    => $upload_columns
        );
        return $retval;
    }

    //when importing, automatically create master item if not exist yet
    protected function __update_custom_column($temp_table_name) {

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
        INSERT INTO tcg_item (itemcode, description, categoryid, typeid, orderunit, issueunit, fastmoving)
        SELECT 
            'IT0000' as itemcode, a.itemid as description, c.categoryid, d.typeid, e.unitid, e.unitid
            , case when a.fastmoving in ('Ya', 'Y', 'Yes') then 1 else 0 end as fastmoving
        FROM " .$temp_table_name. " a
        left join tcg_item b on upper(b.description)=upper(a.itemid) and b.is_deleted=0
        left join tcg_itemcategory c on upper(c.description)=upper(a.categoryid) and c.is_deleted=0
        left join tcg_itemtype d on upper(d.typecode)=upper(a.itemtypeid) and d.is_deleted=0
        left join lk_unit e on upper(e.unit)=upper(a.unit) and e.is_deleted=0
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
        where b.vendorid is null and trim(coalesce(a.vendorid, ''))!=''
        ";
        $this->db->query($sql);

        //create missing master data (VENDOR)
        $sql = "
        INSERT INTO tcg_vendor (vendorcode, name)
        SELECT 
            'VE0000' as vendorcode, a.vendorid as name
        FROM " .$temp_table_name. " a
        left join tcg_vendor b on upper(b.name)=upper(a.vendorid) and b.is_deleted=0
        where b.vendorid is null and trim(coalesce(a.vendorid, ''))!='';
        ";
        $this->db->query($sql);

        //update vendorcode
        $sql = "update tcg_vendor set vendorcode=concat('VE', lpad(vendorid,8,'0')) where vendorcode='VE0000'";
        $this->db->query($sql);

        //update the reference
        $sql = "
        update " .$temp_table_name. " a
        left join tcg_manufacturer b on upper(b.name)=upper(a.manufacturerid) and b.is_deleted=0
        set
            a.manufacturerid = concat('*', a.manufacturerid, '*')
        where b.manufacturerid is null and trim(coalesce(a.manufacturerid, ''))!=''
        ";
        $this->db->query($sql);

        //create missing master data (MANUFACTURER)
        $sql = "
        INSERT INTO tcg_manufacturer (manufacturercode, name)
        SELECT 
            'MN0000' as manufacturercode, a.manufacturerid as name
        FROM " .$temp_table_name. " a
        left join tcg_manufacturer b on upper(b.name)=upper(a.manufacturerid) and b.is_deleted=0
        where b.manufacturerid is null and trim(coalesce(a.manufacturerid, ''))!='';
        ";
        $this->db->query($sql);

        //update manufacturercode
        $sql = "update tcg_manufacturer set manufacturercode=concat('MN', lpad(manufacturerid,8,'0')) where manufacturercode='MN0000'";
        $this->db->query($sql);
        
        //store ponum/contractnum/donum/dolinenum as-is
        $sql = "
        update " .$temp_table_name. " set ponum=poid, contractnum=contractid, donum=doid, dolinenum=doitemid, 
            receivedamount=cast(acceptedamount as decimal)+cast(rejectedamount as decimal);
        ";
        $this->db->query($sql);

        //default value
        $sql = "
        update " .$temp_table_name. " set `stockintype`='RECEIVE', `approveddate`=null, `approvedby`=null, `status`='DRAFT'
        where _update_!=1";
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

        $valuepair['status'] = 'DRAFT';
        if (empty($valuepair['stockintype'])) {
            $valuepair['stockintype'] = 'RECEIVE';
        }
        
        $id = parent::add($valuepair, $enforce_edit_columns);

        return $id;
    }         
}

  