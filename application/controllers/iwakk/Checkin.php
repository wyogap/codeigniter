<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// @codeCoverageIgnoreEnd
require_once BASEPATH.'../vendor/autoload.php';

class Checkin extends CI_Controller {
    protected $time_fencing = 1;
    protected $geo_fencing = 1;

	public function index()
	{
        $this->load->model(array('iwakk/Mcheckin'));

        //not open yet
        if ($this->time_fencing) {
            $is_open = $this->Mcheckin->is_open();
            if (!$is_open) {
                $this->notopen(); 
                return;
            }
        } 

        $data['ajax'] = $this->get_ajax_url("json");

        $no_registrasi = $this->input->get("no_registrasi");
        if (isset($no_registrasi) && $no_registrasi != "") {
            $data["no_registrasi"] = $no_registrasi;
        }

        if ($this->geo_fencing) {
            $settings = $this->Mcheckin->settings();
            $data['ref_latitude'] = $settings['ref_latitude'];
            $data['ref_longitude'] = $settings['ref_longitude'];
            $data['rad_latitude'] = $settings['radius_km'] / 110.574;
            $data['rad_longitude'] = $settings['radius_km'] / 111.320;
            $data['kode_checkin'] = $settings['kode_checkin'];
        } else {
            $data['geo_fencing'] = 0;
        }
        
        $this->load->view('iwakk/checkin', $data);
	}
	
    public function notopen() {
        $no_registrasi = $this->input->get("no_registrasi");

        $data = array();
        if (isset($no_registrasi) && !is_empty($no_registrasi)) {
            $status = $this->Mcheckin->detail($no_registrasi);
            if ($status["error"] == 0) {
                $data['nama_lengkap'] = $status['nama_lengkap'];
            }
        }
		$this->load->view('iwakk/notopen', $data);
    }

    public function success()
	{
		$this->load->view('iwakk/success');
	}

	public function update()
	{
		$this->load->view('iwakk/update');
	}

    function get_ajax_url($page) {
        return site_url('/' .$this->router->class .'/'. $page);
    }

    function json() {
        $this->load->model(array('iwakk/Mcheckin'));

        $data = $this->input->post("data");
        $action = $this->input->post("action");;

        if ($action == 'checkin') {
            $no_registrasi = $data['no_registrasi'];

            $status = $this->Mcheckin->checkin($no_registrasi);
            echo json_encode($status, JSON_INVALID_UTF8_IGNORE);
        }
        if ($action == 'update') {
            $id = $data['id'];
            $no_ktp = $data['no_ktp'];
            $no_hp = $data['no_hp'];

            $status = $this->Mcheckin->update($id, $no_ktp, $no_hp);
            echo json_encode($status, JSON_INVALID_UTF8_IGNORE);
        }
    }

 }
