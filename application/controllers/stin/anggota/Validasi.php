<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// @codeCoverageIgnoreEnd
require_once BASEPATH.'../vendor/autoload.php';

class Validasi extends CI_Controller {

    public function index() {
        echo "STIN";
    }

    private function validasi_login() {
        //show validation page for login user
    }

    private function validasi_nonlogin() {
        //show validation page for non-login user
    }

    public function simpan() {
        //store the updated data
    }

    public function tarikdata() {
        //get existing profile based on provided token
    }

    public function kirimtoken() {
        //send token to the registered number
    }
}
