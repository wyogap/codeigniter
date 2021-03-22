<?php
// @codeCoverageIgnoreStart
defined('BASEPATH') || exit('No direct script access allowed');
// @codeCoverageIgnoreEnd

class Setting
{
    protected static $SETTING_TABLE = 'dbo_settings';

    protected $ci = null;

    public function __construct($config = array())
    {
        //init
        $ci =& get_instance();
    }

    public function get($name, $default="", $group=null) {
        $ci =& get_instance();

        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $ci->db->select('value');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->row_array();
        if ($arr == null) {
            return $default;
        }

        return $arr['value'];
    }

    public function set($name, $value, $group=null) {
        $ci =& get_instance();
        
        $values = array (
            'value'         => $value,
            'updated_on'    => date('Y/m/d H:i:s'),     //utc
            'updated_by'    => $ci->session->userdata('user_id')
        );

        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $ci->update(static::$SETTING_TABLE, $values, $filters);
    }

    public function list() {
        $ci =& get_instance();
        
        $filters = array (
            'is_deleted'    => 0
        );

        $ci->db->select('name, group, value, description');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->result_array();

        return $arr;
    }

    public function list_group($group) {
        $ci =& get_instance();
        
        $filters = array (
            'is_deleted'    => 0,
            'group'         => $group
        );

        $ci->db->select('name, group, value, description');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->result_array();

        return $arr;
    }
}

