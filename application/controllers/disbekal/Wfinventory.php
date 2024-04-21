<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Json.php');

class Wfinventory extends Base_Json {
    protected static $PAGE_WRITEOFF = "hapusbuku";
    protected static $PAGE_STOCKIN= "bekalmasuk";
    protected static $PAGE_STOCKOUT = "bekalkeluar";

    public function writeoff()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_WRITEOFF)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $ids = $this->input->post('ids');
        $ids = explode(',', $ids);

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->writeoff($ids);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approvestockin()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_STOCKIN)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $ids = $this->input->post('ids');
        $ids = explode(',', $ids);

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->approve_stockin($ids);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approvestockinall()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_STOCKIN)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->approve_stockin_all();

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function deletestockinall()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_STOCKIN)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $ids = $this->input->post('ids');
        $ids = explode(',', $ids);

        $this->load->model('disbekal/Minventory');
        $this->Minventory->delete_stockin_all($ids);

        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approvestockout()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_STOCKOUT)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $ids = $this->input->post('ids');
        $ids = explode(',', $ids);

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->approve_stockout($ids);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approveur() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Musagerequest'));
        
        $json = array();
        $data = $this->Musagerequest->approve($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Musagerequest->get_error_message();
        }
        else {
            //TODO: generate pdf report
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    public function approvetr() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mtransferrequest'));
        
        $json = array();
        $data = $this->Mtransferrequest->approve($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mtransferrequest->get_error_message();
        }
        else {
            //TODO: generate pdf report
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    public function closetr() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mtransferrequest'));
        
        $json = array();
        $data = $this->Mtransferrequest->close($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mtransferrequest->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    public function closeur() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Musagerequest'));
        
        $json = array();
        $data = $this->Musagerequest->close($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Musagerequest->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    protected function createattachmentur() {
        //TODO
    }
}
