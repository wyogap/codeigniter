<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lookup extends CI_Controller {

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

	public function index()
	{
		theme_404();
	}

	public function wilayah()
    {
        $target = intval($this->input->get("target"));
        $level = intval($this->input->get("level"));
        $top = intval($this->input->get("top"));
        $val = $this->input->get("value");

        $data['level'] = $target;
        switch($target) {
            case 0: $data['levelName'] = 'negara'; 
                    $data['levelTitle'] = 'Indonesia'; 
                    break;
            case 1: $data['levelName'] = 'provinsi'; 
                    $data['levelTitle'] = 'Provinsi'; 
                    break;
            case 2: $data['levelName'] = 'kabupaten'; 
                    $data['levelTitle'] = 'Kabupaten/Kota'; 
                    break;
            case 3: $data['levelName'] = 'kecamatan'; 
                    $data['levelTitle'] = 'Kecamatan'; 
                    break;
            case 4: $data['levelName'] = 'desa'; 
                    $data['levelTitle'] = 'Desa/Kelurahan'; 
                    break;
        }

        $this->load->model(array('lookup/Mwilayah'));
        
        if ($top) {
            $data['result'] = $this->Mwilayah->list($target);
        }
        else if ($target > $level) {
            $data['result'] = $this->Mwilayah->cascade_down($target, $val);
        } 
        else {
            $data['result'] = $this->Mwilayah->list($target, $val);
        }

        if ($target < $level) {
            $detail = $this->Mwilayah->detail($val, $target);
            if ($detail != null) {
                $val = $detail['kode_wilayah'];
            } else {
                $val = null;
            }
        }
        
        $data['value'] = $val;
        //var_dump($data['detail']);

        $this->load->view('lookup/wilayah', $data);       
    }

}
