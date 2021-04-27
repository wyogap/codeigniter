<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Rest_Controller.php');
require_once APPPATH .'/third_party/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

class Rest extends MY_Rest_Controller {

    protected $_use_key = TRUE;

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->methods['entity_view']['log'] = FALSE;
        $this->methods['entity_create']['log'] = TRUE;
        $this->methods['entity_update']['log'] = TRUE;
        $this->methods['entity_delete']['log'] = TRUE;

        $this->methods['entity_view']['key'] = $this->_use_key;
        $this->methods['entity_create']['key'] = $this->_use_key;
        $this->methods['entity_update']['key'] = $this->_use_key;
        $this->methods['entity_delete']['key'] = $this->_use_key;

        $this->methods['entity_view']['limit'] = 0;
        $this->methods['entity_create']['limit'] = 0;
        $this->methods['entity_update']['limit'] = 0;
        $this->methods['entity_delete']['limit'] = 0;

    }

    /**
     * Requests are not made to methods directly, the request will be for
     * an "object". This simply maps the object and method to the correct
     * Controller method.
     *
     * @param string $object_called
     * @param array  $arguments     The arguments passed to the controller method
     *
     * @throws Exception
     */
    public function _remap($object_called, $arguments = [])
    {
        $this->load->model(array('crud/Mpages', 'crud/Mpermission'));        

        // Should we answer if not over SSL?
        if ($this->config->item('force_https') && $this->request->ssl === false) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_unsupported'),
            ], self::HTTP_FORBIDDEN);
        }

        // Remove the supported format from the function name e.g. index.json => index
        $object_called = preg_replace('/^(.*)\.(?:'.implode('|', array_keys($this->_supported_formats)).')$/', '$1', $object_called);

        if ($this->config->item('rest_enable_jwt_token') && $object_called == "token") {
            $this->token();
            return;
        }

        $crud_api = $this->config->item('rest_enable_crud_api');

        $entity = $this->Mpages->get_api_page($object_called, $crud_api);
        if ($entity == null || empty($entity['crud_table_id'])) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_not_found'),
            ], MY_Rest_Controller::HTTP_NOT_FOUND);
            return;
        }

        $entity_id = $entity['id'];

        //public entity
        if ($entity['is_public'] == 1) {
            $this->methods['entity_view']['key'] = FALSE;
        }

        //odata api call
        if ($this->request->method == "get") {
            $controller_method = "entity_view";
            $controller_arg = $this->_get_args;
        }
        else if ($this->request->method == "post") {
            $controller_method = "entity_create";
            $controller_arg = $this->_post_args;
        }
        else if ($this->request->method == "put") {
            $controller_method = "entity_create";
            $controller_arg = $this->_put_args;
        }
        else if ($this->request->method == "patch") {
            $controller_method = "entity_update";
            $controller_arg = $this->_patch_args;
        }
        else if ($this->request->method == "delete") {
            $controller_method = "entity_delete";
            $controller_arg = $this->_delete_args;
        }
        else {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_unsupported'),
            ], self::HTTP_FORBIDDEN);
        }
        
        //update limiter
        $this->methods[$controller_method]['limit'] = $this->rest->limit;
        $this->methods[$controller_method]['time'] = $this->rest->time_limit_sec;

        // Do we want to log this method (if allowed by config)?
        $log_method = !(isset($this->methods[$controller_method]['log']) && $this->methods[$controller_method]['log'] === false);

        // Use keys for this method?
        $use_key = !(isset($this->methods[$controller_method]['key']) && $this->methods[$controller_method]['key'] === false);

        if ($use_key) {
            $allow_access = false;

            if (!empty($this->rest->key)) {

                // They provided a key, but it wasn't valid, so get them out of here
                if ($this->_allow === false) {
                    if ($this->config->item('rest_enable_logging') && $log_method) {
                        $this->_log_request();
                    }

                    // fix cross site to option request error
                    if ($this->request->method == 'options') {
                        exit;
                    }

                    $this->response([
                        $this->config->item('rest_status_field_name')  => false,
                        $this->config->item('rest_message_field_name') => sprintf($this->lang->line('text_rest_invalid_api_key'), $this->rest->key),
                    ], self::HTTP_FORBIDDEN);
                }

                // Check to see if this key has access to the requested controller
                if ($this->_check_key_permission($entity_id, $controller_method) === false) {
                    if ($this->config->item('rest_enable_logging') && $log_method) {
                        $this->_log_request();
                    }

                    $this->response([
                        $this->config->item('rest_status_field_name')  => false,
                        $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_api_key_unauthorized'),
                    ], self::HTTP_UNAUTHORIZED);
                }

                $allow_access = true;
            }

            else if (!empty($this->rest->token)) {
                // They provided a token, but it wasn't valid, so get them out of here
                if ($this->_allow_token === false) {
                    if ($this->config->item('rest_enable_logging') && $log_method) {
                        $this->_log_request();
                    }

                    // fix cross site to option request error
                    if ($this->request->method == 'options') {
                        exit;
                    }

                    $this->response([
                        $this->config->item('rest_status_field_name')  => false,
                        $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_invalid_jwt_token'),
                    ], self::HTTP_FORBIDDEN);
                }

                // Check to see if this key has access to the requested controller
                if ($this->_check_token_permission($entity_id, $controller_method) === false) {
                    if ($this->config->item('rest_enable_logging') && $log_method) {
                        $this->_log_request();
                    }

                    $this->response([
                        $this->config->item('rest_status_field_name')  => false,
                        $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_api_key_unauthorized'),
                    ], self::HTTP_UNAUTHORIZED);
                }

                $allow_access = true;
            }

            if (!$allow_access) {
                $this->response([
                    $this->config->item('rest_status_field_name')  => false,
                    $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_unauthorized'),
                ], self::HTTP_UNAUTHORIZED);
            }
        }

        // Sure it exists, but can they do anything with it?
        if (!method_exists($this, $controller_method)) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_api_key_permissions'),
            ], self::HTTP_METHOD_NOT_ALLOWED);
        }

        // Doing key related stuff? Can only do it if they have a key right?
        if ($this->config->item('rest_enable_keys') && empty($this->rest->key) === false) {
            // Check the limit
            if ($this->config->item('rest_enable_limits') && $this->_check_limit($controller_method) === false) {
                $response = [$this->config->item('rest_status_field_name') => false, $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_api_key_time_limit')];
                $this->response($response, self::HTTP_UNAUTHORIZED);
            }

            // If no level is set use 0, they probably aren't using permissions
            $level = isset($this->methods[$controller_method]['level']) ? $this->methods[$controller_method]['level'] : 0;

            // If no level is set, or it is lower than/equal to the key's level
            $authorized = $level <= $this->rest->level;
            // IM TELLIN!
            if ($this->config->item('rest_enable_logging') && $log_method) {
                $this->_log_request($authorized);
            }
            if ($authorized === false) {
                // They don't have good enough perms
                $response = [$this->config->item('rest_status_field_name') => false, $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_api_key_permissions')];
                $this->response($response, self::HTTP_UNAUTHORIZED);
            }
        }

        //check request limit by ip without login
        elseif ($this->config->item('rest_limits_method') == 'IP_ADDRESS' && $this->config->item('rest_enable_limits') && $this->_check_limit($controller_method) === false) {
            $response = [$this->config->item('rest_status_field_name') => false, $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_ip_address_time_limit')];
            $this->response($response, self::HTTP_UNAUTHORIZED);
        }

        // No key stuff, but record that stuff is happening
        elseif ($this->config->item('rest_enable_logging') && $log_method) {
            $this->_log_request($authorized = true);
        }

		$model = $this->get_model($entity['crud_table_id']);
		if ($model == null) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_not_found'),
            ], MY_Rest_Controller::HTTP_NOT_FOUND);
		}

        // Call the controller method and passed arguments
        try {
            call_user_func_array([$this, $controller_method], array($model, $controller_arg, $arguments));
        } catch (Exception $ex) {
            if ($this->config->item('rest_handle_exceptions') === false) {
                throw $ex;
            }

            // If the method doesn't exist, then the error will be caught and an error response shown
            $_error = &load_class('Exceptions', 'core');
            $_error->show_exception($ex);
        }
    }

    public function entity_view ($model, $arg, $params = array()) {

        if (count($params) >= 1) {
            //select detail
            $key = $params[0];

            $detail = $model->detail($key);
            if ($detail == null) {
                $this->response([
                    $this->config->item('rest_status_field_name')  => false,
                    $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_not_found'),
                ], MY_Rest_Controller::HTTP_NOT_FOUND);
            }

            $this->response($detail, MY_Rest_Controller::HTTP_OK); 

            return;
        }

        //params
        $limit = $arg['$top'] ?? $arg['top'] ?? null;
        $offset = $arg['$skip'] ?? $arg['skip'] ?? null;
        $orderby = $arg['$orderby'] ?? $arg['orderby'] ?? null;

        //format: sql search string
        $search = $arg['$search'] ?? $arg['search'] ?? null;

        //format: "key=value,key=value,key=value
        $filter = $arg['$filter'] ?? $arg['filter'] ?? null;

        $filter_arr = array();
        if (!empty($filter)) {
            $arr = explode(',', $filter);
            foreach($arr as $f) {
                $arr2 = explode("=", $f);
                $key = trim($arr2[0]); 
                if (count($arr2) > 1) {
                    $val = trim($arr2[1]);
                    //add filter
                    $filter_arr[$key] = $val;
                }
            }
        }

        $result = null;
        if (!empty($search)) {
            $search = trim($search);
            $result = $model->search($search, $filter_arr, $limit, $offset, $orderby);
        }
        else {
            $result = $model->list($filter_arr, $limit, $offset, $orderby);
        }

        $data = array();
        if ($result == null) {
            $data["result"] = array();
        }
        else {
            $data["result"] = $result;
        }

        $this->response($data, MY_Rest_Controller::HTTP_OK); 
    }

    public function entity_create ($model, $arg, $params = array()) {

        if (count($params) >= 1) {
            //with detail, try updating
            $key = $params[0];

            $detail = $model->detail($key);
            if ($detail != null) {
                $this->entity_update($model, $arg, $params);
                return;
            }
        }

        $key = $model->add($arg);
        if ($key == 0) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_failed'),
            ], MY_Rest_Controller::HTTP_BAD_REQUEST);
        }

        $data = array();
        $data['key'] = $key;

        $this->response($data, MY_Rest_Controller::HTTP_CREATED); 

    }

    public function entity_update ($model, $arg, $params = array()) {

        if (count($params) == 0) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_not_found'),
            ], MY_Rest_Controller::HTTP_BAD_REQUEST);
        }

        $key = $params[0];

        $key = $model->update($key, $arg);
        if ($key == 0) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_operation_failed'),
            ], MY_Rest_Controller::HTTP_BAD_REQUEST);
        }

        $data = array();
        $data['key'] = $key;

        $this->response($data, MY_Rest_Controller::HTTP_OK); 
    }

    public function entity_delete ($model, $arg, $params = array()) {

        if (count($params) == 0) {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_not_found'),
            ], MY_Rest_Controller::HTTP_BAD_REQUEST);
        }

        $key = $params[0];
        $key = $model->delete($key);

        $this->response(null, MY_Rest_Controller::HTTP_OK); 
    }

	protected function get_model($table_id) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_id, true)) {
			return null;
		}

		return $this->Mtable;
	}


    /**
     * Check to see if the API key has access to the controller and methods.
     *
     * @return bool TRUE the API key has access; otherwise, FALSE
     */
    protected function _check_key_permission($entity_id, $controller_method)
    {
        // If we don't want to check access, just return TRUE
        if ($this->config->item('rest_enable_access') === false) {
            return false;
        }

        if ($this->rest->is_admin == 1) {
            return true;
        }
        
        // // Fetch controller based on path and controller name
        // $controller = implode(
        //     '/',
        //     [
        //         $this->router->directory,
        //         $this->router->class,
        //     ]
        // );

        // // Remove any double slashes for safety
        // $controller = str_replace('//', '/', $controller);

        //check if the key has all_access
        $accessRow = $this->rest->db
            ->where('key', $this->rest->key)
            ->where('entity_id', $entity_id)
            ->get($this->config->item('rest_access_table'))->row_array();

        if (empty($accessRow) || $accessRow['no_access'] == 1) {
            return false;
        }
        
        if ($controller_method == "entity_view" && $accessRow['allow_view'] == 1) {
            return true;
        }

        if ($controller_method == "entity_create" && $accessRow['allow_add'] == 1) {
            return true;
        }

        if ($controller_method == "entity_update" && $accessRow['allow_edit'] == 1) {
            return true;
        }

        if ($controller_method == "entity_delete" && $accessRow['allow_delete'] == 1) {
            return true;
        }

        return false;
    }

    /**
     * Check to see if the API key has access to the controller and methods.
     *
     * @return bool TRUE the API key has access; otherwise, FALSE
     */
    protected function _check_token_permission($entity_id, $controller_method)
    {
        // If we don't want to check access, just return TRUE
        if ($this->config->item('rest_enable_jwt_token') === false) {
            return false;
        }

        if (empty($this->rest->token)) {
            return false;
        }

        if ($this->rest->is_admin == 1) {
            return true;
        }
        
        $date = new DateTime();
        if ($date->getTimestamp() > $this->rest->token->exp) {
            return false;
        }

        //check if the key has all_access
        $accessRow = $this->rest->db
            ->where('role_id', $this->rest->role_id)
            ->where('page_id', $entity_id)
            ->get("dbo_crud_permissions")->row_array();

        if (empty($accessRow) || $accessRow['no_access'] == 1) {
            return false;
        }
        
        if ($controller_method == "entity_view" && $accessRow['allow_view'] == 1) {
            return true;
        }

        if ($controller_method == "entity_create" && $accessRow['allow_add'] == 1) {
            return true;
        }

        if ($controller_method == "entity_update" && $accessRow['allow_edit'] == 1) {
            return true;
        }

        if ($controller_method == "entity_delete" && $accessRow['allow_delete'] == 1) {
            return true;
        }

        return false;
    }
    
    public function token()
    {
        $this->load->model(array('Mauth'));
        $this->load->library('form_validation');

        $username = $this->post('username'); //Username Posted
        $password = $this->post('password'); //Pasword Posted

        $this->form_validation->set_rules('username', 'Username', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
        if(isset($username) && $this->form_validation->run() == FALSE)
        {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_invalid_credentials'),
            ], self::HTTP_UNAUTHORIZED);
        }

        $result = $this->Mauth->login($username, $password);		

		if($result == null)
		{
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_invalid_credentials'),
            ], self::HTTP_UNAUTHORIZED);
		}

        if ($result['allow_login'] != '1') {
            $this->response([
                $this->config->item('rest_status_field_name')  => false,
                $this->config->item('rest_message_field_name') => $this->lang->line('text_rest_unauthorized'),
            ], self::HTTP_FORBIDDEN);
        }

        $key = $this->config->item('rest_jwt_key');
        $expiry = $this->config->item('rest_jwt_expiry_sec');

        $token = array();
        $token['userid'] = $result['user_id'];  
        $token['username'] = $username;
        $token['roleid'] = $result['role_id'];
        $token['admin'] = $result['admin'];

        $date = new DateTime();
        $token['iat'] = $date->getTimestamp();
        $token['exp'] = $date->getTimestamp() + $expiry; //To here is to generate token

        $output['token'] = JWT::encode($token, $key); 
        $this->response($output, self::HTTP_OK); 
    }
}
