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
			redirect(base_url() .'auth');
		}
    }

    function get_ajax_url($table) {
        return base_url('/crud/' .$table. '/json');
    }

}
