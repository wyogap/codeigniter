<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invstatus extends CI_Controller 
{
    protected static $PAGE_NAME = "stock_status";

    public function writeoff()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
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

    
}
