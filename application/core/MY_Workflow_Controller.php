<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH ."/models/wf/flow/Step.php";

use Tcg\Workflow\Flow\Step;

abstract class MY_Workflow_Controller extends CI_Controller {

	abstract function get_ajax_url($table);

	public function _remap($method, $params = array())
	{
		$wf_name = $method;
		$wf_method = "";
		if (count($params) > 0) {
			$wf_method = array_shift($params);
		}

		//invalid params
		if (empty($wf_name) || empty($wf_method)) {
			theme_404();		//not-found
			return;
		}

		//pass to the correct function
		if (method_exists($this, $wf_method))
		{
			return call_user_func_array(array($this, $wf_method), array($wf_name, $params));
		}
		$this->workflow($wf_name, $wf_method, $params);
	}

	protected function workflow($wf_name, $wf_method, $params = array())
	{
		if (empty($wf_name) || empty($wf_method)) {
			theme_404();		//not-found
			return;
		}

		//check for permission
		$this->load->model(array('wf/Mworkflow', 'wf/Mpermission', 'crud/Mnavigation'));
		if (!$this->Mpermission->can_view($wf_name)) {
			theme_403();		//not-authorized
			return;
		}
		
		//TODO: workflow page

		$wf = $this->Mworkflow->get_workflow($wf_name);
		if ($wf == null) {
			theme_404();
			return;
		}

		if ($wf_method == 'add') {
			$this->table_add($page);
			return;
		}

		if ($wf_method == 'edit') {
			$id = array_shift($params);
			if ($id != null)		$this->table_edit($page, $id);
			else					$this->table_add($page);
			
			return;
		}

		//navigation
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));

		//var_dump($navigation); exit;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];
		$page_data['query_params']           = $params;

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	}

	protected function start($wf_name, $params = array()) {
		//TODO
	}

	protected function payload($wf_name) {
		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {
			//TODO
		}
		else if ($action=='edit'){
			//TODO
		}
		else if ($action=='uploadFile') {
			//TODO
            $key = $this->input->post("field");
            if (!isset($key)) {
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
       
			$this->load->helper("uploader");
			$uploader = new Uploader();

			//TODO: get parameter from columnmeta
            $uploader->file_types = array();
            $uploader->max_dimension = 200;
            $uploader->max_size_mb = 100;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['file'], $table_name);

            $data['files'] = array();
            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data['status'] = 1;
                $data['files'][] = $fileObj;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
		}
		else if ($action=='removeFile'){
			//TODO
			$files = $this->input->post("files");
            if (!isset($files)) {
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
            $files = explode(',', $files);

            $this->load->helper("uploader");
			$uploader = new Uploader();

            $error_msg = "";
			foreach ($files as $key) {
                $uploader->remove($key, $table_name);
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}
		else if ($action=='listFile') {
			//TODO
			$files = $this->input->post("files");
            if (!isset($files)) {
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
            $files = explode(',', $files);

            //var_dump($files);

            $this->load->helper("uploader");
			$uploader = new Uploader();

            $error_msg = "";
            $data['files'] = array();
			foreach ($files as $key=>$value) {
                $fileObj = $uploader->detail($value, $table_name);
                if ($fileObj != null) {
                    $data['files'][] = $fileObj;
                }
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}
        else {
            $data['error'] = __('not-implemented');
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
        }

		return;
	}

	protected function confirm($wf_name, $params = array()) {
		//TODO 
		$token = $this->input->get("token");
		$value = $this->input->get("value");

		$this->load->model(array('wf/Mworkflow', 'wf/Mpermission'));
		$step = $this->Mworkflow->get_step_by_token($wf_name, $token);
		if ($step == null) {
			//TODO
		}

		if ($step['status'] == Step::STEP_COMPLETED || $step['status'] == Step::STEP_TERMINATED) {
			//TODO
		}

		if ($step['status'] == Step::STEP_NOTREACHED) {
			//TODO
		}

		if ($step['status'] != Step::STEP_STARTED) {
			//TODO
		}
	}

	protected function get_model($table_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_id, true)) {
			return null;
		}

		return $this->Mtable;
	}

}
