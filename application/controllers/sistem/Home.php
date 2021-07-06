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
			redirect(site_url() .'/auth');
			return;
		}
		
		$this->load->model(array('crud/Mpermission'));
		if (!$this->Mpermission->is_admin()) {
			redirect(site_url() .'/auth/notauthorized');
			return;
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
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = 'admin';

		$this->load->model(array('crud/Mnavigation', 'bpkad/Mdashboard'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$page_data['total'] = 0;
		$page_data['terverifikasi'] = 0;
		$page_data['perlu_verifikasi'] = 0;

		$total = $this->Mdashboard->kendaraan_total();
		if ($total != null) {
			$page_data['total'] = $total['total'];
			$page_data['terverifikasi'] = $total['terverifikasi'];
			$page_data['perlu_verifikasi'] = $total['perlu_verifikasi'];
		}

		$page_data['per_jenis_kendaraan'] = $this->Mdashboard->kendaraan_per_jenis_kendaraan();
		$page_data['per_peruntukan'] = $this->Mdashboard->kendaraan_per_peruntukan();
		
		//$page_data['per_opd'] = $this->Mdashboard->kendaraan_per_opd();

		$this->smarty->render_theme('admin/home.tpl', $page_data);
	}

	public function json() {
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

	public function per_opd() {
		$this->load->model(array('bpkad/Mdashboard'));
		$page_data['data'] = $this->Mdashboard->kendaraan_per_opd();
		echo json_encode($page_data, JSON_INVALID_UTF8_IGNORE);	
	}

}
