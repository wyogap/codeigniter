<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Rest_Controller.php');

class Kendaraandinas extends MY_Rest_Controller {
    private static $TABLE_NAME = "API - Kendaraan Dinas";

    public function search_get()
    {
        // If the id parameter doesn't exist return all the users
        $table_name = static::$TABLE_NAME;

        $model = $this->get_model($table_name);
        if ($model == null || !$model->is_initialized()) {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid model'
            ], MY_Rest_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        //query string
        $q = $this->get('q');

		//filter params
		$filters = array();
        $filter_columns = $model->filter_columns();
        if (count($filter_columns) > 0) {
            foreach($this->input->get() as $key => $val)
            {
                if ($val == '') continue;
                if (substr($key, 0, 2) != "f_") continue;

                $col_name = substr($key, 2);
                if (false === array_search($col_name, $filter_columns)) continue;
                $filters[$col_name] = $val;
            }
        }

        //must provide query string or filter
        if (empty($q) && count($filters) == 0) {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No query string'
            ], MY_Rest_Controller::HTTP_BAD_REQUEST); 
        }

        //default value for consistency
        if (empty($q)) {
            $q = "";
        }

        $results = $model->search($q, $filters);
        // Check if the users data store contains users (in case the database result returns NULL)
        if ($results)
        {
            // Set the response and exit
            $this->response($results, MY_Rest_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data'
            ], MY_Rest_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    protected function get_model($table_name) {
		$this->load->model('crud/Mtable');

		if (!$this->Mtable->init($table_name, false)) {
			return null;
		}

		return $this->Mtable;
	}

}