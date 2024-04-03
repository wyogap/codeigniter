<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Kebutuhan extends Base_Crud {
    protected static $PAGE_NAME = "kebutuhan";

    public function approve()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');

        $json = array();

        $this->load->model('disbekal/Mdemand');
        $detail = $this->Mdemand->approve($id);
        if ($detail) {
            $json['status'] = 1;
            $json['data'] = $detail;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdemand->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function close()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');

        $json = array();

        $this->load->model('disbekal/Mdemand');
        $status = $this->Mdemand->close($id);
        if ($status) {
            $json['status'] = 1;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdemand->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function buatpengadaan()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');
        $ponum = $this->input->post('ponum');
        if (empty($ponum)) {
            $ponum = "";
        }
        $year = $this->input->post('year');
        if (empty($year)) {
            $year = date('Y');
        }

        //controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

        $json = array();

        $this->load->model('disbekal/Mdemand');
        $poid = $this->Mdemand->buatpengadaan($id, $year, $ponum);
        if ($poid) {
            $json['status'] = 1;
            $json['editurl'] = site_url() .$controller. "/pengadaan/edit/" .$poid;
            $json['viewurl'] = site_url() .$controller. "/pengadaan/detail/" .$poid;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdemand->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    
}
