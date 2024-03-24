<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mselect extends CI_Model
{

    public function __construct()
    {
    }

    public function store() {
        $sql = "select storeid as value, description as label from tcg_store where is_deleted=0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function lk_store() {
        $sql = "select storeid, description, storecode, latitude, longitude, orgcode, sitecode from tcg_store where is_deleted=0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function tipebekal() {
        $sql = "select typeid as value, typecode as label from tcg_itemtype where is_deleted=0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function satuankerja() {

        $sql = "SELECT a.*
        from (
            SELECT a.siteid as value, a.name as label,  a.level as level,
                case when a.level=0 then concat(a.orgid,'-',lpad(`a`.`siteid`,3,'0')) 
                     when a.level=1 then concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                     else concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                     end AS `compname`
            FROM tcg_site a
            left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0 
            left join tcg_site c on c.orgid=a.orgid and c.level=0 and c.is_deleted=0 and a.level>0
            where a.is_deleted=0 
        ) a order by a.compname asc;
        ";

        $query = $this->db->query($sql);
        if ($query == null)     return $query;

        return $query->result_array();
    }

}

  