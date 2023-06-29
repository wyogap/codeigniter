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
}
