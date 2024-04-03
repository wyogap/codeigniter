<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

/**
 * Use this class as base class to extend Mcrud_tablemeta
 */
class Mcrud_ext extends Mcrud_tablemeta
{
    protected static $TABLE_ID = 1;
    protected static $TABLE_NAME = "tcg_store";
    protected static $PRIMARY_KEY = "storeid";
    protected static $LOOKUP_KEY = "storecode";
    protected static $COLUMNS = array();

    protected static $SOFT_DELETE = true;

    function __construct() {
        $this->table_id = static::$TABLE_ID;
        $this->table_name = static::$TABLE_NAME;
        $this->columns = static::$COLUMNS;
        //make sure it is all uppercase for column comparison
        for($i=0; $i<count($this->columns); $i++) {
            $this->columns[$i] = strtoupper($this->columns[$i]);
        }
        //add default column if not there yet
        if (empty($this->columns)) {
            $this->columns = array(strtoupper(static::$PRIMARY_KEY), strtoupper(static::$LOOKUP_KEY));
        }
        $this->select_columns = $this->columns;

        $this->table_metas = Mtablemeta::$TABLE;
        $this->table_metas['table_id'] = static::$TABLE_ID;
        $this->table_metas['table_name'] = static::$TABLE_NAME;
        $this->table_metas['key_column'] = static::$PRIMARY_KEY;
        $this->table_metas['lookup_column'] = static::$LOOKUP_KEY;
        $this->table_metas['soft_delete'] = static::$SOFT_DELETE;

        parent::__construct();
    }

}

  