<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mtable extends CI_Model
{
    protected static $DEF_PAGE_SIZE = 25;
    protected static $REGEX_USERDATA = '/{{(\w+)}}/';

    public const INVALID_VALUE = "null";

    protected $table_id = 0;
    protected $table_name = '';
    protected $data_model = null;
    protected $initialized = false;
    
    protected $table_metas = null;
    protected $column_metas = null;
    protected $editor_metas = null;
    protected $filter_metas = null;

    protected $join_tables = null;

    protected $table_actions = null;
    protected $row_actions = null;
    protected $custom_actions = null;

    protected $columns = null;
    protected $edit_columns = null;
    protected $select_columns = null;
    protected $filter_columns = null;
    protected $search_columns = null;

    protected $lookup_columns = null;

    public $error = array();

    public static $TABLE = array (
        'table_id' => 'tdata',
        'ajax' => "",
        'key_column' => "",
        'lookup_column' => "",
        'initial_load' => false,
        'editor' => false,
        'filter' => false,
        'columns' => [],
        'editor_columns' => [],
        'filter_columns' => [],
        'table_actions' => [],
        'custom_actions' => [],
        'row_actions' => [],
        'row_id_column' => true,
        'row_select_column' => false,
        'table_name' => '',
        'editable_table_name' => '',
        'soft_delete' => true,
        'join_tables' => [],
    );
    
    public static $COLUMN = array (
        'name' => '',
        'label' => '',
        'visible' => true,
        'data_priority' => 100,
        'css' => '',
        'type' => 'text',
        'options' => array(),
        'column_name' => '',
        'allow_insert' => true,
        'allow_edit' => true,
        'allow_filter' => false,
        'width' => '',
        'foreign_key' => false
    );
    
    public static $EDITOR = array (
        'name' => '',
        'allow_insert' => true,
        'allow_edit' => true,
        'edit_field' => null,
        'edit_label' => '',
        'edit_type' => 'text',
        'edit_css'  => '',
        'edit_compulsory' => false,
        'edit_options' => array(),
        'edit_info' => null,
        'edit_attr' => array(),
        'edit_onchange_js' => '',
        'edit_bubble' => false,
        'edit_def_value' => null,
    );
    
    public static $FILTER = array (
        'name' => '',
        'allow_filter' => false,
        'filter_label' => '',
        'filter_type' => 'text',
        'filter_css' => '',
        'filter_options' => array(),
        'filter_onchange_js' => '',
        'filter_attr' => array(),
        'filter_invalid_value'  => false
    );
    
    public static $TABLE_ACTION = array (
        'add' => true,
        'add_custom_js' => '',
        'edit' => true,
        'edit_row_action' => true,
        'edit_custom_js' => '',
        'delete' => true,
        'delete_row_action' => true,
        'delete_custom_js' => '',
        'export' => false,
        'export_custom_js' => '',
        'import' => false,
        'import_custom_js' => '',
    );

    public static $CUSTOM_ACTION = array (
        'label' => '',
        'icon' => '',
        'icon_only' => false,
        'css' => '',
        'onclick_js' => '',
    );
    
    public static $ROW_ACTION = array (
        'label' => '',
        'icon' => '',
        'icon_only' => false,
        'css' => '',
        'onclick_js' => '',
        'conditional_js' => '',
    );

    public static $TABLE_JOIN = array (
        'name' => '',
        'column_name' => "",
        'reference_table_name' => '',
        'reference_alias' => '',
        'reference_key_column' => "",
        'reference_lookup_column' => "",
        'reference_soft_delete' => false,
        'reference_where_clause' => "",
    );

    public static $XLSX_FILE_TYPE = "Xlsx";
    public static $XLS_FILE_TYPE = "Xls";

    function __construct() {
        //TODO
    }

    function init($name_or_id, $is_table_id = false) {
        $this->initialized = false;
        
        //table metas
        $filter = null;
        if ($is_table_id) {
            $filter = array('id'=>$name_or_id, 'is_deleted'=>0);
        }
        else {
            $filter = array('name'=>$name_or_id, 'is_deleted'=>0);
        }

        $this->db->select('*');
        $arr = $this->db->get_where('dbo_crud_tables', $filter)->row_array();
        if ($arr == null) {
            return false;
        }

        //table name
        $this->table_id = $arr['id'];
        $this->name = $arr['name'];
        $this->table_name = $arr['table_name'];

        $this->data_model = null;

        //data model
        if (!empty($arr['data_model']))     $this->data_model = $arr['data_model'];
        else                                $this->data_model = null;

        if ($this->data_model != null) {
             try {
                $this->data_model = $this->get_model($this->data_model);
            }
            catch (exception $e) {
                 $this->data_model = null;
            }
        }

        //table info
        $this->table_metas = static::$TABLE;
        $this->table_metas['name'] = $this->name;
        $this->table_metas['id'] = $arr['id'];

        $this->table_metas['ajax'] = base_url('crud/'.$this->table_name);
        $this->table_metas['table_id'] = 'tdata_'.$arr['id'];
        $this->table_metas['key_column'] = $arr['key_column'];
        $this->table_metas['initial_load'] = ($arr['initial_load'] == 1);
        $this->table_metas['row_id_column'] = ($arr['row_id_column'] == 1);
        $this->table_metas['row_select_column'] = ($arr['row_select_column'] == 1);

        $this->table_metas['lookup_column'] = $arr['lookup_column'];
        if (empty($this->table_metas['lookup_column'])) {
            $this->table_metas['lookup_column'] = $this->table_metas['key_column'];
        }

        $this->table_metas['table_name'] = $arr['table_name'];
        $this->table_metas['editable_table_name'] = $arr['editable_table_name'];
        if (empty($this->table_metas['editable_table_name'])) {
            $this->table_metas['editable_table_name'] = $this->table_metas['table_name'];
        }

        $this->table_metas['where_clause'] = "";
        if (!empty($arr['where_clause'])) {
            $this->table_metas['where_clause'] = replace_userdata($arr['where_clause']);
        }

        $this->table_metas['orderby_clause'] = $arr['orderby_clause'];
        $this->table_metas['limit_selection'] = $arr['limit_selection'];
        $this->table_metas['soft_delete'] = ($arr['soft_delete'] == 1);

        $this->table_metas['page_size'] = $arr['page_size'];
        if (empty($this->table_metas['page_size'])) {
            $this->table_metas['page_size'] = static::$DEF_PAGE_SIZE;
        }

        $this->table_metas['data_model'] = $arr['data_model'];
        
        // $this->table_metas['page_name'] = $arr['page_name'];
        // if (empty($this->table_metas['page_name'])) {
        //     $this->table_metas['page_name'] = $this->table_metas['name'];
        // }

        // $this->table_metas['page_title'] = $arr['page_title'];
        // if (empty($this->table_metas['page_title'])) {
        //     $this->table_metas['page_title'] = ucwords( str_replace('.', ' ', $arr['page_name']) );
        // }

        // $this->table_metas['page_icon'] = $arr['page_icon'];
        // $this->table_metas['page_type'] = $arr['page_type'];
        // $this->table_metas['page_key'] = $arr['page_key'];
        // $this->table_metas['header_view'] = $arr['header_view'];
        // $this->table_metas['footer_view'] = $arr['footer_view'];
        // $this->table_metas['custom_view'] = $arr['custom_view'];

        //table actions
        $this->table_actions = static::$TABLE_ACTION;
        $this->table_actions['add'] = ($arr['allow_add'] == 1);
        $this->table_actions['edit'] = ($arr['allow_edit'] == 1);
        $this->table_actions['delete'] = ($arr['allow_delete'] == 1);
        $this->table_actions['export'] = ($arr['allow_export'] == 1);
        $this->table_actions['import'] = ($arr['allow_import'] == 1);
        $this->table_actions['add_custom_js'] = $arr['add_custom_js'];
        $this->table_actions['edit_custom_js'] = $arr['edit_custom_js'];
        $this->table_actions['delete_custom_js'] = $arr['delete_custom_js'];
        $this->table_actions['export_custom_js'] = $arr['export_custom_js'];
        $this->table_actions['import_custom_js'] = $arr['import_custom_js'];

        $this->table_actions['edit_row_action'] = ($arr['edit_row_action'] == 1);
        $this->table_actions['delete_row_action'] = ($arr['delete_row_action'] == 1);

        $this->column_metas = array();
        $this->editor_metas = array();
        $this->filter_metas = array();
        $this->join_tables = array();
        $this->row_actions = array();
        $this->custom_actions = array();
        $this->join_tables = array();

        $this->columns = array();
        $this->edit_columns = array();
        $this->select_columns = array();
        $this->filter_columns = array();
        $this->search_columns = array();
        
        //inline edit row
        if ($this->table_actions['edit_row_action']) {
            $row_action = static::$ROW_ACTION;
            $row_action['label'] = "Edit";
            $row_action['icon'] = "fa fa-edit fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-info";
            $row_action['onclick_js'] = "dt_tdata_".$this->table_id."_edit_row";
            $this->row_actions[] = $row_action;
        }

        //inline delete row
        if ($this->table_actions['delete_row_action']) {
            $row_action = static::$ROW_ACTION;
            $row_action['label'] = "Delete";
            $row_action['icon'] = "fa fa-trash fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-danger";
            $row_action['onclick_js'] = "dt_tdata_".$this->table_id."_delete_row";
            $this->row_actions[] = $row_action;
        }

        //lookup alias
        $lookup_idx = 1;

        //columns metas
        do {
            $this->db->select('*');
            $this->db->order_by('order_no asc');
            $arr = $this->db->get_where('dbo_crud_columns', array('table_id'=>$this->table_id, 'is_deleted'=>0))->result_array();
            if ($arr == null) {
                break;
            }
    
            foreach($arr as $row) {
                $col = static::$COLUMN;
                $col['name'] = $row['name'];
                $col['label'] = __($row['label']);
                $col['visible'] = ($row['visible'] == 1);
                $col['css'] = $row['css'];
                $col['type'] = $row['column_type'];
                $col['data_priority'] = $row['data_priority'];
                $col['column_name'] = $row['column_name'];
                if (empty($col['column_name'])) {
                    $col['column_name'] = $this->table_name. "." .$col['name'];
                }

                //if already exist, ignore. prevent duplicate
                if (true === array_search($col['column_name'], $this->select_columns)) {
                   continue;
                }

                $col['foreign_key'] = ($row['foreign_key'] == 1);;
                $col['allow_insert'] = ($row['allow_insert'] == 1);
                $col['allow_edit'] = ($row['allow_edit'] == 1);
                $col['allow_filter'] = ($row['allow_filter'] == 1);

                if (!empty($row['options_array'])) {
                    $col['options'] = json_decode($row['options_array']);
                }
                else if (!empty($row['options_data_model'])) {
                    $model = null;
                    try {
                        $model = $this->get_model($row['options_data_model']);
                    }
                    catch (exception $e) {
                        //ignore
                    }

                    if ($model != null) {
                        $col['options'] = $model->lookup();
                    }
                }
                
                $col['edit_field'] = $row['edit_field'];
                if (empty($col['edit_field'])) {
                    $col['edit_field'] = $col['name'];
                }

                if ($col['type'] == "tcg_upload") {
                    //link as foreign key
                    $col['foreign_key'] = true;
                    $row['reference_table_name'] = "dbo_uploads";
                    $row['reference_key_column'] = "id";
                    $row['reference_lookup_column'] = "filename";
                    $row['reference_soft_delete'] = 1;
                }

                //search column
                $col['allow_search'] = (isset($row['allow_search']) && $row['allow_search'] == 1);
                if ($col['allow_search']) {
                    $this->search_columns[] = $this->table_name. "." .$col['name'];
                }

                if ($col['foreign_key']) {
                    $ref = static::$TABLE_JOIN;
                    $ref['name'] = $col['name'];
                    $ref['column_name'] = $col['column_name'];
                    $ref['reference_table_name'] = $row['reference_table_name'];
                    $ref['reference_key_column'] = $row['reference_key_column'];
                    $ref['reference_lookup_column'] = $row['reference_lookup_column'];
                    if (empty($ref['reference_lookup_column'])) {
                        $ref['reference_lookup_column'] = $ref['reference_key_column'];
                    }
                    $ref['reference_soft_delete'] = ($row['reference_soft_delete'] == 1);
                    $ref['reference_where_clause'] = $row['reference_where_clause'];

                    //use alias in case multiple reference of the same table (ie. lookup table)
                    $ref['reference_alias'] = 'lookup_' .$lookup_idx++;

                    if ($col['type'] == 'tcg_select2') {
                        $this->join_tables[ $col['name'] ] = $ref;

                        //add into select
                        $col['label_column'] = $ref['reference_alias'].".".$ref['reference_lookup_column'];
                        $col['label_type'] = "join";

                        $this->select_columns[] = $ref['reference_alias'].".".$ref['reference_lookup_column']." as ".$ref['name']."_label";
                    }
                    else if ($col['type'] == 'tcg_multi_select') {
                        $subquery = "select group_concat(" .$ref['reference_lookup_column']. " separator ', ') from " .$ref['reference_table_name']. " where find_in_set(" .$ref['reference_key_column']. ", " .$ref['column_name']. ") > 0";

                        if ($ref['reference_soft_delete']) {
                            $subquery .= " AND is_deleted=0";
                        }

                        //add into select
                        $col['label_column'] = "(" .$subquery. ")";
                        $col['label_type'] = "subquery";

                        $this->select_columns[] = "(" .$subquery. ") as ".$ref['name']."_label";
                    }
                    else if ($col['type'] == 'tcg_upload') {
                        $subquery = "select group_concat(x.filename, ':', x.path separator ', ') from dbo_uploads x where find_in_set(x.id, " .$ref['column_name']. ") > 0 and x.is_deleted=0";

                        //add into select
                        $col['label_column'] = "(" .$subquery. ")";
                        $col['label_type'] = "subquery";

                        $this->select_columns[] = "(" .$subquery. ") as ".$ref['name']."_label";
                    }

                    //get lookup if not specified manually
                    if (empty($col['options']) && ($row['edit_type'] == 'tcg_select2' || $row['filter_type'] == 'tcg_select2')) {
                        $col['options'] = $this->get_lookup_options($ref['reference_table_name'], $ref['reference_key_column'], $ref['reference_lookup_column'], $ref['reference_soft_delete'], $ref['reference_where_clause']);
                    }

                    //search column -> search lookup
                    if (!empty($col['allow_search']) && $row['edit_type'] == 'tcg_select2') {
                        $this->search_columns[] = $ref['reference_alias'].".".$ref['reference_lookup_column'];
                    }
    
                    // //force select2
                    // if (!empty($col['options'])) {
                    //     $col['type'] = 'tcg_select2';
                    // }
                }

                if ($col['allow_edit']) {
                    $editor = static::$EDITOR;
                    $editor['name'] = $row['name'];
                    $editor['allow_insert'] = $col['allow_insert'];
                    $editor['allow_edit'] = $col['allow_edit'];
                    $editor['edit_field'] = $col['edit_field'];

                    $editor['edit_label'] = __($row['edit_label']);
                    if (empty($editor['edit_label'])) {
                        $editor['edit_label'] = ucwords($col['label']);
                    }
                                   
                    $editor['edit_css'] = $row['edit_css'];
                    $editor['edit_compulsory'] = ($row['edit_compulsory'] == 1);
                    $editor['edit_info'] = $row['edit_info'];
                    $editor['edit_onchange_js'] = $row['edit_onchange_js'];
                    $editor['edit_bubble'] = ($row['edit_bubble'] == 1);

                    $editor['edit_def_value'] = $row['edit_def_value'];

                    if (!empty($editor['edit_def_value'])) {
                        $editor['edit_def_value'] = replace_userdata(trim($editor['edit_def_value']));
                    }

                    if (!empty($row['edit_options_array'])) {
                        $editor['edit_options'] = json_decode($row['edit_options_array']);
                    } else if (!empty($col['options'])) {
                        $editor['edit_options'] = $col['options'];
                    }

                    if (!empty($row['edit_attr_array'])) {
                        //echo ($row['edit_attr_array']);
                        //$row['edit_attr_array'] = '{"a": true,"b":2,"c":3,"d":4,"e":5}';
                        //echo ($row['edit_attr_array']);
                        $editor['edit_attr'] = json_decode($row['edit_attr_array']);
                        //TODO: append base url for ajax parameter
                    }

                    //force select2
                    $editor['edit_type'] = $row['edit_type'];
                    if (!empty($editor['edit_options']) && empty($editor['edit_type'])) {
                        $editor['edit_type'] = 'tcg_select2';
                    }
                    if (empty($editor['edit_type'])) {
                        $editor['edit_type'] = $col['type'];
                    }

                    //var_dump($editor);

                    $this->editor_metas[] = $editor;
                    $this->edit_columns[] = $col['name'];
                }

                if ($col['allow_filter']) {
                    $filter = static::$FILTER;
                    $filter['name'] = $row['name'];
                    $filter['allow_filter'] = $col['allow_filter'];

                    $filter['filter_label'] = __($row['filter_label']);
                    if (empty($filter['filter_label'])) {
                        $filter['filter_label'] = ucwords($col['label']);
                    }

                    $filter['filter_css'] = $row['filter_css'];
                    $filter['filter_onchange_js'] = $row['filter_onchange_js'];

                    if (!empty($row['filter_options_array'])) {
                        $filter['filter_options'] = json_decode($row['filter_options_array']);
                    } else if (!empty($col['options'])) {
                        $filter['filter_options'] = $col['options'];
                    }

                    if (!empty($row['filter_attr_array'])) {
                        $filter['filter_attr'] = json_decode($row['filter_attr_array']);
                    }

                    //force select2
                    $filter['filter_type'] = $row['filter_type'];
                    if (!empty($filter['filter_options']) && empty($filter['filter_type'])) {
                        $filter['filter_type'] = 'tcg_select2';
                    }
                    if (empty($filter['filter_type'])) {
                        $filter['filter_type'] = $col['type'];
                    }

                    $filter['filter_invalid_value'] = ($row['filter_invalid_value'] == 1);

                    $this->filter_metas[] = $filter;
                    $this->filter_columns[] = $col['name'];
                }
    
                $this->column_metas[] = $col;
                $this->select_columns[] = $col['column_name']." as ".$col['name'];
                $this->columns[] = $col['name'];
            }

        } while (false);

        //var_dump( $this->join_tables );

        //always add lookup-column
        $key_column_name = $this->table_name. "." .$this->table_metas['lookup_column']. ' as ' .$this->table_metas['lookup_column'];       
        if (false === array_search($key_column_name, $this->select_columns)) {
            $col = static::$COLUMN;
            $col['name'] = $this->table_metas['lookup_column'];
            $col['label'] = __('Lookup');
            $col['visible'] = true;
            $col['data_priority'] = 10;
            $col['column_name'] = $key_column_name;

            $col['allow_insert'] = true;
            $col['allow_edit'] = true;
            $col['allow_filter'] = false;

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->select_columns, $key_column_name);
        }

        //always add key-column
        $key_column_name = $this->table_name. "." .$this->table_metas['key_column']. ' as ' .$this->table_metas['key_column'];       
        if (false === array_search($key_column_name, $this->select_columns)) {
            $col = static::$COLUMN;
            $col['name'] = $this->table_metas['key_column'];
            $col['label'] = __('Key');
            $col['visible'] = true;
            $col['data_priority'] = 1;
            $col['column_name'] = $key_column_name;

            $col['allow_insert'] = false;
            $col['allow_edit'] = false;
            $col['allow_filter'] = false;

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->select_columns, $key_column_name);
        }

        //custom actions
        do {
            $this->db->select('*');
            $this->db->order_by('order_no asc');
            $arr = $this->db->get_where('dbo_crud_custom_actions', array('table_id'=>$this->table_id, 'is_deleted'=>0))->result_array();
            if ($arr == null) {
                break;
            }

            foreach($arr as $row) {
                $action = static::$CUSTOM_ACTION;
                $action['label'] = $row['label'];
                $action['icon'] = $row['icon'];
                $action['icon_only'] = ($row['icon_only'] == 1);
                $action['css'] = $row['css'];
                $action['onclick_js'] = $row['onclick_js'];
                $action['selected'] = $row['selected'];

                $this->custom_actions[] = $action;
            }

        } while (false);
 
        //row actions
        do {
            $this->db->select('*');
            $this->db->order_by('order_no asc');
            $arr = $this->db->get_where('dbo_crud_row_actions', array('table_id'=>$this->table_id, 'is_deleted'=>0))->result_array();
            if ($arr == null) {
                break;
            }

            foreach($arr as $row) {
                $action = static::$ROW_ACTION;
                $action['label'] = $row['label'];
                $action['icon'] = $row['icon'];
                $action['icon_only'] = ($row['icon_only'] == 1);
                $action['css'] = $row['css'];
                $action['onclick_js'] = $row['onclick_js'];
                $action['conditional_js'] = $row['conditional_js'];

                $this->row_actions[] = $action;
            }

        } while (false);

        $this->table_metas['columns'] = $this->column_metas;
        $this->table_metas['editor_columns'] = $this->editor_metas;
        if (count($this->editor_metas) > 0) {
            $this->table_metas['editor'] = true;
        }
        $this->table_metas['filter_columns'] = $this->filter_metas;
        if (count($this->filter_metas) > 0) {
            $this->table_metas['filter'] = true;
        }
        $this->table_metas['table_actions'] = $this->table_actions;
        $this->table_metas['custom_actions'] = $this->custom_actions;
        $this->table_metas['row_actions'] = $this->row_actions;
        $this->table_metas['join_tables'] = $this->join_tables;

        //always include key-column in column search
        if (false === array_search($this->table_metas['key_column'], $this->search_columns)) {
            $this->search_columns[] = $this->table_metas['key_column'];
        }

        //always include lookup-column in column search
        if (false === array_search($this->table_metas['lookup_column'], $this->search_columns)) {
            $this->search_columns[] = $this->table_metas['lookup_column'];
        }

        //disable editor if no edit columns
        if (count($this->table_metas['editor_columns']) == 0) {
            $this->table_metas['editor'] = false;
        }

        //disable filter if no filter columns
        if (count($this->table_metas['filter_columns']) == 0) {
            $this->table_metas['filter'] = false;
        }

        $this->initialized = true;

        //initialized the distinct filter options
        //must be performed after initialization is completed
        foreach ($this->table_metas['filter_columns'] as $key => $row) {
            if($row['filter_type'] != 'distinct')   continue;

            ($this->table_metas['filter_columns'])[$key]['filter_options'] = $this->distinct_lookup($row['name']);
        }
        return true;
    }

    function tablemeta() {
        if (!$this->initialized)   return null;
        return $this->table_metas;
    }

    function tablename() {
        if (!$this->initialized)   return null;
        return $this->table_name;   
    }

    function key_column() {
        return $this->table_metas['key_column'];
    }

    function filter_columns() {
        return $this->filter_columns;
    }

    function is_initialized() {
        return $this->initialized;
    }

    function distinct_lookup($column, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->distinct_lookup($column, $filter);
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];

        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            if (false !== array_search($key, $this->columns)) {
                $this->db->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        $this->db->order_by($column);
        $this->db->distinct();
        $this->db->select($column .' as value');
        $arr = $this->db->get($table_name)->result_array();
        if ($arr == null)       return $arr;

        foreach($arr as $key => $row) {
            $arr[$key]['label'] = $row['value'];
        }

        //var_dump($arr);

        return $arr;
    }

    function lookup($filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->lookup($filter);
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];

        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            if (false !== array_search($key, $this->columns)) {
                $this->db->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        $this->db->select($this->table_metas['lookup_column'] .' as label, '. $this->table_metas['key_column'] .' as value');
        return $this->db->get($table_name)->result_array();
    }

    function search($query, $filter = null, $limit = null, $offset = null, $orderby = null) {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->search($query, $filter, $limit, $offset, $orderby);
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];

        //group search filter
        if ($query != "" && $query != null) {
            $this->db->group_start();
            foreach($this->search_columns as $key => $val) {
                $this->db->or_like($val, $query);
            }
            $this->db->group_end();
        }

        //add filter
        if ($filter != null && count($filter) > 0) {
            foreach($filter as $key => $val) {
                //TODO: use columnmeta[$key] and use $column_name
                if (false !== array_search($key, $this->columns)) {
                    if ($val == Mtable::INVALID_VALUE) {
                        $fkey = $this->join_tables[$key];
                        if ($fkey != null) {
                            $this->db->where($fkey['reference_alias'].".".$fkey['reference_lookup_column'], NULL);
                        }
                        else {
                            $this->db->where("$table_name.$key", NULL);
                        }
                    } else {
                        $this->db->where("$table_name.$key", $val);
                    }
                }
            }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where($table_name. '.is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        $select_str = implode(', ', $this->select_columns);

        $this->db->select($select_str);
        // $this->db->from($table_name);

        //join table
        foreach($this->join_tables as $row) {
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_key_column'];
            if ($row['reference_soft_delete']) {
                $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
            }
            if (!empty($row['reference_where_clause'])) {
                $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                $where_clause .= " AND " .$str;
            }

            $this->db->join($row['reference_table_name'] .' as '. $row['reference_alias'], $where_clause, 'LEFT OUTER');
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

        $arr = $this->db->get($table_name, $limit, $offset)->result_array();
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                foreach($arr as $idx => $row) {
                    if (isset( $row[$col['name']] )) {
                        $arr[$idx][$col['name']] = explode(',', $row[$col['name']]);
                    }
                }
            }
        }
        
        return $arr;
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->list($filter, $limit, $offset, $orderby);
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];

        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            if (false !== array_search($key, $this->columns)) {
                if ($val == Mtable::INVALID_VALUE) {
                    $fkey = $this->join_tables[$key];
                    if ($fkey != null) {
                        $this->db->where($fkey['reference_alias'].".".$fkey['reference_lookup_column'], NULL);
                    }
                    else {
                        $this->db->where("$table_name.$key", NULL);
                    }
                } else {
                    $this->db->where("$table_name.$key", $val);
                }
           }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where($table_name. '.is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        $select_str = implode(', ', $this->select_columns);

        $this->db->select($select_str);
        //$this->db->from($table_name);

        //join table
        foreach($this->join_tables as $row) {
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_key_column'];
            if ($row['reference_soft_delete']) {
                $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
            }
            if (!empty($row['reference_where_clause'])) {
                $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                $where_clause .= " AND " .$str;
            }

            $this->db->join($row['reference_table_name'] .' as '. $row['reference_alias'], $where_clause, 'LEFT OUTER');
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

        $arr = $this->db->get($table_name, $limit, $offset)->result_array();
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                foreach($arr as $idx => $row) {
                    if (isset( $row[$col['name']] )) {
                        $arr[$idx][$col['name']] = explode(',', $row[$col['name']]);
                    }
                }
            }
        }
        
        return $arr;
    }

    function detail($id, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->detail($id, $filter);
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];

        $this->db->where($table_name. '.' .$this->table_metas['key_column'], $id);
        if ($this->table_metas['soft_delete'])   $this->db->where($table_name. '.is_deleted', 0);

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $this->db->where($this->table_metas['where_clause']);

        $select_str = implode(', ', $this->select_columns);

        $this->db->select($select_str);

        //join table
        foreach($this->join_tables as $row) {
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_key_column'];
            if ($row['reference_soft_delete']) {
                $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
            }
            if (!empty($row['reference_where_clause'])) {
                $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                $where_clause .= " AND " .$str;
            }
            $this->db->join($row['reference_table_name'] .' as '. $row['reference_alias'], $where_clause, 'LEFT OUTER');
        }
        
        $arr = $this->db->get($table_name)->row_array();       
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                if (isset( $arr[$col['name']] )) {
                    $arr[$col['name']] = explode(',', $arr[$col['name']]);
                }
            }
        }
        
        return $arr;
    }

    function update($id, $valuepair, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->update($id, $valuepair, $filter);
        }

        //use dynamic crud
        //clean up non existing columns
        foreach(array_keys($valuepair) as $key) {
            if (false === array_search($key, $this->edit_columns)) {
                //invalid columns
                unset($valuepair[$key]);
            }
        }

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select" && isset($valuepair[ $col['name'] ])) {
                $val =  $valuepair[ $col['name'] ];
                if (is_array($val)) {
                    $val = implode(",", $val);
                }
                $valuepair[ $col['name'] ] = $val;
            }
        }

        $this->db->where($this->table_metas['key_column'], $id);

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $this->db->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['editable_table_name'];

        //inject updated 
        $valuepair['updated_on'] = date('Y/m/d H:i:s');
        $valuepair['updated_by'] = $this->session->userdata('user_id');

        $this->db->update($table_name, $valuepair);
        
        $affected = $this->db->affected_rows();
        if ($affected > 0) {
            //audit trail
            audittrail_update($table_name, $id, $valuepair);
        }

        return $id;
    }

    function delete($id, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->delete($id, $filter);
        }

        //use dynamic crud
        $filter[ $this->table_metas['key_column'] ] = $id;

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $this->db->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['editable_table_name'];

        if ($this->table_metas['soft_delete']) {
            $valuepair = array (
                'is_deleted' => 1,
                'updated_on' => date('Y/m/d H:i:s'),
                'updated_by' => $this->session->userdata('user_id')
            );

            $this->db->update($table_name, $valuepair, $filter);   
        }
        else {
            $this->db->delete($table_name, $filter);
        }

        $affected = $this->db->affected_rows();
        if ($affected > 0) {
            //audit trail
            audittrail_delete($table_name, $id);
        }

        return $affected;
    }

    function add($valuepair) {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->add($valuepair);
        }

        //use dynamic crud
        //clean up non existing columns
        foreach(array_keys($valuepair) as $key) {
            if (false === array_search($key, $this->edit_columns)) {
                //invalid columns
                unset($valuepair[$key]);
            }
        }

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select" && isset($valuepair[ $col['name'] ])) {
                $val =  $valuepair[ $col['name'] ];
                if (is_array($val)) {
                    $val = implode(",", $val);
                }
                $valuepair[ $col['name'] ] = $val;
            }
        }

        //inject
        $valuepair['created_by'] = $this->session->userdata('user_id');

        //use view if specified
        $table_name = $this->table_metas['editable_table_name'];

        $id = 0;
        
        $query = $this->db->insert($table_name, $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //audit trail
            audittrail_insert($table_name, $id, $valuepair);
        } 

        return $id;
    }

    function get_model($path) {
		$ci	=&	get_instance();
		$ci->load->model($path);

		$name = basename($path);

		return $ci->$name;
	}

    function get_lookup_options($table_name, $key_column, $lookup_column, $soft_delete = true, $where_clause = null) {
        if ($soft_delete)           $this->db->where('is_deleted', 0);
        if (!empty($where_clause))  $this->db->where($where_clause);

        $this->db->select($lookup_column .' as label, '. $key_column .' as value');
        return $this->db->get($table_name)->result_array();
	}

    function get_error_message() {
        return $this->error['message'];
    }

    function import($file) {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->import($file, $this->table_name);
        }

        $this->error['message'] = "";
        
        $table_id = $this->table_id;
        $table_name = $this->table_name;
        $columns = $this->table_metas['columns'];
        $key_col_name = $this->table_metas['key_column'];
        $join_tables = $this->table_metas['join_tables'];

        //upload file
        $data = $this->__upload_xls($file, $table_id, $table_name);
        if ($data == null) {
            return 0;
        }
        $import_id = $data['import_id'];
        $filepath = $data['filepath'];

        //create temporary table
        $temp_table_name = "temp_" .$table_name;
        $data = $this->__create_temp_table($temp_table_name, $columns);
        if ($data == null) {
            $sql = "update dbo_imports set status='" .$this->error['message']. "' where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }
        $export_columns = $data['export'];
        $import_columns = $data['import'];
        $column_types = $data['type'];

        //import xls
        $status = $this->__import_xls($filepath, $temp_table_name, $export_columns, $column_types);
        if($status == 0) {
            $sql = "update dbo_imports set status='" .$this->error['message']. "' where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }

        //process import
        $this->__process_import($table_name, $key_col_name, $temp_table_name, $import_columns, $join_tables);

        //drop temporary table
        $this->db->query("DROP TEMPORARY TABLE $temp_table_name;");

        //audit trail
        audittrail_trail($table_name, $import_id, "IMPORT", "Import from file " .$filepath);

        return 1;
    }

    private function __upload_xls($file, $table_id, $table_name) {
        $this->error['message'] = "";

        $ci	=& get_instance();
        $ci->load->helper("uploader");
        $uploader = new Uploader();

        $uploader->file_types = array("xls", "xlsx");
        $upload = $uploader->upload($file, "dbo_imports");
        if ($upload == null || !empty($upload["error"])) {
            //error uploading
            $this->error['message'] = "Gagal mengunggah fail.";
            return null;
        }

        //get upload detail
        $upload_id = $upload['id'];
        $sql = "select * from dbo_uploads where id=? and is_deleted=0";
        $upload = $this->db->query($sql, array($upload_id))->row_array();
        if ($upload == null) {
            $this->error['message'] = "Gagal mengunggah fail.";
            return null;
        }

        //copy to dbo_imports
        $import_id = 0;
        $sql = "insert into dbo_imports(table_id, filename, file_path, web_path, thumbnail_path, status) " .
                "select ?, filename, path, web_path, thumbnail_path, ? from dbo_uploads where is_deleted=0 and id=?";
        $query = $this->db->query($sql, array($table_id, "not-started", $upload_id));
        if ($query) {
            $import_id = $this->db->insert_id();
        } 

        if ($import_id == 0) {
            //error copying
            $this->error['message'] = "Gagal mengunggah fail.";
            return null;
        }

        //update upload ref_id
        $sql = "update dbo_uploads set ref_id=" .$import_id. " where id=" .$upload_id;
        $query = $this->db->query($sql);

        $retval = array(
            'import_id'     => $import_id,
            'filepath'      => $upload['path']
        );
        return $retval;
    }

    private function __create_temp_table($temp_table_name, $columns) {
        $this->error['message'] = "";

        $column_def = array();
        $import_columns = array();
        $export_columns = array();
        $column_type = array();
        foreach($columns as $key => $col) {
            if ($col['visible'] == 0)    continue;
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
            else if($col['type'] == 'tcg_toggle') {
                $column_def[] = $col['name'] ." smallint";
            }
            else if($col['type'] == 'tcg_upload') {
                $column_def[] = $col['name'] ." int";
            }
            else {
                $column_def[] = $col['name'] ." varchar(1000)";
            }
            //exported columns
            $export_columns[] = $col['name'];
            $column_type[] = $col['type'];
            //imported columns
            if ($col['allow_insert']) {
                $import_columns[] = $col['name'];
            }
        }

        if (count($import_columns) == 0) {
            $this->error['message'] = "Tidak ada kolom untuk diimpor";
            return null;
        }

        //always drop first
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS $temp_table_name;");

        //add status columns
        $column_def[] = "_update_ varchar(100) default 0";
        $column_def[] = "_tag2_ varchar(100) default null";
        $column_def[] = "_tag3_ varchar(100) default null";

        //create the table
        $sql = "create temporary table " .$temp_table_name. "(" .implode(',', $column_def). ")";
        $query = $this->db->query($sql);
        if (!$query) {
            $this->error['message'] = "Gagal membuat temporary table";
            return null;
        }

        $retval = array(
            'export'    => $export_columns,
            'import'    => $import_columns,
            'type'      => $column_type
        );
        return $retval;
    }

    private function __import_xls($file, $temp_table_name, $export_columns, $column_types) {

        $this->error['message'] = "";
		$reader = null;

        $path_parts = pathinfo($file);
        if (!isset($path_parts['extension'])) {
            $this->error['message'] = "Tipe file tidak diketahui.";
            return 0;
        }

        if (strtolower($path_parts['extension']) == strtolower(static::$XLSX_FILE_TYPE)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(static::$XLSX_FILE_TYPE);
            $reader->setLoadSheetsOnly(["Sheet1"]);
            $reader->setReadDataOnly(true);
        }
        else if (strtolower($path_parts['extension']) == strtolower(static::$XLS_FILE_TYPE)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(static::$XLS_FILE_TYPE);
            $reader->setLoadSheetsOnly(["Sheet1"]);
            $reader->setReadDataOnly(true);
        }
        else {
            $this->error['message'] = "Tipe file ." .$path_parts['extension']. " tidak bisa diimpor." ;
            return 0;
        }

        $spreadsheet = $reader->load($file);
        if ($spreadsheet == null) {
            $this->error['message'] = 'File tidak ditemukan.';
            return 0;
        }

        $sheet = $spreadsheet->getSheetByName('Sheet1');
        if ($sheet == null) {
            $this->error['message'] = 'Lembar Sheet1 tidak ditemukan.';
            return 0;
        }

        $import_values = array();

        $rows = $sheet->toArray();
        
        //sanity check: number of columns is as expected
        $col_labels = array_filter($rows[1]);     //row 2 is column label

        if (count($col_labels) != count($export_columns)) {
            $this->error['message'] = 'Jumlah kolom tidak sesuai.';
            return 0;
        }

        //insert row by row
        $dateFormat = 'Y-m-d';        
        $dateTimeFormat = 'Y-m-d h:i:s';        
        
        foreach ($rows as $rowid => $row) {
            //only read from 3 onward
            if ($rowid < 2) continue;

            //skip empty rows
            if ( empty( trim($row[0]) ) && empty( trim($row[1]) ) && empty( trim($row[2]) ) && empty( trim($row[3]) ) && empty( trim($row[4])))   continue;

            $value = array();
            foreach($export_columns as $idx => $col) {
                //for date, convert to string
                if ($column_types[$idx] == 'tcg_date') {
                    $val = trim($row[$idx]);
                    $ts = strtotime($val);
                    if (empty($ts)) {
                        $ts = intval($val);
                        if ($ts > 0) {
                            $ts = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ts);
                            $val = date( $dateFormat, $ts);
                        }
                        else {
                            $val = null;
                        }
                    }
                    $value[ $col ] = $val;
                }
                else if ($column_types[$idx] == 'tcg_datetime') {
                    $val = trim($row[$idx]);
                    $ts = strtotime($val);
                    if (empty($ts)) {
                        $ts = intval($val);
                        if ($ts > 0) {
                            $ts = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ts);
                            $val = date( $dateTimeFormat, $ts);
                        }
                        else {
                            $val = null;
                        }
                    }
                    $value[ $col ] = $val;
                }
                else {
                    $value[ $col ] = trim($row[$idx]);
                }
            }

            $import_values[] = $value;
        }

        //batch insert
        if(!$this->db->insert_batch($temp_table_name, $import_values)) {
            //error message
            $this->error['message'] = $this->db->error()['message'];
            return 0;
        }

        return 1;

    }

    private function __process_import($table_name, $key_column_name, $temp_table_name, $import_columns, $join_tables) {
        //check for duplicate key
        $sql = "update " .$temp_table_name. " a join " .$table_name. " b on b." .$key_column_name. "=a." .$key_column_name. " set a._update_=1";
        $this->db->query($sql);

        //match foreign key
        foreach($join_tables as $idx => $tbl) {
            $sql = "update " .$temp_table_name. " a join " .$tbl['reference_table_name']. " b on lower(b." .$tbl['reference_lookup_column']. ")=lower(a." .$tbl['name']. ") set a." .$tbl['name']. "=b." .$tbl['reference_key_column'];
            $this->db->query($sql);
        }

        // $arr = $this->db->query("select * from " .$temp_table_name)->result_array();
        // var_dump($arr);

        //insert new entry
        $column_list = implode(',', $import_columns);
        $sql = "insert into " .$table_name. "(" .$column_list. ") select " .$column_list. " from " .$temp_table_name. " where _update_ != 1";
        $this->db->query($sql);

        $update_list = implode(',', array_map(function($val) {return 'a.'.$val.'=b.'.$val;}, $import_columns));
        $user_id = $this->session->userdata('user_id');
        $timestamp = date('Y/m/d H:i:s');
        $sql = "update " .$table_name. " a join " .$temp_table_name. " b on b." .$key_column_name. "=a." .$key_column_name. " set " .$update_list. ", a.is_deleted=0, a.updated_by=" .$user_id. ", a.updated_on='" .$timestamp. "'";
        $this->db->query($sql);

    }

    public function generate_columns($table_id_or_name) {
        $sql = "
        insert into dbo_crud_columns (name, table_id, order_no, label, is_deleted, data_priority, column_type, edit_type, filter_type)
        select 
            b.column_name as `name`, 
            a.id as table_id, 
            b.ordinal_position as order_no, 
            fn_camel_case(replace(b.column_name, '_', ' ')) as label,
            case when b.column_name in ('created_on', 'created_by', 'updated_on', 'updated_by', 'is_deleted') then 1 else 0 end as is_deleted,
            case when b.column_name = a.key_column then 1 else 100 end as data_priority,
            'tcg_text' as column_type, 
            'tcg_text' as edit_type, 
            'tcg_text' as filter_type
        from dbo_crud_tables a
        join INFORMATION_SCHEMA.COLUMNS b on b.table_name=a.table_name and b.table_schema=DATABASE() 
        left join dbo_crud_columns x on x.table_id=a.id and x.name=b.column_name
        where (a.id=? or a.table_name=?)
            and a.is_deleted=0
            and x.id is null
            ";

        $this->db->query($sql, array($table_id_or_name, $table_id_or_name));
    }
}

  