<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Level1_Crud_Controller extends CI_Controller {

	protected static $PAGE_GROUP = null;

	protected static $LEVEL1_COLUMN = null;

	abstract function index($level1_name, $params = array());

	abstract function can_view($level1_name);

	abstract function can_edit($level1_name);

	abstract function get_level1_name($level1_name);

	abstract function get_level1_title($level1_name);

	abstract function get_level1_id($level1_name);

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

		//navigation
		$navigation = $this->get_navigation();

		//check permission
		if (!$this->can_view($level1_name)) {
			theme_403_with_navigation($navigation);
			return;
		}

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

		$this->handle($level1_name, $navigation, $method, $params);
	}

	public function handle($level1_name, $navigation, $name = '', $params = array())
	{
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}

		if (empty($level1_name) || empty($name)) {
			theme_403_with_navigation($navigation, $controller);
			return;
		}

		//check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission'));
		if (!$this->Mpermission->can_view($name)) {
			theme_403_with_navigation($navigation, $controller);		//not-authorized
			return;
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			theme_404_with_navigation($navigation, $controller);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'add') {
			unset($params[0]);
			$this->table_add($level1_name, $page, $navigation);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'edit') {
			unset($params[0]);

			//var_dump($params);

			$id = array_shift($params);
			if ($id != null)		$this->table_edit($level1_name, $page, $navigation, $id);
			else					$this->table_add($level1_name, $page, $navigation);
			
			return;
		}

		$level1_id = $this->get_level1_id($level1_name);

		//crud pages
		$model = $this->get_model($page['crud_table_id'], $level1_id);
		if ($model == null) {
			theme_404_with_navigation($navigation, $controller);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'detail') {
			unset($params[0]);
			$this->table_detail($level1_name, $page['name'], $model, $navigation, $params);
			return;
		}

		if (isset($params) && count($params) > 0 && $params[0] == 'json') {
			unset($params[0]);
			$this->table_json($level1_name, $page['name'], $model, $params);
			return;
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

		//table crud
		$page_data['level1_name']            = $level1_name;
		$page_data['level1_id']           	 = $level1_id;
		$page_data['level1_title']           = $this->get_level1_title($level1_name);
		$page_data['level1_column']        	 = static::$LEVEL1_COLUMN;

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}
		$page_data['controller'] = $controller;

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
				$model = $this->get_model($page['crud_table_id'], $level1_id);
			}

			if ($model != null) {
				$tablemeta = $model->tablemeta();
				//easy access for everything
				$page_data['crud']			 = $tablemeta; 
			}

			$this->smarty->render_theme($template, $page_data);
			
			return;
		}

		$tablemeta = $model->tablemeta();

		//pass on the GET params
		if (count($this->input->get()) > 0) {
			$page_data['get_params'] = http_build_query($this->input->get());
		}

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .'/'. $page_data['controller'] .'/'. $name;

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		if (!empty($page_data['get_params'])) {
			$tablemeta['ajax'] .= '?' .$page_data['get_params'];
		}

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
				//level1 data
				if ($key == 'level1_name') {
					$key = $page_data['level1_name'];
				}
				else if ($key == 'level1_id') {
					$key = $page_data['level1_id'];
				} 
				else {
					//actual key is from userdata
					$key = $this->session->userdata($key);
				}
				$detail = $model->detail($key);
			}
			else if (isset($params) && count($params) > 0) {
				//get from param
				$key = $params[0];
				$detail = $model->detail($key);
			}
			else {
				$key = $page_data['level1_id'];
				$detail = $model->detail($key);
				if ($detail == null) {
					$key = $page_data['level1_name'];
					$detail = $model->detail($key);
				}
			}
			$page_data['detail'] = $detail; 
		}
		else {
			$template = '/crud/table.tpl';
		}

		//easy access for everything
		$page_data['crud']			 = $tablemeta; 
		$page_data['subtables']		 = $subtables;

		//permission
		$permissions = array (
			'allow_edit'	=> ($this->Mpermission->can_edit($name) && $this->can_edit($level1_name))
		);
		$page_data['permissions']	= $permissions;

		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	  
	}

	protected function table_add($level1_name, $page, $navigation) {
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}

		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404_with_navigation($navigation, $controller);		//not-found
			return;
		}

		//check permission
		if (!$this->can_edit($level1_name)) {
			theme_403_with_navigation($navigation, $controller);
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($navigation, $controller);		//not-authorized
			return;
		}

		$level1_id = $this->get_level1_id($level1_name);

		$page_data['level1_name']            = $level1_name;
		$page_data['level1_id']           	 = $level1_id;
		$page_data['level1_title']           = $this->get_level1_title($level1_name);
		$page_data['level1_column']        	 = static::$LEVEL1_COLUMN;

		//var_dump($navigation); exit;

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}
		$page_data['controller'] = $controller;

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
		$model = $this->get_model($page['crud_table_id'], $level1_id);
		if ($model == null) {
			theme_404_with_navigation($navigation, $controller);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Buat") ." ". $tablemeta['name'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .'/'. $page_data['controller'] .'/'. $page['name'];

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

	protected function table_edit($level1_name, $page, $navigation, $id=null) {
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}

		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404_with_navigation($navigation, $controller);		//not-found
			return;
		}

		//check permission
		if (!$this->can_edit($level1_name)) {
			theme_403_with_navigation($navigation, $controller);
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($navigation, $controller);		//not-authorized
			return;
		}

		$level1_id = $this->get_level1_id($level1_name);

		$page_data['level1_name']            = $level1_name;
		$page_data['level1_id']           	 = $level1_id;
		$page_data['level1_title']           = $this->get_level1_title($level1_name);
		$page_data['level1_column']        	 = static::$LEVEL1_COLUMN;

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}
		$page_data['controller'] = $controller;

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
		$model = $this->get_model($page['crud_table_id'], $level1_id);
		if ($model == null) {
			theme_404_with_navigation($navigation, $controller);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Ubah") ." ". $tablemeta['name'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .'/'. $page_data['controller'] .'/'. $page['name'];

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
			//level1 data
			if ($key == 'level1_name') {
				$key = $page_data['level1_name'];
			}
			else if ($key == 'level1_id') {
				$key = $page_data['level1_id'];
			} 
			else {
				//actual key is from userdata
				$key = $this->session->userdata($key);
			}
			$detail = $model->detail($key);
		}
		else if ($id != null) {
			//get from param
			$detail = $model->detail($id);
		}
		else {
			$detail = $model->detail($page_data['level1_id']);
			if ($detail == null) {
				$detail = $model->detail($page_data['level1_name']);
			}
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

	protected function table_detail($level1_name, $page_name, $model, $navigation, $params = null) {
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}

		if ($params == null || count($params) == 0) {
			theme_404_with_navigation($navigation, $controller);
			return;
		}

		//check permission
		if (!$this->can_view($level1_name)) {
			theme_403_with_navigation($navigation, $controller);
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_view($page_name)) {
			theme_403_with_navigation($navigation, $controller);
			return;
		}

		$level1_id = $this->get_level1_id($level1_name);

		$page_data['level1_name']            = $level1_name;
		$page_data['level1_id']           	 = $level1_id;
		$page_data['level1_title']           = $this->get_level1_title($level1_name);
		$page_data['level1_column']        	 = static::$LEVEL1_COLUMN;

		if (isset($params) && count($params) > 0) {
			$id = $params[0];
			$page_data['detail'] = $model->detail($id);
		} 
		else {
			$id = $page_data['level1_id'];
			$page_data['detail'] = $model->detail($id);
			if ($page_data['detail'] == null) {
				$id = $page_data['level1_name'];
				$page_data['detail'] = $model->detail($id);
			}
		}

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role') .'/'. $level1_name;
		}
		else {
			$controller = $this->router->class .'/'. $level1_name;
		}
		$page_data['controller'] = $controller;

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

		//permission
		$permissions = array (
			'allow_edit'	=> ($this->Mpermission->can_edit($page_name) && $this->can_edit($level1_name))
		);
		$page_data['permissions']	= $permissions;
		
		//echo json_encode($page_data);
		$this->smarty->render_theme($custom_view, $page_data);
	}

	protected function table_json($level1_name, $page_name, $model, $params = null) {
		$table_name = $model->tablename();
		$db_table_name = $model->editable_table();
		
		//build params
		$filters = array();
		foreach($this->input->post() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		foreach($this->input->get() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//var_dump($filters);
		$key_column = $model->key_column();

		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_view($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}
			
            $json['data'] = $model->list($filters);

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$values = $this->input->post("data");

			$errors = array();
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				$key = $model->update($key, $valuepair, $filters);
				if (!$key)	{
					$errors[] = "$key: " .$model->get_error_message();
					continue;		
				}

				if (isset( $valuepair[$model->key_column()] )) {
					$key = $valuepair[$model->key_column()];
				}
				
				$detail = $model->detail($key, $filters); 

				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            if (count($errors) > 0) {
                $data['error'] = implode(', ', $errors);
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='remove') {

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_delete($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_add($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$values = $this->input->post("data");

            $key = $model->add($values[0], $filters);
            if ($key == 0) {
                //$data['error'] = $this->db->error()['message'];
				$data['error'] = $model->get_error_message();
            } else {
				$data['data'] = [];
				$detail = $model->detail($key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }
        else if ($action == "upload") {

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}
 
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

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}
            
            $key = $this->input->post("field");
            if (!isset($key)) {
				$data['error'] = 'invalid field';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
       
			$this->load->helper("uploader");
			$uploader = new Uploader();

			//TODO: get parameter from columnmeta
			$uploader->load_setting();
            // $uploader->file_types = array();
            // $uploader->max_dimension = 1200;
            // $uploader->max_size_mb = 100;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['file'], $db_table_name);

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

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$files = $this->input->post("files");
            if (!isset($files)) {
				$data['error'] = 'invalid files';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
            $files = explode(',', $files);

            $this->load->helper("uploader");
			$uploader = new Uploader();

            $error_msg = "";
			foreach ($files as $key) {
                $uploader->remove($key, $db_table_name);
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='listFile') {
			$files = $this->input->post("files");
            if (!isset($files)) {
				$data['error'] = 'invalid files';
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

			//check permission
			if (!$this->can_edit($level1_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name) || !$this->Mpermission->can_add($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

	protected function table_lookup($level1_name, $model, $params = null) {
			
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

	protected function table_subtable($level1_name, $page_id, $subtable_id, $params = null) {

		$level1_id = $this->get_level1_id($level1_name);
		
		$subtable = $this->Mpages->subtable_detail($page_id, $subtable_id);
		if($subtable == null) {
			$data['error'] = 'Invalid table-id';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}

		$model = $this->get_model($subtable_id, $level1_id);
		if ($model == null) {
			$data['error'] = 'Invalid table-id';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}
		
		//build params
		$filters = array();

		$action = $this->input->post("action");
		if (empty($action) || $action=='view') {

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_view_table_id($subtable_id)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}
			
			$filter_value = '';
			if (isset($params) && count($params) > 0) {
				$filter_value = $params[0];
			}
			$filters[ $subtable['subtable_fkey_column'] ] = $filter_value;

			$json['data'] = $model->list($filters);

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit_table_id($subtable_id)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_delete_table_id($subtable_id)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_add_table_id($subtable_id)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit_table_id($subtable_id)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

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

	protected function get_model($table_id, $level1_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_id, true, static::$LEVEL1_COLUMN, $level1_id)) {
			return null;
		}

		// if (!$this->Mtable->init($table_id, true)) {
		// 	return null;
		// }

		// if (static::$LEVEL1_COLUMN != null) {
		// 	$this->Mtable->set_level1_filter(static::$LEVEL1_COLUMN, $level1_id);
		// }

		return $this->Mtable;
	}

	protected function get_navigation() {
		$this->load->model('crud/Mnavigation');

		return $this->Mnavigation->get_navigation($this->session->userdata('role_id'), static::$PAGE_GROUP);
	}
}
