<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Level1_Controller.php');

abstract class MY_Level1_Crud_Controller extends MY_Level1_Controller {

	protected static $DEFAULT_MODEL = 'clazz/Clazz_classes';
	protected static $DEFAULT_PAGE = 'detail';

	protected static $PAGE_ROLE = 'clazz';
	
	abstract function get_group_title($group_name);

	abstract function get_group_id($group_name);

	abstract function get_group_name($group_name);

	abstract function get_model_name($table);

	abstract function get_page_name($table);

	public function handle_default($group_name, $table = '', $params = array())
	{
		if (empty($table)) {
			$this->handle_no_permission($group_name);
			return;
		}

		$model_name = $this->get_model_name($table);
		$page_name = $this->get_page_name($table);

		if (isset($params) && count($params) > 0 && $params[0] == 'json') {
			array_shift($params);
			$this->table_json($group_name, $model_name, $params);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'lookup') {
			array_shift($params);
			$this->table_lookup($group_name, $model_name, $params);
			return;
		}
		
		$page_data['page_name']              = $page_name;
		$page_data['page_title']             = __($table) .' | '. $this->get_group_title($group_name);
		$page_data['page_icon']              = "mdi-apple-keyboard-command";
		$page_data['query_params']           = $params;

		$page_data['group_id']           	 = $this->get_group_id($group_name);
		$page_data['group_name']           	 = $this->get_group_name($group_name);
		$page_data['group_title']            = $this->get_group_title($group_name);

		$page_data['page_role']           	 = static::$PAGE_ROLE;

		//echo json_encode($page_data);
		$this->load->view('index', $page_data);
	  
	}

	protected function table_json($group_name, $model_name, $params = array()) {

		$model = get_model($model_name);
		if ($model == null) {
			$json['error'] = "Cannot find data model";
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
			return;
		}		

		//build params
		$filters = array();
		foreach($this->input->post() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//get group_id
		$group_id = $this->get_group_id($group_name);

		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {
			
            $json['data'] = $model->level1_list($group_id, $filters);

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){
			$values = $this->input->post("data");

			$error_msg = "";
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				$key = $model->level1_update($group_id, $key, $valuepair, $filters);
				if (!$key)	continue;		//TODO: catch error message

				$detail = $model->level1_detail($group_id, $key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            if (strlen($error_msg) > 0) {
                $data['error'] = $error_msg;
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='remove') {
			$values = $this->input->post("data");

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$filters = array_merge($filters, $valuepair);
                $model->level1_delete($group_id, $key, $filters);
			}

            $data['data'] = array(); 
            if (strlen($error_msg) > 0) {
                $data['error'] = $error_msg;
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='create') {
			$values = $this->input->post("data");

            $key = $model->level1_add($group_id, $values[0], $filters);
            if ($key == 0) {
                $data['error'] = $this->db->error()['message'];
            } else {
				$data['data'] = [];
				$detail = $model->level1_detail($group_id, $key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }
        else if ($action == "upload") {
            $key = $this->input->post("uploadField");
       
			$this->load->helper("uploader");
			$uploader = new Uploader();

			//use default
            // $uploader->file_types = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf');
            // $uploader->max_dimension = 200;
            // $uploader->max_size_mb = 10;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['upload']);

            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
        }   
        else {
            $data['error'] = __('not-implemented');
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }

		return;
	}

	protected function table_lookup($group_name, $model_name, $params = array()) {

		$model = get_model($model_name);
		if ($model == null) {
			$json['error'] = "Cannot find data model";
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
			return;
		}		
			
		//build params
		$filters = array();
		foreach($this->input->post() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//get param override pos param (if any)
		foreach($this->input->get() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//get group_id
		$group_id = $this->get_group_id($group_name);

		//get lookup
		$json['data'] = $model->level1_lookup($group_id, $filters);

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
	}

	public function handle_no_permission($group_name, $params = array()) {
		echo "no-permission";
		return;
	}
}
