<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    protected static $PAGE_NAME = "stock";

    public function stokpergudangpertahun()
    {
        $json = $this->input->get('json');
        $tablemeta = $this->input->get('tablemeta');

        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
			theme_403_with_navigation($navigation);		//not-authorized
			return;
            // $data['status'] = 0;
            // $data['error'] = 'not-authorized';
            // echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            // return;
        }

        //TODO
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
