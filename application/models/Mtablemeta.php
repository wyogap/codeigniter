<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mtablemeta extends CI_Model
{
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
        'custom_css' => '',
        'custom_js' => '',
        'search' => false,
        'search_max_result' => 0,
        'row_reorder' => false,
        'row_reorder_column' => null,
        'add_custom_js' => null,
        'edit_custom_js' => null,
        'delete_custom_js' => null,
        'on_add_custom_js' => null,
        'on_edit_custom_js' => null,
        'on_delete_custom_js' => null,
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
        'foreign_key' => false,
        'display_format_js' => ''
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

}

?>