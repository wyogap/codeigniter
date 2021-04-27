<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud.php');

class Mkeys extends Mcrud
{
    protected static $TABLE_NAME = "dbo_api_keys";
    protected static $PRIMARY_KEY = "id";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();
    protected static $SEARCHES = array();

    protected static $COL_LABEL = 'key';
    protected static $COL_VALUE = 'id';

    protected static $SOFT_DELETE = true;

    function add($valuepair) {
        //generate the token
        $valuepair['key'] = generate_token(40);

        return parent::add($valuepair);
    }
}

  