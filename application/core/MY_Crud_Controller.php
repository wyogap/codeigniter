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

	protected function table($name = '', $params = array())
	{
		if (empty($name)) {
			theme_404();		//not-found
			return;
		}

		//check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission', 'crud/Mnavigation'));
		if (!$this->Mpermission->can_view($name)) {
			theme_403();		//not-authorized
			return;
		}
		
		$page = $this->Mpages->get_page($name);
		if ($page == null) {
			theme_404();
			return;
		}


		if (isset($params) && count($params) > 0 && $params[0] == 'add') {
			unset($params[0]);
			$this->table_add($page);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'edit') {
			unset($params[0]);

			//var_dump($params);

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

		$page_type = $page['page_type'];
		if ($page_type == 'custom') {
			$template = $page['custom_view'];
			if (empty($template)) {
				//default template
				$template = 'welcome.tpl';
			}

			//for custom page, tablemeta is optional
			$model = null;
			if (!empty($page['crud_table_id'])) {
				$model = $this->get_model($page['crud_table_id']);
			}

			if ($model != null) {
				$tablemeta = $model->tablemeta();
				//easy access for everything
				$page_data['crud']			 = $tablemeta; 
			}

			$this->smarty->render_theme($template, $page_data);
			
			return;
		}

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404();
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

		if (isset($params) && count($params) > 1 && $params[0] == 'subtable') {
			unset($params[0]);
			$subtable_id = array_shift($params);
			$this->table_subtable($page['id'], $subtable_id, $params);
			return;
		}

		$tablemeta = $model->tablemeta();

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = $this->get_ajax_url($name);

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		//get subtables if necessary
		$subtables = $this->Mpages->subtables($page['id'], true);
		foreach($subtables as $key => $val) {
			$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'];
			//override paging size if necessary
			if (!empty($val['page_size'])) {
				$subtables[$key]['crud']['page_size'] = $val['page_size'];
			}
			//override always autoload
			$subtables[$key]['crud']['initial_load'] = false;
		}

		if ($page_type == 'table') {
			$template = '/crud/table.tpl';
		}
		else if ($page_type == 'form') {
			$template = '/crud/form.tpl';
			//get detail key
			$key = $page['userdata_key'];
			if (!empty($key)) {
				//actual key is from userdata
				$key = $this->session->userdata($key);
				$detail = $model->detail($key);
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
		else {
			$template = '/crud/table.tpl';
		}

		//easy access for everything
		$page_data['crud']			 = $tablemeta; 
		$page_data['subtables']		 = $subtables;

		//var_dump($subtables); exit;

		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_add($page) {
		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404();		//not-found
			return;
		}

		//navigation
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));

		//var_dump($navigation); exit;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404();
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Buat") ." ". $tablemeta['name'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = $this->get_ajax_url($page['name']);

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//show link back to table
		$page_data['show_table_link'] = true;

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		$page_data['crud']			 = $tablemeta; 

		// //get subtables if necessary
		// $subtables = $this->Mpages->subtables($page['id'], true);
		// foreach($subtables as $key => $val) {
		// 	$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'];
		// 	//override paging size if necessary
		// 	if (!empty($val['page_size'])) {
		// 		$subtables[$key]['crud']['page_size'] = $val['page_size'];
		// 	}
		// }

		// //easy access for everything
		// $page_data['subtables']		 = $subtables;

		$page_data['detail'] = null; 

		$template = '/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_edit($page, $id=null) {
		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404();		//not-found
			return;
		}

		//navigation
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));

		$page_data['page_name']              = $page['name'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404();
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Ubah") ." ". $tablemeta['name'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = $this->get_ajax_url($page['name']);

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//show link back to table
		$page_data['show_table_link'] = true;

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		$page_data['crud']			 = $tablemeta; 

		//get detail
		$detail = null;

		$key = $page['userdata_key'];
		if (!empty($key)) {
			//actual key is from userdata
			$key = $this->session->userdata($key);
			$detail = $model->detail($key);
		}
		else if ($id != null) {
			//get from param
			$detail = $model->detail($id);
		}
		
		$page_data['detail'] = $detail; 

		if ($detail != null) {
			$parent_key = $detail[ $tablemeta['key_column'] ];

			//get subtables if necessary
			$subtables = $this->Mpages->subtables($page['id'], true);
			foreach($subtables as $key => $val) {
				//override paging size if necessary
				if (!empty($val['page_size'])) {
					$subtables[$key]['crud']['page_size'] = $val['page_size'];
				}
				//override always autoload
				$subtables[$key]['crud']['initial_load'] = true;
				$subtables[$key]['crud']['parent_key'] = $parent_key;
				//override ajax with the parent key
				$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'] .'/'. $parent_key;

			}

			//easy access for everything
			$page_data['subtables']		 = $subtables;
		}

		//var_dump($page_data);

		$template = '/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_detail($model, $params = null) {
		if ($params == null || count($params) == 0) {
			theme_404();
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
		$table_name = $model->tablename();
		
		//build params
		$filters = array();
		foreach($this->input->post() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//var_dump($filters);
		$key_column = $model->key_column();

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

				if (isset( $valuepair[$model->key_column()] )) {
					$key = $valuepair[$model->key_column()];
				}
				
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
				$key = $valuepair[$key_column];
				$filters = array_merge($filters, $valuepair);
				unset($filters[$key_column]);
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
		else if ($action=='uploadFile') {
            
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
        }
        else if ($action=='listFile') {
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
                $fileObj = $uploader->detail($value);
                if ($fileObj != null) {
                    $data['files'][] = $fileObj;
                }
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action == "import") {
            $status = $model->import($_FILES['upload']);

            if($status == 0) {
                $data['error'] = $model->get_error_message();
            } else {
                $data['status'] = $status;
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

	protected function table_subtable($page_id, $subtable_id, $params = null) {

		$subtable = $this->Mpages->subtable_detail($page_id, $subtable_id);
		if($subtable == null) {
			$data['error'] = 'Invalid table-id';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}

		$model = $this->get_model($subtable_id);
		if ($model == null) {
			$data['error'] = 'Invalid table-id';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}
		
		//build params
		$filters = array();

		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {
			
			$filter_value = '';
			if (isset($params) && count($params) > 0) {
				$filter_value = $params[0];
			}
			$filters[ $subtable['subtable_fkey_column'] ] = $filter_value;

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

			$filter_value = '';
			if (isset($params) && count($params) > 0) {
				$filter_value = $params[0];
			}
			$values[0][ $subtable['subtable_fkey_column'] ] = $filter_value;
			
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

	protected function get_model($table_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_id, true)) {
			return null;
		}

		return $this->Mtable;
	}

	// protected function theme_404() {
	// 	$theme = $this->session->userdata('theme');
	// 	if (!isset($theme)) {
	// 		$theme = 'default';
	// 	}

	// 	$page_data = array();
	// 	$page_data['page_name']              = "error-404";
	// 	$page_data['page_title']             = __("Not Found");
	// 	$page_data['page_icon']              = null;
	// 	$page_data['query_params']           = null;

	// 	$page_data['page_role']           	 = $this->session->userdata('page_role');

	// 	$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
	// 	$page_data['navigation']	 = $navigation;

	// 	$template = "error/404.tpl";
	// 	$this->smarty->render_theme($template, $page_data);
	// }

	// protected function theme_403() {
	// 	$theme = $this->session->userdata('theme');
	// 	if (!isset($theme)) {
	// 		$theme = 'default';
	// 	}

	// 	$template = "themes/$theme/error/403.tpl";
	// 	$this->smarty->render($template);
	// }

}
