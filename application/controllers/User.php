<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class User extends Base_Crud {

	public function index($params = array())
	{
		$this->home($params);
	}

	public function home() {
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['page_description']       = null;
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = $this->session->userdata('page_role');;

		$this->load->model(array('crud/Mnavigation'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$page_data['use_datatable'] = 1;
		$page_data['use_geo'] = 1;
		$page_data['use_select2'] = 1;
		
		// $this->smarty->render_theme('sistem/home.tpl', $page_data);
		$this->smarty->render_theme('/disbekal/inventory/pencarian.tpl', $page_data);
	}

}
