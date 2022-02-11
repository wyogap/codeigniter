<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Level1_Crud_Controller.php');

abstract class MY_Level1_Json_Controller extends MY_Level1_Crud_Controller {
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            json_not_authorized();
		}
    }

    function index($level1_name, $params = array()) {
        json_not_implemented();
    }

    public function _remap($method, $params = array())
	{
		if ($method == 'index') {
			$level1_name = '';
		}
		else {
			$level1_name = $method;
			$method = array_shift($params);
		}
		array_unshift($params, $level1_name);

		//get actual level1 name, in case we get level1_id
		$level1_name = $this->get_level1_name($level1_name);
		$level1_id = $this->get_level1_id($level1_name);
		$level1_title = $this->get_level1_title($level1_name);

		//check permission
		if (!$this->can_view($level1_name)) {
			json_not_authorized();
			return;
		}

		//set global vars
		$this->vars->set('level1', static::$LEVEL1_COLUMN);
		$this->vars->set('level1_id', $level1_id);
		$this->vars->set('level1_name', $level1_name);
		$this->vars->set('level1_title', $level1_title);

		//route to the function
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		
		if (empty($method)) {
			$this->index($level1_name, $params);
			return;
		}

		//for consistency, remove the group-name from params
		array_shift($params);

		$this->handle($level1_name, null, $method, $params);
	}

    //override default handle only to serve json
    public function handle($level1_name, $navigation, $name = '', $params = array()) {
		$controller = $this->router->class .'/'. $level1_name;

		if (empty($level1_name) || empty($name)) {
			json_not_authorized();
		}

		//check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission'));
		if (!$this->Mpermission->can_view($name)) {
			json_not_authorized();
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			json_not_implemented();
		}

		$level1_id = $this->get_level1_id($level1_name);

		//crud pages
		$model = $this->get_model($page['crud_table_id'], $level1_id);
		if ($model == null) {
			json_not_implemented();
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'lookup') {
			unset($params[0]);
			$this->table_lookup($level1_name, $model, $params);
			return;
		}

		if (isset($params) && count($params) > 1 && $params[0] == 'subtable') {
			unset($params[0]);
			$subtable_id = array_shift($params);
			$this->table_subtable($level1_name, $page['id'], $subtable_id, $params);
			return;
		}

        if (isset($params) && count($params) > 0 && $params[0] == 'json') {
            unset($params[0]);
        }

		//get permissions
		$permissions = $this->Mpermission->get_permission($page['name']);
		if (!$this->can_view($level1_name)) {
			$permissions['view'] = $permissions['edit'] = $permissions['delete'] = $permissions['add'] = 0;
		}
		if (!$this->can_edit($level1_name)) {
			$permissions['edit'] = $permissions['delete'] = $permissions['add'] = 0;
		}

        //page filters
        $page_filter = array();
        if (!empty($page['page_filter'])) {
            $arr = explode(',', $page['page_filter']);
            foreach($arr as $idx => $val) {
                $valuepair = explode('=', $val, 2);
                $key = trim($valuepair[0]);
                $param = '';
                if (count($valuepair) > 1) {
                    $param = trim($valuepair[1]);
                }
                //set as filters
                $page_filter[$key] = $param;
            }
        }
 
        //handle json only
        $this->table_json($level1_name, $model, $permissions, $params, $page_filter);
    }


}
