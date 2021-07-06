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
		$role_id = $this->session->userdata('role_id');
		if ($role_id == 4) {
			redirect(site_url('crud/kendaraan_dinas_pengurus_barang'));
		}		

		$opd = $this->session->userdata('opd');

		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = 'user';

		$this->load->model(array('crud/Mnavigation', 'bpkad/Mdashboard'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$page_data['total'] = 0;
		$page_data['terverifikasi'] = 0;
		$page_data['perlu_verifikasi'] = 0;

		$total = $this->Mdashboard->kendaraan_total_opd($opd);
		if ($total != null) {
			$page_data['total'] = $total['total'];
			$page_data['terverifikasi'] = $total['terverifikasi'];
			$page_data['perlu_verifikasi'] = $total['perlu_verifikasi'];
		}

		$page_data['per_jenis_kendaraan'] = $this->Mdashboard->kendaraan_per_jenis_kendaraan($opd);
		$page_data['per_peruntukan'] = $this->Mdashboard->kendaraan_per_peruntukan($opd);
		
		$this->smarty->render_theme('opd/home.tpl', $page_data);
	}

	public function per_opd() {
		$opd = $this->session->userdata('opd');

		$this->load->model(array('bpkad/Mdashboard'));
		$page_data['data'] = $this->Mdashboard->kendaraan_per_opd($opd);
		echo json_encode($page_data, JSON_INVALID_UTF8_IGNORE);	
	}

}
