<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Crud_Controller.php');

abstract class MY_Json_Controller extends MY_Crud_Controller {
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if (static::$AUTHENTICATED && (!isset($isLoggedIn) || $isLoggedIn != TRUE)) {
			json_not_login();
		}
    }

    public function index($params = array())
	{
        json_not_implemented();
    }

    //override default handler to server only json
    protected function table($name = '', $params = array()) {
		if (empty($name)) {
			json_not_implemented();
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

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			json_not_implemented();
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'lookup') {
			unset($params[0]);
			$this->table_lookup($model, $params);
			return;
		}

		if (isset($params) && count($params) > 1 && $params[0] == 'subtable') {
			unset($params[0]);
			$subtable_id = array_shift($params);
			$this->table_subtable($page['id'], $subtable_id, $params);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'json') {
			unset($params[0]);
		}

		//get permissions
		$permissions = $this->Mpermission->get_permissions($page['name']);

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
        $this->table_json($model, $permissions, $params, $page_filter);
        return;
    }

}
