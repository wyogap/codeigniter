<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// @codeCoverageIgnoreEnd
require_once BASEPATH.'../vendor/autoload.php';

class Stin extends CI_Controller {

    public function index() {
        echo "STIN";
    }

	public function get_token()
	{
        $this->load->model(array('stin/Mpsi'));

        $url = "https://services.puspsi.com/api/data/get-token";

        $curl = curl_init();

        $fp = fopen('c://temp//errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
           "Accept: application/json",
           "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $resp = curl_exec($curl);        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            echo curl_errno($curl). ' :: '. curl_error($curl);
            exit();
        }
        curl_close($curl);
    
        $json = json_decode($resp);
        if (empty($json)) {
            echo 'INVALID RESPONSE';
            exit();
        }

        $token = $json->token;
        if (!empty($token)) {
            $this->Mpsi->simpan_token($token);
            echo $token;
        }
        else {
            echo 'INVALID TOKEN';
        }
	}
	
    public function simpan_peserta() 
    {
        $this->load->model(array('stin/Mpsi'));

        $url = "https://services.puspsi.com/api/peserta/simpan-peserta";
        
        $curl = curl_init();

        $fp = fopen('c://temp//errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $token = $this->Mpsi->get_token(); 
        $headers = array(
           "Accept: application/json",
           "Content-Type: application/json",
           "Authorization: Bearer ".$token
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $data = $this->Mpsi->list_psi();
        $json = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        
        $json = json_decode($resp);
        if (empty($json)) {
            echo 'INVALID RESPONSE';
            exit();
        }

        $jumlah_data = $json->jumlah_data;
        if (!empty($jumlah_data) && $jumlah_data == count($data)) {
            echo "BERHASIL: JUMLAH DATA DIPROSES (" .$jumlah_data. ") == JUMLAH DATA DIKIRIM (" .count($data). ")";
            exit();
        }

        echo "TIDAK BERHASIL: JUMLAH DATA DIPROSES (" .$jumlah_data. ") != JUMLAH DATA DIKIRIM (" .count($data). ")";
    }

    public function get_hasil_psi() {
        $this->load->model(array('stin/Mpsi'));

        $url = "https://services.puspsi.com/api/peserta/hasil-tes";

        $curl = curl_init();

        $fp = fopen('c://temp//errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $token = $this->Mpsi->get_token(); 
        $headers = array(
           "Accept: application/json",
           "Content-Type: application/json",
           "Authorization: Bearer ".$token
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $resp = curl_exec($curl);        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            echo curl_errno($curl). ' :: '. curl_error($curl);
            exit();
        }
        curl_close($curl);
    
        $json = json_decode($resp);
        if (empty($json)) {
            echo 'INVALID RESPONSE';
            exit();
        }

        $data = isset($json->data) ? $json->data : null;
        if (empty($data)) {
            echo "TIDAK BERHASIL: " .$json->message;
            exit();
        }

        $jml_disimpan = 0;
        $jml_disarankan = 0;
        $jml_dipertimbangkan = 0;
        $jml_tidak_disarankan = 0;

        if (count($data) > 0) {
            $jml_disimpan = $this->Mpsi->simpan_hasil($data);
        }

        if($jml_disimpan != count($data)) {
            echo "TIDAK BERHASIL: JUMLAH DATA DISIMPAN (" .$jml_disimpan. ") != JUMLAH DATA DIKIRIM (" .count($data). ")";
            exit();
        }

        $jml_disarankan = $this->Mpsi->get_jumlah_disarankan();
        if($jml_disarankan != $json->jumlah_disarankan) {
            echo "TIDAK KONSISTEN (JUMLAH DISARANKAN): JUMLAH DATA DISIMPAN (" .$jml_disarankan. ") != JUMLAH DATA DI HEADER (" .$json->jumlah_disarankan. ")";
            exit();
        }

        $jml_dipertimbangkan = $this->Mpsi->get_jumlah_dipertimbangkan();
        if($jml_dipertimbangkan != $json->jumlah_dipertimbangkan) {
            echo "TIDAK KONSISTEN (JUMLAH DISARANKAN): JUMLAH DATA DISIMPAN (" .$jml_dipertimbangkan. ") != JUMLAH DATA DI HEADER (" .$json->jumlah_dipertimbangkan. ")";
            exit();
        }

        $jml_tidak_disarankan = $this->Mpsi->get_jumlah_tidak_disarankan();
        if($jml_tidak_disarankan != $json->jumlah_tidak_disarankan) {
            echo "TIDAK KONSISTEN (JUMLAH DISARANKAN): JUMLAH DATA DISIMPAN (" .$jml_tidak_disarankan. ") != JUMLAH DATA DI HEADER (" .$json->jumlah_tidak_disarankan. ")";
            exit();
        }

        echo "BERHASIL.<br>";
        echo "JUMLAH DISIMPAN: " .$jml_disimpan. "<br>";
        echo "JUMLAH DISARANKAN: " .$jml_disarankan. "<br>";
        echo "JUMLAH DIPERTIMBANGKAN: " .$jml_dipertimbangkan. "<br>";
        echo "JUMLAH TIDAK DISARANKAN: " .$jml_tidak_disarankan. "<br>";

        return;
    }

    public function kirim_kelulusan() 
    {
        $this->load->model(array('stin/Mpsi'));

        $url = "https://services.puspsi.com/api/peserta/kelulusan";
        
        $curl = curl_init();

        $fp = fopen('c://temp//errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $token = $this->Mpsi->get_token(); 
        $headers = array(
           "Accept: application/json",
           "Content-Type: application/json",
           "Authorization: Bearer ".$token
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $data = $this->Mpsi->list_lulus();
        if ($data == null) {
            echo 'BELUM ADA DATA KELULUSAN';
            exit();
        }

        $json = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        
        $json = json_decode($resp);
        if (empty($json)) {
            echo 'INVALID RESPONSE';
            exit();
        }

        $jumlah_data = $json->jumlah_data;
        if (!empty($jumlah_data) && $jumlah_data == count($data)) {
            echo "BERHASIL: JUMLAH DATA DIPROSES (" .$jumlah_data. ") == JUMLAH DATA DIKIRIM (" .count($data). ")";
            exit();
        }

        echo "TIDAK BERHASIL: JUMLAH DATA DIPROSES (" .$jumlah_data. ") != JUMLAH DATA DIKIRIM (" .count($data). ")";
    }
}
