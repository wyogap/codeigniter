<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mtable extends CI_Model
{
    protected static $TABLE_ID = null;     //table

    protected $data_model = null;

    public function __construct() {
        //TODO
    }

    public function init($name_or_id, $is_table_id = false) {
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
                if (!$this->data_model->init_with_tablemeta($arr)) {
                    $this->data_model = null;
                }
            }
            catch (exception $e) {
                $this->data_model = null;
            }
        }     

        if ($this->data_model == null) {
            $this->data_model = new Mcrud_tablemeta($arr['id'], true);
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

        if ($filter == null) $filter = array();

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

    private function get_model($path) {
		$ci	=&	get_instance();
		$ci->load->model($path);

		$name = basename($path);

		return $ci->$name;
	}
}

  