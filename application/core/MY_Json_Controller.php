<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Crud_Controller.php');

abstract class MY_Json_Controller extends MY_Crud_Controller {
    /** Enable if you want to support access to all CRUD pages */
	protected static $CRUD = false;

    public function _remap($method, $params = array()) {
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if (static::$AUTHENTICATED && (!isset($isLoggedIn) || $isLoggedIn != TRUE)) {
			$this->json_not_login();
		}

		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}

        if (static::$CRUD) {
            return $this->table($method, $params);
        }
		
        $this->json_not_implemented();
    }

    public function index($params = array())
	{
        $this->json_not_implemented();
    }

    //override default handler to server only json
    protected function table($name = '', $params = array()) {
		if (empty($name)) {
			$this->json_not_implemented();
		}
        
		//check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission'));
		if (!$this->Mpermission->can_view($name)) {
			$this->json_not_authorized();
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			$this->json_not_implemented();
		}

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			$this->json_not_implemented();
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
		$permissions = $this->Mpermission->get_page_permission($page['name']);

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
        $this->table_json($page['name'], $model, $params);
        return;
    }

    protected function table_add($page, $navigation) {
        $this->json_not_implemented();
    }

    protected function table_edit($page, $navigation, $id=null) {
        $this->json_not_implemented();
    }

    protected function table_detail($page, $model, $navigation, $params = null) {
        $this->json_not_implemented();
    }
}
