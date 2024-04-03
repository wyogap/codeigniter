<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Klasifikasi extends Base_Crud {
	protected static $PAGE_NAME = 'klasifikasi';

    public function _remap($method, $params = array()) {
        return $this->index($params);
    }

	public function index($params = array())
	{
        $name = static::$PAGE_NAME;

		//navigation
		$navigation = $this->get_navigation();

        //check for permission
		$this->load->model(array('crud/Mpages', 'crud/Mpermission'));
		if (!$this->Mpermission->can_view($name)) {
			if (isset($params) && count($params) > 0 && ($params[0] == 'lookup' || $params[0] == 'json')) {
                $this->json_not_authorized();
			}
			theme_403_with_navigation($navigation);		//not-authorized
			return;
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			if (isset($params) && count($params) > 0 && ($params[0] == 'lookup' || $params[0] == 'json')) {
                $this->json_invalid_page();
			}
			theme_404_with_navigation($navigation);
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
		$page_data['use_datatable'] = 1;
        $page_data['use_editor'] = 1;
        $page_data['use_select2'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		
        $crud = array();

        //crud pages
        $model = $this->get_model_byname('tcg_category');
        if ($model == null) {
            theme_404_with_navigation($navigation);
            return;
        }
	
		$tablemeta = $model->tablemeta();

		//pass on the GET params
		if (count($this->input->get()) > 0) {
			$page_data['get_params'] = http_build_query($this->input->get());
		}

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .'crud/kelasbekal';

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

		//easy access for everything
		$crud[]			 = $tablemeta; 

        //no subtables
        $page_data['subtables'] = array();

        //crud pages
        $model = $this->get_model_byname('tcg_itemtype');
        if ($model == null) {
            theme_404_with_navigation($navigation);
            return;
        }
	
		$tablemeta = $model->tablemeta();

		//pass on the GET params
		if (count($this->input->get()) > 0) {
			$page_data['get_params'] = http_build_query($this->input->get());
		}

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .'crud/tipebekal';

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

		//easy access for everything
		$crud[]			 = $tablemeta; 

		//easy access for everything
		$page_data['crud']			 = $crud; 

        //permission
		$permissions = array (
			'allow_edit'	=> $this->Mpermission->can_edit($name)
		);
		$page_data['permissions']	= $permissions;

        // $this->smarty->render_theme('sistem/home.tpl', $page_data);
		$this->smarty->render_theme('/disbekal/catalog/klasifikasi.tpl', $page_data);
	}

	protected function get_model_byname($table_name) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_name, false)) {
			return null;
		}

		return $this->Mtable;
	}


}
