<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'controllers/system/Base_Crud.php');

class Sistem extends Base_Crud {

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
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = 'admin';

		$this->load->model(array('crud/Mnavigation'));
		$navigation = $this->Mnavigation->get_navigation($this->session->userdata('role_id'));
		$page_data['navigation']	 = $navigation;

		// $this->load->model(array('bpkad/Mdashboard'));

		$page_data['total'] = 0;
		$page_data['terverifikasi'] = 0;
		$page_data['perlu_verifikasi'] = 0;

		// $total = $this->Mdashboard->kendaraan_total();
		// if ($total != null) {
		// 	$page_data['total'] = $total['total'];
		// 	$page_data['terverifikasi'] = $total['terverifikasi'];
		// 	$page_data['perlu_verifikasi'] = $total['perlu_verifikasi'];
		// }

		// $page_data['per_jenis_kendaraan'] = $this->Mdashboard->kendaraan_per_jenis_kendaraan();
		// $page_data['per_peruntukan'] = $this->Mdashboard->kendaraan_per_peruntukan();
		// $page_data['per_umur_kendaraan'] = $this->Mdashboard->kendaraan_per_umur_kendaraan();
		
		//$page_data['per_opd'] = $this->Mdashboard->kendaraan_per_opd();

		$this->smarty->render_theme('sistem/home.tpl', $page_data);
	}
}
