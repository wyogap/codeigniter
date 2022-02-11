<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index()
    {
        $isLoggedIn = $this->session->userdata('is_logged_in');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $data['use_captcha'] = ($this->setting->get('use_captcha') == 1);
            $data['is_localhost'] = (strpos(base_url(), 'localhost') >= 0);

			$this->smarty->render('auth/login.tpl',$data);
            return;
        }

        $page_role = $this->session->userdata('page_role');
        if (empty($page_role)){
            $page_role="user";
            $this->session->set_userdata('page_role', $page_role);
        }      

        redirect(site_url() ."/$page_role/home");
    }

	// public function index()
	// {
	// 	$data = array();

	// 	$data['newsflash'] = $this->setting->get('app_news_flash', '', 'system');
	// 	$data['description'] = $this->setting->get('app_description', 'Tidak ada pencarian', 'system');
	// 	$data['max_search_result'] = $this->setting->get('search_max_result', '20', 'search');

	// 	//echo site_url();

	// 	$this->smarty->render('public/home.tpl', $data);
	// }

}
