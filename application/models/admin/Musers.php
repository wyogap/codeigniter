<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud.php');

class Musers extends Mcrud
{
    protected static $TABLE_NAME = "dbo_users";
    protected static $PRIMARY_KEY = "user_id";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'nama';
    protected static $COL_VALUE = 'user_id';

    protected static $SOFT_DELETE = true;

    function lookup($filter = null) {
        if ($filter == null) $filter = array();
    
        $filter['user_id>'] = 10;

        return parent::lookup($filter);
    }    

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if ($filter == null) $filter = array();
    
        $filter['user_id>'] = 10;

        return parent::list($filter, $limit, $offset, $orderby);
    }    
}

  