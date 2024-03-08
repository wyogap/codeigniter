<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
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

	public function index() {
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['page_description']       = null;
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = 'admin';

		$this->load->model(array('crud/Mnavigation'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$page_data['use_geo'] = 1;
		
		$this->smarty->render_theme('disbekal/kadis/dashboard.tpl', $page_data);
	}
    
    public function nilaistok()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->nilaistok($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['nilaistok'] = $data['total'];
            $json['kadaluarsa'] = $data['kadaluarsa'];
            $json['rusak'] = $data['rusak'];    
            //TODO
            $json['hampirkadaluarsa'] = 0;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function itemstok()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->itemstok($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['hampirhabis'] = $data['hampirhabis'];
            $json['kadaluarsa'] = $data['kadaluarsa'];
            $json['rusak'] = $data['rusak'];    
            //TODO
            $json['hampirkadaluarsa'] = 0;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function stokpergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->stokpergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function stokperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->stokperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function kadaluarsapergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->kadaluarsapergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
    
    public function kadaluarsaperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->kadaluarsaperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function rusakpergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->rusakpergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
    
    public function rusakperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->rusakperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function perkiraankadaluarsa()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->perkiraankadaluarsa($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function pergerakanbarang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->pergerakanbarang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukperwaktu()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukperwaktu($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukpergudang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukpergudang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukperkategori()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukperkategori($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarperwaktu()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarperwaktu($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarpergudang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarpergudang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarperkategori()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarperkategori($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

}
