<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select extends CI_Controller {

    public function manufacturer()
    {
        $q = $this->input->get('q');

        $this->load->model('disbekal/Lookup');
        //TODO

        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    public function vendor()
    {
        $q = $this->input->get('q');

        $this->load->model('disbekal/Lookup');
        //TODO

        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    public function store()
    {
        $q = $this->input->get('q');

        $this->load->model('disbekal/Mselect');
        $data = $this->Mselect->store();
        if ($data != null) {
            $json['data'] = $data;
        }

        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    public function location()
    {
        $storeid = $this->input->get('s');    //store-id
        $q = $this->input->get('q');

        $this->load->model('disbekal/Lookup');
        //TODO

        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }
    
    public function category()
    {
        $level = $this->input->get('l');    //level
        $parentid = $this->input->get('p');    //parent-id
        $q = $this->input->get('q');

        //$this->load->model('disbekal/Lookup');
        //TODO

        $json['level'] = $level;
        $json['parentid'] = $parentid;
        $json['status'] = 1;
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    public function tipebekal()
    {
        $this->load->model('disbekal/Mselect');

        $json = array();
        $json['status'] = 1;
        $json['data'] = $this->Mselect->tipebekal();
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function satuankerja()
    {
        $this->load->model('disbekal/Mselect');

        $json = array();
        $json['status'] = 1;
        $json['data'] = $this->Mselect->satuankerja();
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
}
