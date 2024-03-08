<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Crud_Controller.php');

class Base_Crud extends MY_Crud_Controller {

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect(site_url() .'/auth');
		}		
    }

    function get_ajax_url($page) {
        return site_url('/' .$this->router->class .$page);
    }

    public function index($params = array())
	{
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['page_description']       = null;
		$page_data['page_tag']       		 = null;
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = $this->session->userdata('page_role');

		$this->load->model(array('crud/Mnavigation'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$this->smarty->render_theme('user/home.tpl', $page_data);
    }

    // public function index($params = array())
	// {
    //     $page_role = $this->session->userdata('page_role');
        
    //     //TODO: Dashboard/
    //     redirect(site_url() ."/$page_role/home");
    // }

}
