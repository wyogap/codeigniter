<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Sistem extends Base_Crud {

	public function index($params = array())
	{
		$this->home($params);
	}

	//customization here

    public function api() {
        $action = $this->input->post("action");
		if ($action=='generate_columns') {
			
            $values = $this->input->post("data");

			$this->load->model(array('crud/Mtable'));

			$error_msg = "";
			$json['data'] = array();
			foreach ($values as $key => $valuepair) {
				$this->Mtable->generate_columns($key);
            }

			$json['status'] = 1;
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
        else if ($action == 'clone_table') {
			
            $values = $this->input->post("data");

			$this->load->model(array('crud/Mtable'));

			$error_msg = "";
			$json['data'] = array();
			foreach ($values as $key => $valuepair) {
				$new_key = $this->Mtable->clone($key);
                if ($new_key == 0) {
                    continue;
                }
                $json['data'][] = $this->Mtable->detail($new_key);
            }

			$json['status'] = 1;
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
        }
    }

	public function home() {
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['page_description']       = null;
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = $this->session->userdata('page_role');;

		$this->load->model(array('crud/Mnavigation'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		$page_data['use_geo'] = 1;
		$page_data['use_select2'] = 1;
		
		// $this->smarty->render_theme('sistem/home.tpl', $page_data);
		$this->smarty->render_theme('/disbekal/inventory/dashboard.tpl', $page_data);
	}
}
