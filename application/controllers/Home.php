<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$data = array();

		$data['newsflash'] = $this->setting->get('app_news_flash', '', 'system');
		$data['description'] = $this->setting->get('app_description', 'Tidak ada pencarian', 'system');
		$data['max_search_result'] = $this->setting->get('search_max_result', '20', 'search');

		//echo site_url();

		$this->smarty->render('public/home.tpl', $data);
	}

}
