<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Pengadaan extends Base_Crud {
    protected static $PAGE_NAME = "pengadaan";

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

        $this->load->model('disbekal/Mpo');
        $status = $this->Mpo->approve($id);
        if ($status) {
            $json['status'] = 1;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mpo->get_error_message();
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

        $this->load->model('disbekal/Mpo');
        $status = $this->Mpo->close($id);
        if ($status) {
            $json['status'] = 1;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mpo->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function buattender()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');

        //controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

        $json = array();

        $this->load->model('disbekal/Mpo');
        $poid = $this->Mpo->buattender($id);
        if ($poid) {
            $json['status'] = 1;
            $json['editurl'] = site_url() .$controller. "/tender/edit/" .$poid;
            $json['viewurl'] = site_url() .$controller. "/tender/detail/" .$poid;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mpo->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }  

    public function buatkontrak()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');

        //controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

        $json = array();

        $this->load->model('disbekal/Mpo');
        $poid = $this->Mpo->buatkontrak($id);
        if ($poid) {
            $json['status'] = 1;
            $json['editurl'] = site_url() .$controller. "/kontrak/edit/" .$poid;
            $json['viewurl'] = site_url() .$controller. "/kontrak/detail/" .$poid;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mpo->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    public function buatperintahterima()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');

        //controller name
		if (!empty($this->session->userdata('page_role'))) {
			$controller = $this->session->userdata('page_role');
		}
		else {
			$controller = $this->router->class;
		}
		$page_data['controller'] = $controller;

        $json = array();

        $this->load->model('disbekal/Mpo');
        $poid = $this->Mpo->buatperintahterima($id);
        if ($poid) {
            $json['status'] = 1;
            $json['editurl'] = site_url() .$controller. "/perintahterima/edit/" .$poid;
            $json['viewurl'] = site_url() .$controller. "/perintahterima/detail/" .$poid;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mpo->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }      
}
