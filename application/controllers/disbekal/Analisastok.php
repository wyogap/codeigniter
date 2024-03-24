<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analisastok extends CI_Controller 
{
    protected static $PAGE_NAME = "analisastok";

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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_timeseries($id, $siteid, $year);
        if ($data != null) {
            $json['status'] = 1;
            $json['data'] = $data;
        } 
        else {
            $json['status'] = 0;
            $json['message'] = $this->Manalisastok->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    function bekalmasuk() {
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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_bekalmasuk($id, $siteid, $year);
        if ($data !== null) {
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
            $json['message'] = $this->Manalisastok->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    function bekalkeluar() {
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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_bekalkeluar($id, $siteid, $year);
        if ($data !== null) {
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
            $json['message'] = $this->Manalisastok->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    function bekaltransfer() {
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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_bekaltransfer($id, $siteid, $year);
        /** IMPORTANT => Empty array is treated like NULL */
        if ($data !== null) {
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
            $json['message'] = $this->Manalisastok->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    function hapusbekal() {
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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_hapusbekal($id, $siteid, $year);
        if ($data !== null) {
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
            $json['message'] = $this->Manalisastok->get_error_message();
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

        $this->load->model('disbekal/Manalisastok');
        $data = $this->Manalisastok->get_stock($id, $siteid, $year);
        if ($data !== null) {
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
            $json['message'] = $this->Manalisastok->get_error_message();
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	

    }

    
}
