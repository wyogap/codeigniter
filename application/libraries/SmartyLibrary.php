<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'third_party/smarty/libs/Smarty.class.php');

class SmartyLibrary extends Smarty {

    protected $ci = null;

    function __construct() {
        parent::__construct();

        // Define directories, used by Smarty:
        $this->setTemplateDir(APPPATH . 'views');
        $this->setCompileDir(APPPATH . 'cache/smarty_templates_cache');
        $this->setCacheDir(APPPATH . 'cache/smarty_cache');

        //base url
        $this->assign('base_url', base_url());

        //general setting
        $this->assign('app_name', config_item('app_name'));
        $this->assign('app_short_name', config_item('app_short_name'));
        $this->assign('app_icon', config_item('app_icon'));
        $this->assign('app_logo', config_item('app_logo'));
        $this->assign('app_captcha_key', config_item('app_captcha_key'));

        if (ENVIRONMENT === 'development') {
			$this->assign('version', 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>');
		}
        else {
            $this->assign('version', '');
        }

        //init reference
        $ci =& get_instance();
    }

    function render($template, $data = array()) {
        $ci =& get_instance();

        //assign form validation
        $validation_error = validation_errors();
        if (!empty($validation_error)) {
            $this->assign('validation_error', $validation_error);
        }

        //error message
        $error = $ci->session->flashdata('error');
        if (!empty($error)) {
            $this->assign('error_message', $error);
        }

        //success message
        $success = $ci->session->flashdata('success');
        if (!empty($success)) {
            $this->assign('success_message', $success);
        }

        //page data
        foreach($data as $key => $val) {
            $this->assign($key, $val);
        }

        //userdata
        $this->assign('userdata', $ci->session->userdata());

        //display the tempalte
        parent::display($template);
    }

    function render_theme($template, $data = array(), $theme = 'default') {
        $ci =& get_instance();

        //assign form validation
        $validation_error = validation_errors();
        if (!empty($validation_error)) {
            $this->assign('validation_error', $validation_error);
        }

        //error message
        $error = $ci->session->flashdata('error');
        if (!empty($error)) {
            $this->assign('error_message', $error);
        }

        //success message
        $success = $ci->session->flashdata('success');
        if (!empty($success)) {
            $this->assign('success_message', $success);
        }

        //page data
        foreach($data as $key => $val) {
            $this->assign($key, $val);
        }

        //userdata
        $userdata = $ci->session->userdata();
        $this->assign('userdata', $userdata);

        $theme_prefix = "themes/$theme";
        $this->assign('theme_prefix', $theme_prefix);

        //the actual template is the inner template
        if (substr( $template, 0, 1 ) == '/') {
            //start with '/' means it is not themeable template
            $inner_template = substr( $template, 1 );
        } 
        else {
            $inner_template = $theme_prefix .'/'. $template;
        }
        $this->assign('inner_template', $inner_template);

        //the master template is the index.tpl
        $template =  $theme_prefix .'/index.tpl';

        //display the tempalte
        parent::display($template);
    }}