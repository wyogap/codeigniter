<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH.'../vendor/autoload.php';

$ci =& get_instance();

//var_dump($ci);

if ( !isset($ci->db_rw) )
{
    $ci->db_rw = $ci->db;

    // //if exist, load database connection parameter to RW database
    // $db_rw = $this->load->database('rw', TRUE);
    // if ($db_rw != null) {
    //     $ci->db_rw = $db_rw;
    // } else {
    //     //otherwise, use the default connection
    //     $ci->db_rw = $db;
    // }
}


?>        

        