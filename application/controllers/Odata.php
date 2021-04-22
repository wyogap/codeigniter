<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Rest_Controller.php');

class Odata extends MY_Rest_Controller {

    //TODO
    
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


}
