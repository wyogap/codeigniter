<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mkeys extends Mcrud_tablemeta
{
    function add($valuepair, $enforce_edit_columns = true) {
        //generate the token
        $valuepair['key'] = generate_token(40);

        return parent::add($valuepair);
    }
}

  