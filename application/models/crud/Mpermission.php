<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpermission extends CI_Model
{
    private static $ADMIN_ROLE_ID = 1;

    protected $table_name = '';
    protected $permissions = array();
    protected $initialized = false;

    public function init($table) {
        if ($table == $table_name)      return true;

        $this->initialized = false;
        
        $this->table_name = $name;
        $this->permissions = array();

        //table metas
        $this->db->select('*');
        $arr = $this->db->get_where('dbo_crud_permissions', array('name'=>$name, 'is_deleted'=>0))->row_array();
        if ($arr == null) {
            return false;
        }

        $this->permissions = $arr;

        $this->initialized = true;

        return true;
    }

    public function can_view($table) {
        $role_id = $this->session->userdata('role_id');

        //admin pass-through
        if ($role_id == static::$ADMIN_ROLE_ID)     return true;

        if ($table !== $this->$table_name) {
            //reinit
            $this->init($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_view']) && $this->permissions['allow_view'] == 1)
            return true;

        if (isset($this->permissions['allow_add']) && $this->permissions['allow_add'] == 1)
            return true;
        
        if (isset($this->permissions['allow_edit']) && $this->permissions['allow_edit'] == 1)
            return true;
        
        if (isset($this->permissions['allow_delete']) && $this->permissions['allow_delete'] == 1)
            return true;

        return false;
    }

    public function can_add($table) {
        $role_id = $this->session->userdata('role_id');

        //admin pass-through
        if ($role_id == static::$ADMIN_ROLE_ID)     return true;

        if ($table !== $this->$table_name) {
            //reinit
            $this->init($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_add']) && $this->permissions['allow_add'] == 1)
            return true;

        return false;
    }

    public function can_edit($table) {
        $role_id = $this->session->userdata('role_id');

        //admin pass-through
        if ($role_id == static::$ADMIN_ROLE_ID)     return true;

        if ($table !== $this->$table_name) {
            //reinit
            $this->init($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_edit']) && $this->permissions['allow_edit'] == 1)
            return true;

        return false;
    }

    public function can_delete($table) {
        $role_id = $this->session->userdata('role_id');

        //admin pass-through
        if ($role_id == static::$ADMIN_ROLE_ID)     return true;

        if ($table !== $this->$table_name) {
            //reinit
            $this->init($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_delete']) && $this->permissions['allow_delete'] == 1)
            return true;

        return false;
    }
}