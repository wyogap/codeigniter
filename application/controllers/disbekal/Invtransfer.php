<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invtransfer extends CI_Controller 
{
    protected static $PAGE_NAME = "inv_transfer_planned";

    public function approve()
    {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $ids = $this->input->post('ids');
        $ids = explode(",", $ids);

        $this->load->model('disbekal/Minventory');
        $cnt = $this->Minventory->approve_transfer($ids);

        $json['status'] = 1;
        $json['count'] = $cnt;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }


    
}
