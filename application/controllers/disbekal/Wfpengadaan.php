<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Json.php');

class Wfpengadaan extends Base_Json {

    public function approvepo() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));
        
        $json = array();
        $data = $this->Mpo->approve($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mpo->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function createtender() {
        $values = $this->input->post("data");
        if (empty($values)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));

        $tendernum = ""; $startdate = ""; $enddate = "";

        $json = array();
        $json['data'] = array();
        $json['status'] = 1;
        foreach ($values as $key => $valuepair) {
            $tendernum = (empty($valuepair['tendernum']) ? 'TD000' : $valuepair['tendernum']);
            $startdate = (empty($valuepair['startdate']) ? date('Y-m-d') : $valuepair['startdate']);
            $enddate = (empty($valuepair['startdate']) ? '' : $valuepair['enddate']);

            //asumption -> no multi insert
            break;
        }        

        $data = $this->Mpo->createtender($key, $tendernum, $startdate, $enddate);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = "[" .$key. "] " .$this->Mpo->get_error_message(). ". ";
        }
        else {
            $json['data'][] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function completetender() {
        $values = $this->input->post("data");
        if (empty($values)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));

        $id = 0; $vendorid = ""; $quotationvalue = 0; $tenderid = 0;

        $json = array();
        $json['data'] = array();
        $json['status'] = 1;
        foreach ($values as $key => $valuepair) {
            $id = $key;
            $tenderid = (empty($valuepair['tenderid']) ? null : $valuepair['tenderid']);
            $vendorid = (empty($valuepair['tender_vendorid']) ? null : $valuepair['tender_vendorid']);
            $quotationvalue = (empty($valuepair['quotationvalue']) ? 0 : $valuepair['quotationvalue']);

            //asumption -> no multi insert
            break;
        }        

        $data = $this->Mpo->completetender($id, $tenderid, $vendorid, $quotationvalue);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = "[" .$id. "] " .$this->Mpo->get_error_message(). ". ";
        }
        else {
            $json['data'][] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }    

    public function createcontract() {
        $values = $this->input->post("data");
        if (empty($values)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));

        $id = 0; $contractnum = ""; $contractdate = ""; $vendorid = null; $quotationvalue = 0; $tenderid = null;

        $json = array();
        $json['data'] = array();
        $json['status'] = 1;
        foreach ($values as $key => $valuepair) {
            $id = $key;
            $tenderid = (empty($valuepair['tenderid']) ? null : $valuepair['tenderid']);
            $contractnum = (empty($valuepair['contractnum']) ? null : $valuepair['contractnum']);
            $contractdate = (empty($valuepair['contractdate']) ? date('Y-m-d') : $valuepair['contractdate']);
            $vendorid = (empty($valuepair['vendorid']) ? null : $valuepair['vendorid']);
            $quotationvalue = (empty($valuepair['quotationvalue']) ? 0 : $valuepair['quotationvalue']);

            //asumption -> no multi insert
            break;
        }        

        if (empty($tenderid)) {
            //penunjukan langsung
            $data = $this->Mpo->createcontract($id, $contractnum, $contractdate, $vendorid, $quotationvalue);
        }
        else {
            //proses tender
            $data = $this->Mpo->createcontractfromtender($id, $tenderid, $contractnum, $contractdate);
        }

        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = "[" .$id. "] " .$this->Mpo->get_error_message(). ". ";
        }
        else {
            $json['data'][] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approvecontract() {
        $id = $this->input->post("id");
        $contractid = $this->input->post("contractid");
        if (empty($id) || empty($contractid)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));
        
        $json = array();
        $data = $this->Mpo->approvecontract($id, $contractid);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mpo->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function createdo() {
        $values = $this->input->post("data");
        if (empty($values)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));

        $id = 0; $contractnum = ""; $contractdate = ""; $vendorid = null; $quotationvalue = 0; $tenderid = null;

        $json = array();
        $json['data'] = array();
        $json['status'] = 1;
        foreach ($values as $key => $valuepair) {
            $id = $key;
            $contractid = (empty($valuepair['contractid']) ? null : $valuepair['contractid']);
            $donum = (empty($valuepair['donum']) ? null : $valuepair['donum']);
            $dodate = (empty($valuepair['dodate']) ? date('Y-m-d') : $valuepair['dodate']);
            $storeid = (empty($valuepair['storeid']) ? null : $valuepair['storeid']);
            $targetdeliverydate = (empty($valuepair['targetdeliverydate']) ? date('Y-m-d') : $valuepair['targetdeliverydate']);

            //asumption -> no multi insert
            break;
        }        

        $data = $this->Mpo->createdofromcontract($id, $contractid, $donum, $dodate, $storeid, $targetdeliverydate);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = "[" .$id. "] " .$this->Mpo->get_error_message(). ". ";
        }
        else {
            $json['data'][] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function approvedo() {
        $id = $this->input->post("id");
        $doid = $this->input->post("doid");
        if (empty($id) || empty($doid)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));
        
        $json = array();
        $data = $this->Mpo->approvedo($id, $doid);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mpo->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function closepo() {
        $id = $this->input->post("id");
        if (empty($id)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));
        
        $json = array();
        $data = $this->Mpo->close($id);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mpo->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function resetpo() {
        $id = $this->input->post("id");
        $state = $this->input->post("state");
        if (empty($id) || empty($state)) {
            $this->json_invalid_page();
        }

        $this->load->model(array('disbekal/Mpo'));
        
        $json = array();
        $data = $this->Mpo->reset($id,$state);
        if ($data == null || $data == 0) {
            $json['status'] = 0;
            $json['error'] = $this->Mpo->get_error_message();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }



}
