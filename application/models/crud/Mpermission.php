<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpermission extends CI_Model
{
    private static $ADMIN_ROLE_ID = 1;

    protected $page_name = '';
    protected $permissions = array();
    protected $initialized = false;

    protected $table_id = 0;
    protected $table_name = '';

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

        //not table
        $this->table_id = 0;
        $this->table_name = '';

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(isset($isLoggedIn) && $isLoggedIn == TRUE) {
            //logged-in -> check for permission for given role
            $role_id = $this->session->userdata('role_id');

            // $sql = "select a.* from dbo_crud_permissions a 
            //         join dbo_crud_pages b on b.id=a.page_id and b.is_deleted=0 
            //         where a.is_deleted=0 and a.role_id=? and b.name=?";

            $sql = "select a.*, b.is_public, b.page_type, c.id as table_id, c.name as table_name from dbo_crud_pages b 
                    join dbo_crud_permissions a on a.table_id=b.crud_table_id and a.is_deleted=0 
                    join dbo_crud_tables c on c.id=b.crud_table_id and c.is_deleted=0 
                    where b.is_deleted=0 and b.name=? and a.role_id=?";

            //table metas
            $arr = $this->db->query($sql, array($page, $role_id))->row_array();
            if ($arr !== null) {
                $this->permissions = array_merge($this->permissions, $arr);
                //table data
                $this->table_id = $arr['table_id'];
                $this->table_name = $arr['table_name'];
                //check for public page or custom page
                if ($arr['is_public'] == 1 || $arr['page_type'] == 'custom')
                    $this->permissions['allow_view'] = 1;
            }
        }
        else {
            //not logged-in -> check for public page or custom page
            $sql = "select a.is_public, a.page_type from dbo_crud_pages a where a.is_deleted=0 and a.name=?";
            $arr = $this->db->query($sql, array($page))->row_array();
            if ($arr != null) {
                if ($arr['is_public'] == 1 || $arr['page_type'] == 'custom')
                    $this->permissions['allow_view'] = 1;
            }
        }

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

    public function is_guardian() {
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE)      return false;   //not logged-in

        $guardian = $this->session->userdata('is_guardian');
        if ($guardian)     return true;

        return false;
    }

    public function is_student() {
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE)      return false;   //not logged-in

        $student = $this->session->userdata('is_student');
        if ($student)     return true;

        return false;
    }

    public function is_teacher() {
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE)      return false;   //not logged-in

        $admin = $this->session->userdata('is_teacher');
        if ($admin)     return true;

        return false;
    }

    public function is_admin() {
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE)      return false;   //not logged-in

        $admin = $this->session->userdata('admin');
        if ($admin)     return true;

        $is_admin = $this->session->userdata('is_admin');
        $is_superadmin = $this->session->userdata('is_superadmin');
        if ($is_admin || $is_superadmin)    return true;
        
        $role_id = $this->session->userdata('role_id');
        return ($role_id == static::$ADMIN_ROLE_ID);
    }

    public function init_table($table) {
        if ($table == $this->table_name)      return true;

        $this->initialized = false;
        
        $this->table_name = $table;
        $this->permissions = array(
            'no_access'     => 0,
            'allow_view'    => 0,
            'allow_add'     => 0,
            'allow_edit'    => 0,
            'allow_delete'  => 0
        );

        //not page
        $this->page_name = '';

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(isset($isLoggedIn) && $isLoggedIn == TRUE) {
            //logged-in -> check for permission for given role
            $role_id = $this->session->userdata('role_id');
            
            // $sql = "select a.* from dbo_crud_permissions a 
            //         join dbo_crud_pages b on b.id=a.page_id and b.is_deleted=0 
            //         where a.is_deleted=0 and a.role_id=? and b.name=?";

            $sql = "select a.*, b.id as table_id from dbo_crud_tables b 
                    join dbo_crud_permissions a on a.table_id=b.id and a.is_deleted=0 
                    where b.is_deleted=0 and b.name=? and a.role_id=?";

            //table metas
            $arr = $this->db->query($sql, array($table, $role_id))->row_array();
            if ($arr !== null) {
                $this->permissions = array_merge($this->permissions, $arr);
                //table id
                $this->table_id = $arr['table_id'];
            }
        }

        $this->initialized = true;

        return true;
    }
    
    public function can_view_table($table) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table !== $this->table_name) {
            //reinit
            $this->init_table($table);
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

    public function can_add_table($table) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table !== $this->table_name) {
            //reinit
            $this->init_table($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_add']) && $this->permissions['allow_add'] == 1)
            return true;

        return false;
    }

    public function can_edit_table($table) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table !== $this->table_name) {
            //reinit
            $this->init_table($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_edit']) && $this->permissions['allow_edit'] == 1)
            return true;

        return false;
    }

    public function can_delete_table($table) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table !== $this->table_name) {
            //reinit
            $this->init_table($table);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_delete']) && $this->permissions['allow_delete'] == 1)
            return true;

        return false;
    }

    public function init_table_id($table_id) {
        if ($table_id == $this->table_id)      return true;

        $this->initialized = false;
        
        $this->table_id = $table_id;
        $this->permissions = array(
            'no_access'     => 0,
            'allow_view'    => 0,
            'allow_add'     => 0,
            'allow_edit'    => 0,
            'allow_delete'  => 0
        );

        //not page
        $this->page_name = '';

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(isset($isLoggedIn) && $isLoggedIn == TRUE) {
            //logged-in -> check for permission for given role
            $role_id = $this->session->userdata('role_id');
            
            // $sql = "select a.* from dbo_crud_permissions a 
            //         join dbo_crud_pages b on b.id=a.page_id and b.is_deleted=0 
            //         where a.is_deleted=0 and a.role_id=? and b.name=?";

            $sql = "select a.*, b.name as table_name from dbo_crud_tables b 
                    join dbo_crud_permissions a on a.table_id=b.id and a.is_deleted=0 
                    where b.is_deleted=0 and b.id=? and a.role_id=?";

            //table metas
            $arr = $this->db->query($sql, array($table_id, $role_id))->row_array();
            if ($arr !== null) {
                $this->permissions = array_merge($this->permissions, $arr);
                //table name
                $this->table_name = $arr['table_name'];
            }
        }

        $this->initialized = true;

        return true;
    }
    
    public function can_view_table_id($table_id) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table_id !== $this->table_id) {
            //reinit
            $this->init_table_id($table_id);
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

    public function can_add_table_id($table_id) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table_id !== $this->table_id) {
            //reinit
            $this->init_table_id($table_id);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_add']) && $this->permissions['allow_add'] == 1)
            return true;

        return false;
    }

    public function can_edit_table_id($table_id) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table_id !== $this->table_id) {
            //reinit
            $this->init_table_id($table_id);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_edit']) && $this->permissions['allow_edit'] == 1)
            return true;

        return false;
    }

    public function can_delete_table_id($table_id) {
        //admin pass-through
        if ($this->is_admin())      return true;

        if ($table_id !== $this->table_id) {
            //reinit
            $this->init_table_id($table_id);
        }

        //blocked access
        if (isset($this->permissions['no_access']) && $this->permissions['no_access'] == 1)
            return false;

        if (isset($this->permissions['allow_delete']) && $this->permissions['allow_delete'] == 1)
            return true;

        return false;
    }    
}