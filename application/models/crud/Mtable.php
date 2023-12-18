<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mtable
 * 
 * This the CRUD class used by the framework. 
 * 
 * If data_model is provided, it will use the data_model class (presumably a subclass of Mcrud). 
 * The data_model class provide encapsulation of the table definition and configuration
 * 
 * If data_model is not provided, it will retrieve the table definition and configuration from database.
 * It will then use Mcrud_tablemeta as the data model class
 *  
 */
require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mtable extends CI_Model
{
    protected static $TABLE_ID = null;     //table

    protected $data_model = null;
    protected $initialized = false;

    public function __construct() {
        //TODO
    }

    public function init_for_lookup($name_or_id, $is_table_id = false) {
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

        $this->data_model = null;

        //data model
        if (!empty($arr['data_model'])) {
            try {
                $this->data_model = $this->get_model($arr['data_model']);
                if (!$this->data_model->init_with_tablemeta_for_lookup($arr)) {
                    $this->data_model = null;
                }
            }
            catch (exception $e) {
                $this->data_model = null;
            }
        }     

        if ($this->data_model == null) {
            $this->data_model = new Mcrud_tablemeta();
            $this->data_model->init_for_lookup($arr['id'], true);
        }

        if($this->data_model != null) {
            $this->initialized = true;
            return true;
        }

        return false;
    }

    public function init($name_or_id, $is_table_id = false, $level1_column = null, $level1_value = null) {
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

        $this->data_model = null;

        //data model
        if (!empty($arr['data_model'])) {
            try {
                $this->data_model = $this->get_model($arr['data_model']);
                if (!$this->data_model->init_with_tablemeta($arr, $level1_column, $level1_value)) {
                    $this->data_model = null;
                }
            }
            catch (exception $e) {
                $this->data_model = null;
            }
        }     

        if ($this->data_model == null) {
            $this->data_model = new Mcrud_tablemeta();
            $this->data_model->init($arr['id'], true, $level1_column, $level1_value);
        }

        if($this->data_model != null) {
            $this->initialized = true;
            return true;
        }

        return false;
    }

    public function distinct_lookup($column, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->distinct_lookup($column, $filter);
        }

        return null;
    }

    public function lookup($filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->lookup($filter);
        }

        return null;
    }

    public function search($query, $filter = null, $limit = null, $offset = null, $orderby = null) {
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->search($query, $filter, $limit, $offset, $orderby);
        }

        return null;
    }

    public function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if (!$this->initialized)   return null;

        $tablemeta = $this->data_model->tablemeta();

        if ($filter == null) $filter = array();

        if ($orderby == null && $tablemeta != null) $orderby = $tablemeta['orderby_clause'];
        if ($limit == null && $tablemeta != null) $limit = $tablemeta['limit_selection'];

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->list($filter, $limit, $offset, $orderby);
        }

        return null;
    }

    public function detail($id, $filter = null) {
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            // //convert as string to make sure no overload of string
            // $id = strval($id);
            return $this->data_model->detail($id, $filter);
        }

        return null;
    }

    public function update($id, $valuepair, $filter = null) {
        if (!$this->initialized)   return 0;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            // //convert as string to make sure no overload of string
            // $id = strval($id);
            return $this->data_model->update($id, $valuepair, $filter);
        }

        return 0;
    }

    public function delete($id, $filter = null) {
        if (!$this->initialized)   return 0;

        if ($filter == null) $filter = array();

        //use data model
        if ($this->data_model != null) {
            // //convert as string to make sure no overload of string
            // $id = strval($id);
            return $this->data_model->delete($id, $filter);
        }

        return 0;
    }

    public function add($valuepair) {
        if (!$this->initialized)   return 0;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->add($valuepair);
        }

        return 0;
    }

    public function import($file) {
        if (!$this->initialized)   return 0;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->import($file);
        }

        return 0;
    }

    public function tablemeta() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->tablemeta();
        }

        return null;
    }

    public function tablename() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->tablename();
        }

        return null;
    }

    function editable_table() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->editable_table();
        }

        return null;
    }

    public function key_column() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->key_column();
        }

        return null;
    }

    public function filter_columns() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->filter_columns();
        }

        return null;
    }

    public function is_initialized() {
        return $this->initialized;
    }

    public function get_error_message() {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->get_error_message();
        }

        return null;
    }

    public function generate_columns($table_id_or_name) {
        $sql = "
        insert into dbo_crud_columns (name, table_id, order_no, label, is_deleted, data_priority, column_type, edit_type, filter_type)
        select 
            b.column_name as `name`, 
            a.id as table_id, 
            b.ordinal_position as order_no, 
            fn_camel_case(replace(b.column_name, '_', ' ')) as label,
            case when b.column_name in ('created_on', 'created_by', 'updated_on', 'updated_by', 'is_deleted') 
                or b.column_name like '%_filename' or b.column_name like '%_path' or b.column_name like '%_thumbnail'
            then 1 else 0 end as is_deleted,
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

    public function clone($table_id_or_name) {
        //get table id
        $sql = "SELECT a.id FROM `dbo_crud_tables` a WHERE (a.id=? or a.table_name=?) and a.is_deleted=0 LIMIT 1";
        $detail = $this->db->query($sql, array($table_id_or_name, $table_id_or_name))->row_array();

        if ($detail == null) {
            return 0;
        }

        $table_id = $detail['id'];

        //clone table
        $sql = "
            INSERT INTO `dbo_crud_tables`
            (
                `name`,
                `title`,
                `table_name`,
                `editable_table_name`,
                `key_column`,
                `lookup_column`,
                `ajax`,
                `initial_load`,
                `row_id_column`,
                `row_select_column`,
                `editor`,
                `allow_add`,
                `allow_edit`,
                `allow_delete`,
                `add_custom_js`,
                `edit_custom_js`,
                `delete_custom_js`,
                `edit_row_action`,
                `delete_row_action`,
                `allow_export`,
                `export_custom_js`,
                `allow_import`,
                `import_custom_js`,
                `filter`,
                `search`,
                `soft_delete`,
                `data_model`,
                `where_clause`,
                `orderby_clause`,
                `limit_selection`,
                `page_size`,
                `custom_css`,
                `custom_js`
            )
            SELECT 
                concat(`name`, '_" .generate_token(5). "'),
                `title`,
                `table_name`,
                `editable_table_name`,
                `key_column`,
                `lookup_column`,
                `ajax`,
                `initial_load`,
                `row_id_column`,
                `row_select_column`,
                `editor`,
                `allow_add`,
                `allow_edit`,
                `allow_delete`,
                `add_custom_js`,
                `edit_custom_js`,
                `delete_custom_js`,
                `edit_row_action`,
                `delete_row_action`,
                `allow_export`,
                `export_custom_js`,
                `allow_import`,
                `import_custom_js`,
                `filter`,
                `search`,
                `soft_delete`,
                `data_model`,
                `where_clause`,
                `orderby_clause`,
                `limit_selection`,
                `page_size`,
                `custom_css`,
                `custom_js`
            FROM `dbo_crud_tables`
            WHERE (id=?) and is_deleted=0
            limit 1";

        $this->db->query($sql, array($table_id));
        
        $new_table_id = $this->db->insert_id();

        //clone columns
        $sql = "
            INSERT INTO `dbo_crud_columns`
            (
                `table_id`,
                `name`,
                `column_name`,
                `order_no`,
                `visible`,
                `label`,
                `css`,
                `column_type`,
                `data_priority`,
                `options_array`,
                `options_data_model`,
                `options_data_url`,
                `foreign_key`,
                `reference_table_name`,
                `reference_alias`,
                `reference_key_column`,
                `reference_lookup_column`,
                `reference_soft_delete`,
                `reference_where_clause`,
                `allow_insert`,
                `allow_edit`,
                `edit_field`,
                `edit_label`,
                `edit_type`,
                `edit_css`,
                `edit_compulsory`,
                `edit_info`,
                `edit_attr_array`,
                `edit_bubble`,
                `edit_onchange_js`,
                `edit_options_array`,
                `edit_def_value`,
                `allow_filter`,
                `filter_label`,
                `filter_type`,
                `filter_css`,
                `filter_attr_array`,
                `filter_onchange_js`,
                `filter_options_array`,
                `filter_invalid_value`,
                `allow_search`,
                `display_format_js`,
                `subtable_id`,
                `subtable_key_column`,
                `subtable_fkey_column`,
                `subtable_order`,
                `subtable_row_reorder_column`
            )
            SELECT 
                ? as `table_id`,
                `name`,
                `column_name`,
                `order_no`,
                `visible`,
                `label`,
                `css`,
                `column_type`,
                `data_priority`,
                `options_array`,
                `options_data_model`,
                `options_data_url`,
                `foreign_key`,
                `reference_table_name`,
                `reference_alias`,
                `reference_key_column`,
                `reference_lookup_column`,
                `reference_soft_delete`,
                `reference_where_clause`,
                `allow_insert`,
                `allow_edit`,
                `edit_field`,
                `edit_label`,
                `edit_type`,
                `edit_css`,
                `edit_compulsory`,
                `edit_info`,
                `edit_attr_array`,
                `edit_bubble`,
                `edit_onchange_js`,
                `edit_options_array`,
                `edit_def_value`,
                `allow_filter`,
                `filter_label`,
                `filter_type`,
                `filter_css`,
                `filter_attr_array`,
                `filter_onchange_js`,
                `filter_options_array`,
                `filter_invalid_value`,
                `allow_search`,
                `display_format_js`,
                `subtable_id`,
                `subtable_key_column`,
                `subtable_fkey_column`,
                `subtable_order`,
                `subtable_row_reorder_column`
            FROM `dbo_crud_columns`
            where table_id=? and is_deleted=0";

        $this->db->query($sql, array($new_table_id, $table_id));


        //clone custom actions
        $sql = "
        INSERT INTO `dbo_crud_custom_actions`
        (
            `table_id`,
            `label`,
            `order_no`,
            `icon`,
            `icon_only`,
            `css`,
            `onclick_js`,
            `selected`
        )
        SELECT 
            ? as `table_id`,
            `label`,
            `order_no`,
            `icon`,
            `icon_only`,
            `css`,
            `onclick_js`,
            `selected`
        FROM `dbo_crud_custom_actions`
        where table_id=? and is_deleted=0";

        $this->db->query($sql, array($new_table_id, $table_id));


        //clone inline actions
        $sql = "
        INSERT INTO `dbo_crud_row_actions`
        (
            `table_id`,
            `label`,
            `order_no`,
            `icon`,
            `icon_only`,
            `css`,
            `onclick_js`,
            `conditional_js`
        )
        SELECT 
            ? as `table_id`,
            `label`,
            `order_no`,
            `icon`,
            `icon_only`,
            `css`,
            `onclick_js`,
            `conditional_js`
        FROM `dbo_crud_row_actions`
        where table_id=? and is_deleted=0";

        $this->db->query($sql, array($new_table_id, $table_id));

        return $new_table_id;
    }


    public function set_level1_filter($column_name, $value = null) {
        if (!$this->initialized)   return null;

        //use data model
        if ($this->data_model != null) {
            return $this->data_model->set_level1_filter($column_name, $value);
        }

        return null;
    }

    private function get_model($path) {
		$ci	=&	get_instance();
		$ci->load->model($path);

		$name = basename($path);

		return $ci->$name;
	}
}

  