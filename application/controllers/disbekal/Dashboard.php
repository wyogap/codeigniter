<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Json.php');

class Dashboard extends Base_Json {
        
    public function lowstock() {        
        $storeid = $this->input->get('f_storeid');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->lowstock($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
            $json['data'] = array();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
        
    public function rusakkadaluarsa() {        
        $storeid = $this->input->get('f_storeid');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->rusakkadaluarsa($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
            $json['data'] = array();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
        
    public function fastmoving() {        
        $storeid = $this->input->get('f_storeid');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->fastmoving($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
            $json['data'] = array();
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
        
    public function gisdetail() {        
        $siteid = $this->input->get('f_siteid');
        $itemtypeid = $this->input->get('f_itemtypeid');
        $age = $this->input->get('f_age');
        $storeids = $this->input->get('f_storeids');
        $str = $this->input->get('q');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->gisdetail($siteid, $itemtypeid, $age, $storeids, $str);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function gissearch() {        
        $siteid = $this->input->get('f_siteid');
        $itemtypeid = $this->input->get('f_itemtypeid');
        $age = $this->input->get('f_age');
        $str = $this->input->get('q');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->gissearch($siteid, $itemtypeid, $age, $str);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function nilaistok()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->nilaistok($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['nilaistok'] = $data['total'];
            $json['kadaluarsa'] = $data['kadaluarsa'];
            $json['rusak'] = $data['rusak'];    
            //TODO
            $json['hampirkadaluarsa'] = 0;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function itemstok()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->itemstok($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['hampirhabis'] = $data['hampirhabis'];
            $json['kadaluarsa'] = $data['kadaluarsa'];
            $json['rusak'] = $data['rusak'];    
            //TODO
            $json['hampirkadaluarsa'] = 0;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function stokpergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->stokpergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function stokperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->stokperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function kadaluarsapergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->kadaluarsapergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
    
    public function kadaluarsaperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->kadaluarsaperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function rusakpergudang()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->rusakpergudang($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
    
    public function rusakperkategori()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->rusakperkategori($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function perkiraankadaluarsa()
    {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->perkiraankadaluarsa($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function pergerakanbarang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->pergerakanbarang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukperwaktu()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukperwaktu($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukpergudang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukpergudang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangmasukperkategori()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangmasukperkategori($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarperwaktu()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarperwaktu($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarpergudang()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarpergudang($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function barangkeluarperkategori()
    {
        $storeid = $this->input->get('s');
        $periode = $this->input->get('p');
        $offset = $this->input->get('o');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->barangkeluarperkategori($storeid, $periode, $offset);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }

    public function stokpergudangpertahunterima() {
        $storeid = $this->input->get('s');

        $this->load->model('disbekal/Mdashboard');
        
        $json = array();
        $data = $this->Mdashboard->stokpergudangpertahunterima($storeid);
        if ($data == null) {
            $json['status'] = 0;
            $json['message'] = 'No data';
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
    }
}
