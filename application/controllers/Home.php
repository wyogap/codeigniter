<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		// $this->smarty->assign('app_name', 'TCG Framework');
		// $this->smarty->assign('app_short_name', 'TCG');
		// $this->smarty->assign('newsflash', 'Segenap staff BPKAD Kebumen mengucapkan selamat menjalankan ibadah puasa kepada seluruh umat muslim di Kebumen.');

		// $this->smarty->assign('base_url', base_url());
		// $this->smarty->assign('userdata', $this->session->userdata());

		$data = array();
		// $data['newsflash'] = 'Segenap staff BPKAD Kebumen mengucapkan selamat menjalankan ibadah puasa kepada seluruh umat muslim di Kebumen.';

		$data['newsflash'] = $this->setting->get('app_news_flash', '', 'system');
		$data['max_search_result'] = 20;

		$this->smarty->render('public/home.tpl', $data);
	}

}
