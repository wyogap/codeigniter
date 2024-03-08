<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Minventory extends CI_Model
{

    public function __construct()
    {
    }

    /**
     * Change invreceive status from DRAFT to COMP. Create correspondingn new entry in inventory
     */
    public function approve_receiving($ids) {
        if ($ids == null) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invreceive a
        set a.tag=?
        where a.status='DRAFT' and a.is_deleted=0 and a.invreceiveid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_receiving_approveall(?,?)";
        $query = $this->db->query($sql, array($tag, $userid));

        return $affected;
    }

    /**
     * Change usage status from DRAFT to COMP
     */
    public function approve_usage($ids) {
        if ($ids == null) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invusage a
        set a.tag=?
        where a.status='DRAFT' and a.is_deleted=0 and a.invusageid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_usage_approveall(?,?)";
        $query = $this->db->query($sql, array($tag,$userid));

        return $affected;
    }    

    /**
     * Change transfer status from DRAFT to COMP
     */
    public function approve_transfer($ids) {
        if ($ids == null) return 0;

        $userid = $this->session->userdata('user_id');

        //convert to array if necessary
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $tag = time();

        $sql = "update tcg_invtransfer a
        set a.tag=?
        where a.status='DRAFT' and a.is_deleted=0 and a.invtransferid in ?";

        $query = $this->db->query($sql, array($tag, $ids));
        
        $affected = $this->db->affected_rows();
        if ($affected == 0) return 0;

        //execute the bulk changes
        $sql = "call usp_transfer_approveall(?,?)";
        $query = $this->db->query($sql, array($tag,$userid));

        return $affected;
    }

    /**
     * Set writeoff = 1, substract the stockcount in inventory.
     */
    public function approve_writeoff($id) {
        $userid = $this->session->userdata('user_id');

        //execute the change
        $sql = "call usp_stockstatus_writeoff(?,?)";
        $query = $this->db->query($sql, array($id,$userid));

        return 1;
    }

    /**
     * Set stockcheck status fron INPROG to COMP. Update inventory stockcount. Create invstatus if necessary
     */
    public function approve_stockcheck($id) {
        $userid = $this->session->userdata('user_id');

        //execute the change
        $sql = "call usp_stockcheck_complete(?,?)";
        $query = $this->db->query($sql, array($id,$userid));

        return 1;
    }

    /**
     * Generate snapshot list of items for stock check
     */
    public function generate_stockcheck($id) {
        $userid = $this->session->userdata('user_id');

        //execute the change
        $sql = "call usp_stockcheck_snapshot(?,?)";
        $query = $this->db->query($sql, array($id,$userid));

        return 1;
    }
}

  