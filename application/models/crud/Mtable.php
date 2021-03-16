<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mtable extends CI_Model
{
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
        'width' => ''
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
    );
    
    public static $FILTER = array (
        'name' => '',
        'allow_filter' => false,
        'filter_label' => '',
        'filter_type' => 'text',
        'filter_css' => '',
        'filter_options' => array(),
        'filter_onchange_js' => '',
        'filter_attr' => array()
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
        'column_name' => "",
        'reference_table_name' => '',
        'reference_column_name' => "",
        'reference_soft_delete' => false,
    );

    function __construct() {
        //TODO
    }

    function init($name) {
        $this->initialized = false;
        
        $this->table_name = $name;

        //table metas
        $this->db->select('*');
        $arr = $this->db->get_where('dbo_crud_tables', array('name'=>$name, 'is_deleted'=>0))->row_array();
        if ($arr == null) {
            return false;
        }

        //table name
        $this->table_id = $arr['id'];

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
        $this->table_metas['ajax'] = base_url('crud/'.$name);
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

        $this->table_metas['where_clause'] = $arr['where_clause'];

        $this->table_metas['soft_delete'] = ($arr['soft_delete'] == 1);
        $this->table_metas['data_model'] = $arr['data_model'];

        $this->table_metas['name'] = $this->table_name;
        
        $this->table_metas['page_name'] = $arr['page_name'];
        if (empty($this->table_metas['page_name'])) {
            $this->table_metas['page_name'] = $this->table_metas['name'];
        }

        $this->table_metas['page_title'] = $arr['page_title'];
        if (empty($this->table_metas['page_title'])) {
            $this->table_metas['page_title'] = ucwords( str_replace('.', ' ', $arr['page_name']) );
        }

        $this->table_metas['page_icon'] = $arr['page_icon'];
        $this->table_metas['page_type'] = $arr['page_type'];
        $this->table_metas['page_key'] = $arr['page_key'];
        $this->table_metas['header_view'] = $arr['header_view'];
        $this->table_metas['footer_view'] = $arr['footer_view'];
        $this->table_metas['custom_view'] = $arr['custom_view'];

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

        //inline edit row
        if ($this->table_actions['edit_row_action']) {
            $row_action = static::$ROW_ACTION;
            $row_action['label'] = "Edit";
            $row_action['icon'] = "fa fa-edit fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-primary";
            $this->row_actions[] = $row_action;
        }

        //inline delete row
        if ($this->table_actions['delete_row_action']) {
            $row_action = static::$ROW_ACTION;
            $row_action['label'] = "Delete";
            $row_action['icon'] = "fa fa-trash fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-danger";
            $this->row_actions[] = $row_action;
        }

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
                    $col['column_name'] = $col['name'];
                }

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

                $this->column_metas[] = $col;
                $this->columns[] = $col['column_name'];

                if ($col['allow_insert'] || $col['allow_edit']) {
                    $editor = static::$EDITOR;
                    $editor['name'] = $row['name'];
                    $editor['allow_insert'] = $col['allow_insert'];
                    $editor['allow_edit'] = $col['allow_edit'];
                    $editor['edit_field'] = $col['edit_field'];

                    $editor['edit_label'] = __($row['edit_label']);
                    if (empty($editor['edit_label'])) {
                        $editor['edit_label'] = ucwords($col['label']);
                    }
    
                    $editor['edit_type'] = $row['edit_type'];
                    if (empty($editor['edit_type'])) {
                        $editor['edit_type'] = $col['type'];
                    }
                    
                    $editor['edit_css'] = $row['edit_css'];
                    $editor['edit_compulsory'] = ($row['edit_compulsory'] == 1);
                    $editor['edit_info'] = $row['edit_info'];
                    $editor['edit_onchange_js'] = $row['edit_onchange_js'];
                    $editor['edit_bubble'] = ($row['edit_bubble'] == 1);

                    if (!empty($row['edit_options_array'])) {
                        $editor['edit_options'] = json_decode($row['edit_options_array']);
                    } else {
                        $editor['edit_options'] = $col['options'];
                    }

                    if (!empty($row['edit_attr_array'])) {
                        $editor['edit_attr'] = json_decode($row['edit_attr_array']);
                    }

                    $this->editor_metas[] = $editor;
                }

                if ($col['allow_filter']) {
                    $filter = static::$FILTER;
                    $filter['name'] = $row['name'];
                    $filter['allow_filter'] = $col['allow_filter'];

                    $filter['filter_label'] = __($row['filter_label']);
                    if (empty($filter['filter_label'])) {
                        $filter['filter_label'] = ucwords($col['label']);
                    }

                    $filter['filter_type'] = $row['filter_type'];
                    if (empty($filter['filter_type'])) {
                        $filter['filter_type'] = $col['type'];
                    }

                    $filter['filter_css'] = $row['filter_css'];
                    $filter['filter_onchange_js'] = $row['filter_onchange_js'];

                    if (!empty($row['filter_options_array'])) {
                        $editor['filter_options'] = json_decode($row['filter_options_array']);
                    } else {
                        $editor['filter_options'] = $col['options'];
                    }

                    if (!empty($row['filter_attr_array'])) {
                        $editor['filter_attr'] = json_decode($row['filter_attr_array']);
                    }

                    $this->filter_metas[] = $filter;
                }

                if (!empty($row['reference_table_name'])) {
                    $ref = static::$TABLE_JOIN;
                    $ref['column_name'] = $col['column_name'];
                    $ref['reference_table_name'] = $row['reference_table_name'];
                    $ref['reference_column_name'] = $row['reference_column_name'];
                    $ref['reference_soft_delete'] = $row['reference_soft_delete'];

                    $this->join_tables[] = $ref;
                }
            }

        } while (false);

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

        //include key-column and lookup-column in list of column
        if (false === array_search($this->table_metas['key_column'], $this->columns)) {
            $this->columns[] = $this->table_metas['key_column'];
        }

        if (false === array_search($this->table_metas['lookup_column'], $this->columns)) {
            $this->columns[] = $this->table_metas['lookup_column'];
        }

        $this->initialized = true;

        return true;
    }

    function tablemeta() {
        if (!$this->initialized)   return null;
        return $this->table_metas;
    }

    function lookup($filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->lookup($filter);
        }

        //use dynamic crud
        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            if (false !== array_search($key, $this->columns)) {
                $this->db->where($key, $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['table_name'];

        $this->db->select($this->table_metas['lookup_column'] .' as label, '. $this->table_metas['key_column'] .' as value');
        return $this->db->get($table_name)->result_array();
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->list($filter, $limit, $offset, $orderby);
        }

        //use dynamic crud
        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            if (false !== array_search($key, $this->columns)) {
                $this->db->where($key, $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $this->db->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $this->db->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['table_name'];

        $select_str = implode(', ', $this->columns);

        $this->db->select($select_str);
        $this->db->from($table_name);

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

        return $this->db->get($limit, $offset)->result_array();
    }

    function detail($id, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->detail($id, $filter);
        }

        //use dynamic crud
        $this->db->where($this->table_metas['key_column'], $id);
        if ($this->table_metas['soft_delete'])   $this->db->where('is_deleted', 0);

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $this->db->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['table_name'];

        $select_str = implode(', ', $this->columns);

        $this->db->select($select_str);

        return $this->db->get($table_name)->row_array();       
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
            if (false === array_search($key, $this->columns)) {
                //invalid columns
                unset($valuepair[$key]);
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

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->add($valuepair);
        }

        //use dynamic crud
        //clean up non existing columns
        foreach(array_keys($valuepair) as $key) {
            if (false === array_search($key, $this->columns)) {
                //invalid columns
                unset($valuepair[$key]);
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

}

  