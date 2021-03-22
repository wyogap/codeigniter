<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpermission extends CI_Model
{
    private static $ADMIN_ROLE_ID = 1;

    protected $page_name = '';
    protected $permissions = array();
    protected $initialized = false;

    public function init($page) {
        if ($page == $this->page_name)      return true;

        $this->initialized = false;
        
        $this->page_name = $page;
        $this->permissions = array(
            'no_access'     => 0,
            'allow_view'    => 0,
            'allow_add'     => 0,
            'allow_edit'    => 0,
            'allow_delete'  => 0
        );

        $role_id = $this->session->userdata('role_id');

        $sql = "select a.* from dbo_crud_permissions a join dbo_crud_pages b on b.id=a.page_id and b.is_deleted=0 where a.is_deleted=0 and a.role_id=? and b.name=?";

        //table metas
        $arr = $this->db->query($sql, array($role_id, $page))->row_array();
        if ($arr == null) {
            return false;
        }

        $this->permissions = $arr;

        $this->initialized = true;

        return true;
    }

    public function can_view($page) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($page !== $this->page_name) {
            //reinit
            $this->init($page);
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

    public function can_add($page) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($page !== $this->page_name) {
            //reinit
            $this->init($page);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_add']) && $this->permissions['allow_add'] == 1)
            return true;

        return false;
    }

    public function can_edit($page) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($page !== $this->page_name) {
            //reinit
            $this->init($page);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_edit']) && $this->permissions['allow_edit'] == 1)
            return true;

        return false;
    }

    public function can_delete($page) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($page !== $this->page_name) {
            //reinit
            $this->init($page);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_delete']) && $this->permissions['allow_delete'] == 1)
            return true;

        return false;
    }

    public function is_admin() {
        $role_id = $this->session->userdata('role_id');
        return ($role_id == static::$ADMIN_ROLE_ID);
    }
}