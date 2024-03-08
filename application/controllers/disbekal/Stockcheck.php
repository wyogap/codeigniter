<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockcheck extends CI_Controller {

    protected static $PAGE_NAME = "stock_check";

    public function approve()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $id = $this->input->post('id');

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->approve_stockcheck($id);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function generate() 
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_edit(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        //one-by-one. dont do in bulk
        $id = $this->input->post('id');

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->generate_stockcheck($id);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    
}
