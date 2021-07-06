<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect(site_url() .'/auth');
			return;
		}
		
		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->is_admin()) {
			redirect(site_url() .'/auth/notauthorized');
			return;
		}
    }

	public function index() {
		$action = $this->input->post("action");
		if ($action=='generate_columns') {
			
            $values = $this->input->post("data");

			$this->load->model(array('crud/Mtable'));

			$error_msg = "";
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				$this->Mtable->generate_columns($key);
            }

			$json['status'] = 1;
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}

	}

}
