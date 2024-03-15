<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Auth.php');

class Auth extends Base_Auth
{
    // //customization here
    // /**
    //  * This function used to logged in user
    //  */
    // public function login_iwakk()
    // {
    //     $this->load->model(array('iwakk/Miwakk'));
    //     $this->load->library('form_validation');
        
	// 	$username = $this->input->post('username');
    //     $password = $this->input->post('password');
	// 	$captcha = $this->input->post("g-recaptcha-response", TRUE);

    //     $json = $this->input->post('json');
    //     if (empty($json)) {
    //         $json = 0;
    //     }
        
    //     $use_captcha = ($this->setting->get('use_captcha') == 1);

    //     $data['use_captcha'] = $use_captcha;
    //     if (!isset($username) && !isset($password)) {
    //         $error = __('Silahkan periksa Username/password anda');
    //         if ($json == 1) {
    //             $data = array('status'=>'0', 'error'=>$error);
    //             echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //         }
    //         else {
    //             $this->smarty->render('auth/login.tpl',$data);
    //         }
    //         return;
    //     }
		
    //     $data['is_localhost'] = (strpos(base_url(), 'localhost') >= 0) || (strpos(base_url(), '127.0.0.1') >= 0) || (strpos(base_url(), '::1') >= 0);

    //     $this->form_validation->set_rules('username', 'Username', 'required|max_length[128]|trim');
    //     $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
    //     if(isset($username) && $this->form_validation->run() == FALSE)
    //     {
    //         $error = __('Silahkan periksa Username/password anda');
    //         if ($json == 1) {
    //             $data = array('status'=>'0', 'error'=>$error);
    //             echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //         }
    //         else {
    //             $this->session->set_flashdata('error', $error);	
    //             $this->smarty->render('auth/login.tpl',$data);
    //         }
	// 		return;
    //     }

	// 	if($use_captcha == 1 && $this->check_recaptcha_v2($captcha) == 0){
    //         $error = __('Kode Captcha yang anda masukkan salah');
    //         if ($json == 1) {
    //             $data = array('status'=>'0', 'error'=>$error);
    //             echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //         }
    //         else {
    //             $this->session->set_flashdata('error', $error);	
    //             $this->smarty->render('auth/login.tpl',$data);
    //         }
	// 		return;
	// 	}

	// 	$result = $this->Miwakk->login($username, $password);
        	
	// 	if($result == null)
	// 	{
    //         $error = __('Username/password tidak ditemukan');
    //         if ($json == 1) {
    //             $data = array('status'=>'0', 'error'=>$error);
    //             echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //         }
    //         else {
    //             $this->session->set_flashdata('error', $error);		
    //             $this->smarty->render('auth/login.tpl',$data);
    //         }
	// 		return;
	// 	}

    //     if ($result['allow_login'] != '1') {
    //         $error = __('Akses login anda untuk sementara ditolak');
    //         if ($json == 1) {
    //             $data = array('status'=>'0', 'error'=>$error);
    //             echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //         }
    //         else {
    //             $this->session->set_flashdata('error', $error);	
    //             $this->smarty->render('auth/login.tpl',$data);
    //         }
	// 		return;
    //     }
        
    //     $result['profile_img'] = base_url(). (empty($result['profile_img']) ? static::$DEFAULT_PROFILE_IMAGE : $result['profile_img']);
    //     $result['is_logged_in'] = TRUE;

    //     if (empty($result['page_role'])) {
    //         $result['page_role'] = 'user';
    //     }

    //     //theme
    //     //$result['theme'] = 'default';
    //     //$result['page_role'] = 'user';

    //     $this->session->set_userdata($result);
            
    //     $page_role = $this->session->userdata('page_role');
    //     if (empty($page_role)){
    //         $page_role="user";
    //         $this->session->set_userdata('page_role', $page_role);
    //     }      

    //     $page_role = $this->session->userdata('page_role');
        
    //     if ($json == 1) {
    //         $data = array("status"=>1, "redirect"=>site_url() ."/$page_role/home");
    //         echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    //     } 
    //     else {
    //         redirect(site_url() ."/$page_role");
    //     }
    // }
}

?>