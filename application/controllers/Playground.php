<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Playground extends CI_Controller {

	public function index()
	{
		$this->load->view('playground/bubble');
	}

	
	public function subtable()
	{
		$this->load->view('playground/subtable');
	}

}
