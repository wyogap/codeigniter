<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// @codeCoverageIgnoreEnd
require_once BASEPATH.'../vendor/autoload.php';

class Helper extends CI_Controller {

    public function index() {
        echo "IWAKK";
    }

    public function ktpterdaftar() {
        //cek ktp
    }

    public function tarikkab() {
        //get kab/kota for given province
    }

    public function tarikkec() {
        //get kec for given kab/kota
    }

    public function tarikdesa() {
        //get desa for given kec
    }
}
