<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Crud_Controller extends CI_Controller {

	abstract function get_ajax_url($table);

	public function _remap($method, $params = array())
	{
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		$this->table($method, $params);
	}

	protected function table($table = '', $params = array())
	{
		if (empty($table)) {
			$this->show_404();		//not-found
			return;
		}

		//check for permission
		$this->load->model('crud/Mpermission');
		if (!$this->Mpermission->can_view($table)) {
			$this->show_403();		//not-authorized
			return;
		}
		
		$model = $this->get_model($table);
		if ($model == null) {
			$this->show_404();
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'detail') {
			unset($params[0]);
			$this->table_detail($model, $params);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'json') {
			unset($params[0]);
			$this->table_json($model, $params);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'lookup') {
			unset($params[0]);
			$this->table_lookup($model, $params);
			return;
		}
	  
		$tablemeta = $model->tablemeta();

		//ajax url for data loading
		$tablemeta['ajax'] = $this->get_ajax_url($table);

		//var_dump($tablemeta); exit;

		$page_data['page_name']              = $tablemeta['page_name'];
		$page_data['page_title']             = $tablemeta['page_title'];
		$page_data['page_icon']              = $tablemeta['page_icon'];
		$page_data['query_params']           = $params;

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		if (!empty($tablemeta['header_view'])) 		$page_data['header_view'] = $tablemeta['header_view'];
		if (!empty($tablemeta['footer_view'])) 		$page_data['footer_view'] = $tablemeta['footer_view'];

		$page_type = $tablemeta['page_type'];
		if ($page_type == 'table') {
			$template = '/crud/table.tpl';
		}
		else if ($page_type == 'form') {
			$template = '/crud/form.tpl';
			//get detail key
			$key = $tablemeta['page_key'];
			if (!empty($key)) {
				//actual key is from userdata
				$key = $this->session->userdata($key);
				$detail = $model->detail($key);
				//var_dump($detail);
			}
			else if (isset($params) && count($params) > 0) {
				//get from param
				$key = $params[0];
				$detail = $model->detail($key);
			}
			else {
				$detail = array();
			}
			$page_data['detail'] = $detail; 
		}
		else if ($page_type == 'custom' && !empty($tablemeta['custom_view'])) {
			$template = $tablemeta['custom_view'];
		}
		else {
			$template = '/crud/table.tpl';
		}

		//easy access for everything
		$page_data['crud']			 = $tablemeta; 

		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_detail($model, $params = null) {
		if ($params == null || count($params) == 0) {
			show_404();
			return;
		}

		$id = $params[0];
		$page_data['detail'] = $model->detail($id);

		$page_data['page_name']              = $model->page_name();
		$page_data['page_title']             = $model->page_title();
		$page_data['page_icon']              = $model->page_title();
		$page_data['query_params']           = $params;

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['table_meta']			 = $model->tablemeta(); 

		$custom_view = $model->custom_view();
		if (empty($custom_view)) {
			$custom_view = 'crud/form.tpl';
		}
		
		//echo json_encode($page_data);
		$this->smarty->render_theme($custom_view, $page_data);
	}

	protected function table_json($model, $params = null) {
		//build params
		$filters = array();
		foreach($this->input->post() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//var_dump($filters);

		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {
			
            $json['data'] = $model->list($filters);

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){
			$values = $this->input->post("data");

			$error_msg = "";
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				$key = $model->update($key, $valuepair, $filters);
				if (!$key)	continue;		//TODO: catch error message

				$detail = $model->detail($key, $filters); 
			
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
                $model->delete($key, $filters);
			}

            $data['data'] = array(); 
            if (strlen($error_msg) > 0) {
                $data['error'] = $error_msg;
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='create') {
			$values = $this->input->post("data");

            $key = $model->add($values[0], $filters);
            if ($key == 0) {
                $data['error'] = $this->db->error()['message'];
            } else {
				$data['data'] = [];
				$detail = $model->detail($key, $filters); 
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

	protected function table_lookup($model_name, $params = null) {
			
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

		$json['data'] = $model->lookup($filters);

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
	}

	protected function get_model($table) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table)) {
			return null;
		}

		return $this->Mtable;
	}

	protected function show_404() {
		$theme = $this->session->userdata('theme');
		if (!isset($theme)) {
			$theme = 'default';
		}

		$template = "themes/$theme/error/404.tpl";
		$this->smarty->render($template);
	}

	protected function show_403() {
		$theme = $this->session->userdata('theme');
		if (!isset($theme)) {
			$theme = 'default';
		}

		$template = "themes/$theme/error/403.tpl";
		$this->smarty->render($template);
	}

}
