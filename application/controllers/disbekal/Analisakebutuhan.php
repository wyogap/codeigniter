<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analisakebutuhan extends CI_Controller 
{
    protected static $PAGE_NAME = "analisakebutuhan";

    function timeseries() {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');
        if (empty($id)) {
            $id = $this->input->get('id');
        }
        $year = $this->input->post('year');
        if (empty($year)) {
            $year = $this->input->get('year');
        }
        $siteid = $this->input->post('siteid');
        if (empty($year)) {
            $siteid = $this->input->get('siteid');
        }

        //default tahun anggaran
        if (empty($year)) {
            $year = date('Y');
        }

        $json = array();

        $this->load->model('disbekal/Manalisakebutuhan');
        $data = $this->Manalisakebutuhan->get_timeseries($id, $siteid, $year);
        if ($data != null) {
            $json['status'] = 1;
            $json['data'] = $data;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Manalisakebutuhan->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    function demand() {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');
        if (empty($id)) {
            $id = $this->input->get('id');
        }
        $year = $this->input->post('year');
        if (empty($year)) {
            $year = $this->input->get('year');
        }
        $siteid = $this->input->post('siteid');
        if (empty($year)) {
            $siteid = $this->input->get('siteid');
        }

        //default tahun anggaran
        if (empty($year)) {
            $year = date('Y');
        }

        $json = array();

        $this->load->model('disbekal/Manalisakebutuhan');
        $data = $this->Manalisakebutuhan->get_demand($id, $siteid, $year);
        if ($data != null) {
            $json['status'] = 1;
            $json['data'] = $data;

            $total = 0;
            foreach($data as $idx => $row) {
                $total += $row['count'];
            }
            $json['total'] = $total;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Manalisakebutuhan->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    function po() {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');
        if (empty($id)) {
            $id = $this->input->get('id');
        }
        $year = $this->input->post('year');
        if (empty($year)) {
            $year = $this->input->get('year');
        }

        //default tahun anggaran
        if (empty($year)) {
            $year = date('Y');
        }

        $json = array();

        $this->load->model('disbekal/Manalisakebutuhan');
        $data = $this->Manalisakebutuhan->get_po($id, null, $year);
        if ($data != null) {
            $json['status'] = 1;
            $json['data'] = $data;

            $total = 0;
            foreach($data as $idx => $row) {
                $total += $row['count'];
            }
            $json['total'] = $total;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Manalisakebutuhan->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    function stock() {
        $this->load->model(array('crud/Mpermission'));
        if (!$this->Mpermission->can_custom1(static::$PAGE_NAME)) {
            $data['status'] = 0;
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
            return;
        }

        $id = $this->input->post('id');
        if (empty($id)) {
            $id = $this->input->get('id');
        }
        $year = $this->input->post('year');
        if (empty($year)) {
            $year = $this->input->get('year');
        }
        $siteid = $this->input->post('siteid');
        if (empty($year)) {
            $siteid = $this->input->get('siteid');
        }

        //default tahun anggaran
        if (empty($year)) {
            $year = date('Y');
        }

        $json = array();

        $this->load->model('disbekal/Manalisakebutuhan');
        $data = $this->Manalisakebutuhan->get_stock($id, $siteid, $year);
        if ($data != null) {
            $json['status'] = 1;
            $json['data'] = $data;

            $total = 0;
            foreach($data as $idx => $row) {
                $total += $row['count'];
            }
            $json['total'] = $total;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Manalisakebutuhan->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    
}
