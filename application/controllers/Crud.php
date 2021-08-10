<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Crud_Controller.php');

class Crud extends MY_Crud_Controller {

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
        return site_url('/crud/' .$page);
    }

    public function index($params = array())
	{
        $page_role = $this->session->userdata('page_role');
        
        //TODO: Dashboard/
        redirect(site_url() ."/$page_role/home");
    }

}
