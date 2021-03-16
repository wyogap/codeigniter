<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
    protected static $DEFAULT_PROFILE_IMAGE = "assets/image/user.png";

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
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

        redirect(base_url() ."$page_role/home");
    }
    
    
    /**
     * This function used to logged in user
     */
    public function login()
    {
        $this->load->model(array('Mauth'));
        $this->load->library('form_validation');
        
		$username = $this->input->post('username');
        $password = $this->input->post('password');
		$captcha = $this->input->post("g-recaptcha-response", TRUE);
        
        $use_captcha = ($this->setting->get('use_captcha') == 1);

        $data['use_captcha'] = $use_captcha;
        if (!isset($username) && !isset($password)) {
			$this->smarty->render('auth/login.tpl',$data);
			return;
        }
		
        $data['is_localhost'] = (strpos(base_url(), 'localhost') >= 0);

        $this->form_validation->set_rules('username', 'Username', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
        if(isset($username) && $this->form_validation->run() == FALSE)
        {
			$this->session->set_flashdata('error', __('Silahkan periksa Username/password anda'));	
			$this->smarty->render('auth/login.tpl',$data);
			return;
        }

		if($use_captcha == 1 && $this->check_recaptcha_v2($captcha) == 0){
			$this->session->set_flashdata('error', __('Kode Captcha yang anda masukkan salah'));	
			$this->smarty->render('auth/login.tpl',$data);
			return;
		}

		$result = $this->Mauth->login($username, $password);		
		if($result == null)
		{
			$this->session->set_flashdata('error', __('Username/password tidak ditemukan'));		
			$this->smarty->render('auth/login.tpl',$data);
			return;
		}

        if ($result['allow_login'] != '1') {
			$this->session->set_flashdata('error', __('Akses login anda untuk sementara ditolak'));	
			$this->smarty->render('auth/login.tpl',$data);
			return;
        }
        
        $result['profile_img'] = base_url(). (empty($result['profile_img']) ? static::$DEFAULT_PROFILE_IMAGE : $result['profile_img']);
        $result['is_logged_in'] = TRUE;

        if (empty($result['page_role'])) {
            $result['page_role'] = 'user';
        }

        //theme
        $result['theme'] = 'default';

        $this->session->set_userdata($result);
            
        // $page_role = $this->session->userdata('page_role');
        // if (empty($page_role)){
        //     $page_role="user";
        //     $this->session->set_userdata('page_role', $page_role);
        // }      

        $page_role = $this->session->userdata('page_role');
        redirect(base_url() ."$page_role/home");
    }

	function logout() {
		$this->session->sess_destroy();
		redirect(base_url() .'auth');
	}

    function notfound() {
        $isLoggedIn = $this->session->userdata('is_logged_in');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            redirect(base_url() .'auth');
            return;
        }

        $theme = $this->session->userdata('theme');
		if (!isset($theme)) {
			$theme = 'default';
		}

		$template = "themes/$theme/error/404.tpl";
		$this->smarty->render($template);
	}

    function notauthorized() {
        $isLoggedIn = $this->session->userdata('is_logged_in');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            redirect(base_url() .'auth');
            return;
        }

        $theme = $this->session->userdata('theme');
		if (!isset($theme)) {
			$theme = 'default';
		}

		$template = "themes/$theme/error/403.tpl";
		$this->smarty->render($template);
	}

	function check_recaptcha_v2($captcha) {
		if (empty($captcha))
			return false;
			
		//this is the proper way of checking it
		if(strpos(base_url(), 'localhost')) {
			//localhost
			$secret = '6LdDOOoUAAAAADGh9tqM6i4Yni5TtX1oVJbdkXey';
		} else {
			$secret = $config_item('app_captcha_key');
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $secret,
			'response' => $captcha
		);
		$options = array(
		    "ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
			'http' => array (
				'method' => 'POST',
				'content' => http_build_query($data),
				'header' => 'Content-Type: application/x-www-form-urlencoded',
			)
		);

		$context  = stream_context_create($options);
		$verify = file_get_contents($url, false, $context);
		$captcha_success=json_decode($verify);
	
		if ($captcha_success->success==false) {
			return 0;
		} else if ($captcha_success->success==true) {
			return 1;
		}
		
	}

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        $this->load->view('forgotPassword');
    }
    
    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','trim|required|valid_email|xss_clean');
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = $this->input->post('login_email');
            
            if($this->login_model->checkEmailExist($email))
            {
                $encoded_email = urlencode($email);
                
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $data1['reset_link'] = base_url() . "resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo[0]->name;
                        $data1["email"] = $userInfo[0]->email;
                        $data1["message"] = "Reset Password Anda";
                    }

                    $sendStatus = resetPasswordEmail($data1);

                    if($sendStatus){
                        $status = "send";
                        setFlashData($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        $status = "notsend";
                        setFlashData($status, "Email has been failed, try again.");
                    }
                }
                else
                {
                    $status = 'unable';
                    setFlashData($status, "It seems an error while sending your details, try again.");
                }
            }
            else
            {
                $status = 'invalid';
                setFlashData($status, "Username/email tidak terdaftar.");
            }
            redirect('/forgotPassword');
        }
    }

    // This function used to reset the password 
    function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        
        if ($is_correct == 1)
        {
            $this->load->view('newPassword', $data);
        }
        else
        {
            redirect('<?php echo base_url();?>Auth');
        }
    }
    
    // This function used to create new password
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = $this->input->post("email");
        $activation_id = $this->input->post("activation_code");
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        }
        else
        {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
            
            if($is_correct == 1)
            {                
                $this->login_model->createPasswordUser($email, $password);
                
                $status = 'success';
                $message = 'Password berhasil diubah';
            }
            else
            {
                $status = 'error';
                $message = 'Password tidak berhasil diubah';
            }
            
            setFlashData($status, $message);

            redirect("<?php echo base_url();?>Auth");
        }
    }

}

?>