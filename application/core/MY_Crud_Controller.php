<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Crud_Controller extends CI_Controller {
	protected static $PAGE_GROUP = null;
	protected static $AUTHENTICATED = true;

	//abstract function get_ajax_url($table);

	abstract function index($params = array());

    public function __construct()
    {
        parent::__construct();

		// //authentication check moved to _remap() so that we can send proper json error message 
		// $isLoggedIn = $this->session->userdata('is_logged_in');
		// if (static::$AUTHENTICATED && (!isset($isLoggedIn) || $isLoggedIn != TRUE)) {
		// 	redirect(site_url() .'/auth');
		// }
    }
	
	public function _remap($method, $params = array())
	{
		//must be authenticated? if it is, redirect to login page or send json error message
		$isLoggedIn = $this->session->userdata('is_logged_in');
		if (static::$AUTHENTICATED && (!isset($isLoggedIn) || $isLoggedIn != TRUE)) {
			if (isset($params) && count($params) > 0 && $params[0] == 'json') {
				json_not_login();
			} else {
				redirect(site_url() .'/auth');
			}
		}

		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		$this->table($method, $params);
	}

	protected function table($name = '', $params = array())
	{
		if (empty($name)) {
			$this->index($params);
			return;
		}

		//navigation
		$navigation = $this->get_navigation();
		//$session = $this->get_session();

		//check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission'));
		if (!$this->Mpermission->can_view($name)) {
			theme_403_with_navigation($navigation);		//not-authorized
			return;
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			if (isset($params) && count($params) > 0 && ($params[0] == 'lookup' || $params[0] == 'json')) {
				$json['error'] = "invalid-page";
				echo json_encode($json, JSON_INVALID_UTF8_IGNORE);		
				return;			
			}
			theme_404_with_navigation($navigation);
			return;
		}

		if ($page['page_type']!='custom' && !empty($page['crud_table_id'])) {
			//CRUD page
			if (isset($params) && count($params) > 0 && $params[0] == 'add') {
				unset($params[0]);
				$this->table_add($page, $navigation);
				return;
			}
	
			if (isset($params) && count($params) > 0 && $params[0] == 'edit') {
				unset($params[0]);
	
				//var_dump($params);
	
				$id = array_shift($params);
				if ($id != null)		$this->table_edit($page, $navigation, $id);
				else					$this->table_add($page, $navigation);
				
				return;
			}
										
			if (isset($params) && count($params) > 0 && $params[0] == 'lookup') {
				unset($params[0]);
	
				$model = $this->get_model_for_lookup($page['crud_table_id']);
				if ($model == null) {
					$json['error'] = "invalid-page";
					echo json_encode($json, JSON_INVALID_UTF8_IGNORE);		
					return;			
				}
	
				$this->table_lookup($model, $params);
				return;
			}
	
			//crud pages
			$model = $this->get_model($page['crud_table_id']);
			if ($model == null) {
				theme_404_with_navigation($navigation);
				return;
			}
	
			if (isset($params) && count($params) > 0 && $params[0] == 'detail') {
				unset($params[0]);
				$this->table_detail($page, $model, $navigation, $params);
				return;
			}
	
			if (isset($params) && count($params) > 0 && $params[0] == 'json') {
				unset($params[0]);
				$this->table_json($page['name'], $model, $params);
				return;
			}
	
			if (isset($params) && count($params) > 1 && $params[0] == 'subtable') {
				unset($params[0]);
				$subtable_id = array_shift($params);
				$this->table_subtable($page['id'], $subtable_id, $params);
				return;
			}	
		}

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];
		$page_data['page_description']       = $page['page_description'];
		$page_data['page_tag']       		 = $page['page_tag'];
		$page_data['query_params']           = $params;

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
		//$page_data['session']		 = $session;
		$page_data['userdata']		 = $this->session->userdata();

		//dynamic loading
		if ($page['use_datatable'])			$page_data['use_datatable'] = 1;
		if ($page['use_editor'])			$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

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

		$tablemeta = $model->tablemeta();

		//pass on the GET params
		if (count($this->input->get()) > 0) {
			$page_data['get_params'] = http_build_query($this->input->get());
		}

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $name;

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		if (!empty($page_data['get_params'])) {
			$tablemeta['ajax'] .= '?' .$page_data['get_params'];
		}

		$tablemeta['crud_url'] = $base_ajax_url;

		//var_dump($tablemeta['crud_url']);
		//var_dump($tablemeta["columns"]);
		//var_dump($tablemeta["editor_columns"]);

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

		if ($page_type == 'table') {
			$template = '/crud/table.tpl';
			//if it is crud page, always enable datatable
			$page_data['use_datatable'] = 1;
			$page_data['use_editor'] = 1;
			$page_data['use_select2'] = 1;
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
			//if it is crud page, always enable datatable
			$page_data['use_datatable'] = 1;
			$page_data['use_editor'] = 1;
			$page_data['use_select2'] = 1;
		}
		else {
			$template = '/crud/table.tpl';
		}

		//easy access for everything
		$page_data['crud']			 = $tablemeta; 

		//var_dump($tablemeta); exit;

		//get subtables if necessary
		//IMPORTANT: since Mpage instance is shared, the following function will modify the tablename inside Mpages
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

		//easy access
		$page_data['subtables']		 = $subtables;

		//get page navigation
		$page_navigations = $this->Mpages->page_navigations($page['id']);
		$page_data['page_navigations'] = $page_navigations;
		
		//permission
		$permissions = array (
			'allow_edit'	=> $this->Mpermission->can_edit($name)
		);
		$page_data['permissions']	= $permissions;

		//custom template
		if (!empty($page['custom_view'])) {
			$template = $page['custom_view'];
		}
	
		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_add($page, $navigation) {
		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404_with_navigation($navigation);		//not-found
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_add($page['name'])) {
			theme_403_with_navigation($navigation);		//not-authorized
			return;
		}

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		//$page_data['page_title']             = $page['page_title'];
		//$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Buat") ." ". $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

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

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['detail'] = null; 
		$page_data['form_mode'] = 'add';

		$template = '/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_edit($page, $navigation, $id=null) {
		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404_with_navigation($navigation);		//not-found
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($navigation);		//not-authorized
			return;
		}

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		
		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Ubah") ." ". $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

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

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['form_mode'] = 'edit';

		$template = '/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_detail($page, $model, $navigation, $params = null) {
		$page_type = $page['page_type'];
		if ($page_type != 'table') {
			theme_404_with_navigation($navigation);		//not-found
			return;
		}

		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($navigation);		//not-authorized
			return;
		}

		$id = array_shift($params);

		//controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		
		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

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

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['form_mode'] = 'detail';

		$template = '/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	// protected function table_detail($page, $model, $navigation, $params = null) {
	// 	if ($params == null || count($params) == 0) {
	// 		theme_404_with_navigation($navigation);
	// 		return;
	// 	}

	// 	$page_name = $page['name'];

	// 	$this->load->model(array('crud/Mpermission'));
	// 	if (!$this->Mpermission->can_view($page_name)) {
	// 		$data['error'] = 'not-authorized';
	// 		echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
	// 		return;
	// 	}

	// 	//$id = $params[0];
	// 	$id = array_shift($params);
	// 	$page_data['detail'] = $model->detail($id);

	// 	//controller name
	// 	if (!empty($this->session->userdata('page_role'))) {
	// 		$controller = $this->session->userdata('page_role');
	// 	}
	// 	else {
	// 		$controller = $this->router->class;
	// 	}
	// 	$page_data['controller'] = $controller;

	// 	$page_data['page_name']              = $page['name'];
	// 	$page_data['query_params']           = $params;
		
	// 	$page_data['page_role']           	 = $this->session->userdata('page_role');

	// 	$page_data['page_header'] 			 = $page['page_header'];
	// 	$page_data['page_footer'] 			 = $page['page_footer'];

	// 	if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
	// 	if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);

	// 	$tablemeta = $model->tablemeta();

	// 	$page_data['page_title']             = __("Ubah") ." ". $tablemeta['title'];
	// 	$page_data['page_icon']              = '';

	// 	//ajax url for data loading
	// 	//Important: we only provide the base path! ie. /crud/page-name
	// 	$base_ajax_url = site_url() .'/'. $controller .'/'. $page_name;

	// 	$tablemeta['ajax'] = $base_ajax_url .'/json';
	// 	$tablemeta['crud_url'] = $base_ajax_url;

	// 	//easy access
	// 	$page_data['table_meta']			 = $tablemeta; 
	// 	$page_data['page']			 		 = $page; 
	// 	$page_data['navigation']	 		 = $navigation;

	// 	$page_data['crud']			 = $tablemeta; 

	// 	// $custom_view = $model->custom_view();
	// 	// if (empty($custom_view)) {
	// 	// 	$custom_view = 'crud/form.tpl';
	// 	// }

	// 	$custom_view = '/crud/form.tpl';

	// 	//permission
	// 	$permissions = array (
	// 		'allow_edit'	=> $this->Mpermission->can_edit($page_name)
	// 	);
	// 	$page_data['permissions']	= $permissions;
		
	// 	//if it is crud page, always enable datatable
	// 	$page_data['use_datatable'] = 1;
	// 	$page_data['use_editor'] = 1;
	// 	$page_data['use_select2'] = 1;

	// 	//echo json_encode($page_data);
	// 	$this->smarty->render_theme($custom_view, $page_data);
	// }

	protected function table_json($page_name, $model, $params = null) {
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

		//query string
		$search = '';
		if (!empty($this->input->post('search'))) {
			$search = $this->input->post('search');
		}
		if (!empty($this->input->get('search'))) {
			$search = $this->input->post('search');
		}
		$search = trim($search);

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
			
			if (empty($search)) {
				$json['data'] = $model->list($filters);
			}
			else {
				$json['data'] = $model->search($search, $filters);
			}
            

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){

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
				$data['status'] = 0;
                $data['error'] = implode(', ', $errors);
            } else {
				$data['status'] = 1;
				$data['affected'] = count($data['data']);
			}
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='remove') {

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
				$data['status'] = 0;
                $data['error'] = $error_msg;
            } else {
				$data['status'] = 1;
				$data['affected'] = count($values);
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='create') {

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_add($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$values = $this->input->post("data");

            $key = $model->add($values[0], $filters);
            if ($key == 0) {
				$data['status'] = 0;
				$data['error'] = $model->get_error_message();
            } else {
				$data['status'] = 1;
				$data['data'] = [];
				$detail = $model->detail($key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }
        else if ($action == "upload") {

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

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_edit($page_name) || !$this->Mpermission->can_add($page_name)) {
				$data['error'] = 'not-authorized';
				echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
				return;
			}

			$status = $model->import($_FILES['upload']);

            if($status == 0) {
				$data['status'] = 0;
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

	protected function table_lookup($model, $params = null) {
			
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
		
		$page_name = $subtable['subtable_name'];

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

			$filter_value = '';
			if (isset($params) && count($params) > 0) {
				$filter_value = $params[0];
			}

			$error_msg = "";
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				//enforce the parent key
				$valuepair[ $subtable['subtable_fkey_column'] ] = $filter_value;

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
        else if ($action == "import") {

			$this->load->model(array('crud/Mpermission'));
			if (!$this->Mpermission->can_add_table_id($subtable_id) || !$this->Mpermission->can_edit_table_id($subtable_id)) {
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

	protected function get_model($table_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_id, true)) {
			return null;
		}

		return $this->Mtable;
	}

	protected function get_model_for_lookup($table_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init_for_lookup($table_id, true)) {
			return null;
		}

		return $this->Mtable;
	}

	protected function get_navigation() {
		$this->load->model('crud/Mnavigation');
		return $this->Mnavigation->get_navigation($this->session->userdata('role_id'), static::$PAGE_GROUP);
	}

	protected function get_session() {
		$this->load->model('crud/Msession');
		$sessions = $this->Msession->get_session();

		for($i=0; $i<count($sessions); $i++) {
			$sess = $sessions[$i];

			$sess_name = $sess['name'] . '-label';
			if (!empty($this->session->userdata($sess_name))) {
				$sess['value'] = $this->session->userdata($sess_name);
			}
			else if (!empty($this->session->userdata($sess['name']))) {
				$sess['value'] = $this->session->userdata($sess['name']);
			}
			else if ($sess['optional'] == 0) {
				if (!empty($sess['label_for_none'])) {
					$sess['value'] = $sess['label_for_none'];
				}
				else {
					$sess['value'] = 'ALL';
				}
			}
			else {
				$sess['value'] = null;
			}

			$sessions[$i]['value'] = $sess['value'];
		}

		return $sessions;
	}
}
