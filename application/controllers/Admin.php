<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Admin extends Base_Crud {
	//protected static $PAGE_GROUP = 'admin';

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
}
