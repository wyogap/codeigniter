<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// @codeCoverageIgnoreEnd
require_once BASEPATH.'../vendor/autoload.php';
   
use Aws\S3\S3Client;

class Playground extends CI_Controller {

	public function index()
	{
		$this->load->view('playground/bubble');
	}

	
	public function subtable()
	{
		$this->load->view('playground/subtable');
	}

	public function selecttable()
	{
		$this->load->view('playground/selecttable');
	}

    public function usersession() {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('crud/Msession');
		$sessions = $this->Msession->get_user_session($user_id);

		for($i=0; $i<count($sessions); $i++) {
			$sess = $sessions[$i];
            $this->session->set_userdata($sess['session_name'], $sess['session_value']);
        }

        var_dump($this->session->userdata());
    }

    public function store()
    {  
  
           // Instantiate an Amazon S3 client.
           $s3Client = new S3Client([
               'version' => 'latest',
        	   'region'  => 'us-east-2',
               'credentials' => [
                   'key'    => 'd53b114f9becbcf00EWH',
                   'secret' => 'nWJFFZBf7PcwU77fnLGVBbaBq6QAeyG6Cg2XFtIk'
               ]
           ]);
  
           $bucket = 'ppdb2022';
           $file_Path = __DIR__ . '/images/icon/pdf_'. time().'_'. rand(). '.png';
           $key = basename($file_Path);
  
           try {
               $result = $s3Client->putObject([
                   'Bucket' => $bucket,
                   'Key'    => $key,
                   'Body'   => fopen($file_Path, 'r'),
                   'ACL'    => 'public-read', // make file 'public'
               ]);
           } catch (Aws\S3\Exception\S3Exception $e) {
               //$msg = 'File has been uploaded';
               echo $e->getMessage(); exit;
           }

           echo 'File has been uploaded';
  
    }
    
    public function periode() {
        $periode = $this->input->get('periode');
        if (empty($periode))    $periode='0';
        $offset = $this->input->get('offset');
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);
        $offset = strtoupper($offset);
        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        echo $datestr;
    }
}
