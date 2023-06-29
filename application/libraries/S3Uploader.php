<?php
// @codeCoverageIgnoreStart
defined('BASEPATH') || exit('No direct script access allowed');
// @codeCoverageIgnoreEnd

require 'vendor/autoload.php';
   
use Aws\S3\S3Client;

class S3Uploader
{
    protected static $SETTING_TABLE = 'dbo_settings';

    protected $ci = null;

    public function __construct($config = array())
    {
        //init
        $ci =& get_instance();
    }

    public function get($name, $default="", $group=null) {
        $ci =& get_instance();

        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $ci->db->select('value');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->row_array();
        if ($arr == null) {
            return $default;
        }

        return $arr['value'];
    }

    public function store($upload)
    {  
  
           // Instantiate an Amazon S3 client.
           $s3Client = new S3Client([
               'version' => 'latest',
            //    'region'  => 'YOUR_AWS_REGION',
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
             $msg = 'File has been uploaded';
           } catch (Aws\S3\Exception\S3Exception $e) {
               //$msg = 'File has been uploaded';
               echo $e->getMessage();
           }

           $msg = 'File has been uploaded';
  
    }

    public function set($name, $value, $group=null) {
        $ci =& get_instance();
        
        $values = array (
            'value'         => $value,
            'updated_on'    => date('Y/m/d H:i:s'),     //utc
            'updated_by'    => $ci->session->userdata('user_id')
        );

        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $ci->db->update(static::$SETTING_TABLE, $values, $filters);
    }

    public function list() {
        $ci =& get_instance();
        
        $filters = array (
            'is_deleted'    => 0
        );

        $ci->db->select('name, group, value, description');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->result_array();

        return $arr;
    }

    public function list_group($group) {
        $ci =& get_instance();
        
        $filters = array (
            'is_deleted'    => 0,
            'group'         => $group
        );

        $ci->db->select('name, group, value, description');
        $arr = $ci->db->get_where(static::$SETTING_TABLE, $filters)->result_array();

        return $arr;
    }
}