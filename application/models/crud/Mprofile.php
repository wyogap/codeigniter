<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mprofile extends Mcrud_tablemeta
{
    protected static $DEF_TABLE_ID = 19;     //default table

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        $this->reset_error();
    
        $result = parent::update($id, $valuepair, $filter, $enforce_edit_columns);
        $user_id = $this->session->userdata('user_id');

        if ($result > 0 && $user_id == $id) {
            if (isset($valuepair['siteid'])) {
                //update session
                $this->session->set_userdata('siteid', $valuepair['siteid']);
            }
            if (isset($valuepair['itemtypeid'])) {
                //update session
                $this->session->set_userdata('itemtypeid', $valuepair['itemtypeid']);
            }
        }

        return $result;
    }


}

  