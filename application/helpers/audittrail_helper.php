<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once BASEPATH.'../vendor/autoload.php';

if ( ! function_exists('audittrail_trail'))
{
	function audittrail_trail($table, $reference, $action, $description, $keys = null, $values = null) {
        $ci =& get_instance();
        $pengguna_id = $ci->session->userdata("user_id");

        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys['created_on']);
            unset($keys['created_by']);
            unset($keys['updated_on']);
            unset($keys['updated_by']);
            unset($keys['is_deleted']);
        }

        //for consistency
        if ($keys == null)      $values = null;

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $ci->session->userdata("user_id")
        );

        $ci->db->insert('dbo_audit_trails', $valuepair);
    }
    
}

if ( ! function_exists('audittrail_insert'))
{
    function audittrail_insert($table, $reference, $keys, $values = null) {
        $ci =& get_instance();
        $pengguna_id = $ci->session->userdata("user_id");

        $action = "INSERT";
        $description = "Insert row";
        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys['created_on']);
            unset($keys['created_by']);
            unset($keys['updated_on']);
            unset($keys['updated_by']);
            unset($keys['is_deleted']);
        }

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $ci->session->userdata("user_id")
        );

        $ci->db->insert('dbo_audit_trails', $valuepair);
    }
}


if ( ! function_exists('audittrail_update'))
{
    function audittrail_update($table, $reference, $keys, $values = null) {
        $ci =& get_instance();
        $pengguna_id = $ci->session->userdata("user_id");

        $action = "UPDATE";
        $description = "Update row";
        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys['created_on']);
            unset($keys['created_by']);
            unset($keys['updated_on']);
            unset($keys['updated_by']);
            unset($keys['is_deleted']);
        }

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $ci->session->userdata("user_id")
        );

        $ci->db->insert('dbo_audit_trails', $valuepair);
    }
}


if ( ! function_exists('audittrail_delete'))
{
    function audittrail_delete($table, $reference) {
        $ci =& get_instance();
        $pengguna_id = $ci->session->userdata("user_id");

        $action = "DELETE";
        $description = "Delete row";
        $str_keys = "";
        $str_values = "";

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $ci->session->userdata("user_id")
        );

        $ci->db->insert('dbo_audit_trails', $valuepair);
    }
}


if ( ! function_exists('audittrail_softdelete'))
{
    function audittrail_softdelete($table, $reference) {
        $ci =& get_instance();
        $pengguna_id = $ci->session->userdata("user_id");

        $action = "SOFT DELETE";
        $description = "Soft-delete row";
        $str_keys = "";
        $str_values = "";

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $ci->session->userdata("user_id")
        );

        $ci->db->insert('dbo_audit_trails', $valuepair);
    }
}


?>        

        