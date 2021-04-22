<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		redirect(base_url('crud/kendaraan_dinas_opd'));

		// $page_data['page_name']              = 'home';
		// $page_data['page_title']             = 'Home';
		// $page_data['page_icon']              = "mdi-view-dashboard-outline";
		// $page_data['query_params']           = null;

		// $page_data['page_role']           	 = 'user';

		// $this->load->model(array('crud/Mnavigation'));
		// $navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		// $page_data['navigation']	 = $navigation;

		// $this->smarty->render_theme('user/home.tpl', $page_data);
	}


}
