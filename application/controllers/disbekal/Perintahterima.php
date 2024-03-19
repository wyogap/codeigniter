<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Perintahterima extends Base_Crud {
    protected static $PAGE_NAME = "perintahterima";

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

        $this->load->model('disbekal/Mdo');
        $status = $this->Mdo->approve($id);
        if ($status) {
            $json['status'] = 1;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdo->get_error_message();
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

        $this->load->model('disbekal/Mdo');
        $status = $this->Mdo->close($id);
        if ($status) {
            $json['status'] = 1;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdo->get_error_message();
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

        $this->load->model('disbekal/Mdo');
        $doid = $this->Mdo->buatperintahterima($id);
        if ($doid   ) {
            $json['status'] = 1;
            $json['editurl'] = site_url() .$controller. "/perintahterima/edit/" .$doid;
            $json['viewurl'] = site_url() .$controller. "/perintahterima/detail/" .$doid;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Mdo->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    
}
