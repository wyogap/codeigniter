<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Msite extends Mcrud_tablemeta
{
    protected static $TABLE_NAME = "tcg_site";
    protected static $PRIMARY_KEY = "siteid";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'nama';
    protected static $COL_VALUE = 'siteid';

    protected static $SOFT_DELETE = true;

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        $sql = "SELECT a.*, b.sitecode as parentcode, b.name as parentname, c.siteid as kotamaid, c.sitecode as kotamacode, c.name as kotamaname, 
            case when a.level=0 then concat(`a`.`siteid`) 
                when a.level=1 then concat(`c`.`siteid`,'-',lpad(`a`.`siteid`,3,'0')) 
                else concat(`c`.`siteid`,'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                end AS `compname`,
            d.name as orgid_label, b.name as parentid_label 
        FROM tcg_site a
        left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0
        left join tcg_site c on c.orgid=a.orgid and c.level=0 and c.is_deleted=0 and a.level>0
        left join tcg_organisation d on d.orgid=a.orgid and d.is_deleted=0
        where a.is_deleted=0";

        $query = $this->db->query($sql);

        $result = $query->result_array();
        if ($result == null) return $result;

        for($i=0; $i<count($result); $i++) {
            $coord = json_decode($result[$i]['latlong']);
            if ($coord == null) {
                $result[$i]['latitude'] = null;
                $result[$i]['longitude'] = null;    
            } else {
                $result[$i]['latitude'] = $coord[0];
                $result[$i]['longitude'] = $coord[1];
            }
        }

        return $result;
    }    

    function detail($key, $filter = null) {
        $this->reset_error();
              
        $sql = "SELECT a.*, b.sitecode as parentcode, b.name as parentname, c.siteid as kotamaid, c.sitecode as kotamacode, c.name as kotamaname, 
        case when a.level=0 then concat(`a`.`siteid`) 
            when a.level=1 then concat(`c`.`siteid`,'-',lpad(`a`.`siteid`,3,'0')) 
            else concat(`c`.`siteid`,'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
            end AS `compname`,
            d.name as orgid_label, b.name as parentid_label 
        FROM tcg_site a
        left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0
        left join tcg_site c on c.orgid=a.orgid and c.level=0 and c.is_deleted=0 and a.level>0
        left join tcg_organisation d on d.orgid=a.orgid and d.is_deleted=0
        where a.is_deleted=0 and a.siteid=?";
   
        $query = $this->db->query($sql, array($key));

        $result = $query->row_array();
        if ($result == null) return $result;

        $coord = json_decode($result['latlong']);
        if ($coord == null) {
            $result['latitude'] = null;
            $result['longitude'] = null;    
        } else {
            $result['latitude'] = $coord[0];
            $result['longitude'] = $coord[1];
        }
        
        return $result;
    }

    function add($valuepair, $enforce_edit_columns = true) {
        $this->reset_error();
        
        if (!empty($valuepair['latitude']) && !empty($valuepair['longitude'])) {
            $valuepair['latlong'] = '["' .$valuepair['latitude']. '","' .$valuepair['longitude']. '"]';
        }

        unset($valuepair['latitude']);
        unset($valuepair['longitude']);

        return parent::add($valuepair, $enforce_edit_columns);
    }

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        $this->reset_error();
        
        if (!empty($valuepair['latitude']) && !empty($valuepair['longitude'])) {
            $valuepair['latlong'] = '["' .$valuepair['latitude']. '","' .$valuepair['longitude']. '"]';
        }

        unset($valuepair['latitude']);
        unset($valuepair['longitude']);

        return parent::update($id, $valuepair, $filter, $enforce_edit_columns);
    }

    protected function __get_custom_column($columns) {
        //set latlong column to be visible for import
        for($i=0; $i<count($columns); $i++) {
            if ($columns[$i]['name'] == 'latlong') {
                $columns[$i]['visible'] = 1;
            }
        }

        return $columns;
    }

    protected function __update_custom_column($table_name) {
        //concat the coordinate
        $sql = "update " .$table_name. "
            set latlong = concat('[\"', latitude, '\",\"', longitude, '\"]')
            where latitude is not null and latitude!='' and longitude is not null and longitude!=''
        ";

        $this->db->query($sql);
    }

    function lookup($filter = null) {
        $this->reset_error();
        
        $orgid = $this->session->userdata('orgid');
        if (empty($orgid))  $orgid = '';

        $siteid = $this->session->userdata('siteid');
        if (empty($siteid)) $siteid = '';

        //filter by orgid (only for admin)
        if (""==$orgid && !empty($filter['orgid']))   $orgid=$filter['orgid'];

        $orgid = $this->db->escape($orgid);
        $siteid = $this->db->escape($siteid);

        $sql = "SELECT a.*
        from (
            SELECT a.siteid as value, a.name as label,  a.level - coalesce(d.level,0) as level,
                case when a.level=0 then concat(a.orgid,'-',lpad(`a`.`siteid`,3,'0')) 
                     when a.level=1 then concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                     else concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                     end AS `compname`
            FROM tcg_site a
            left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0 and (b.orgid=" .$orgid. " or ''=" .$orgid. ")
            left join tcg_site c on c.orgid=a.orgid and c.level=0 and c.is_deleted=0 and a.level>0 and (c.orgid=" .$orgid. " or ''=" .$orgid. ")
            left join tcg_site d on d.siteid=" .$siteid. " and d.is_deleted=0
            where a.is_deleted=0 and (c.siteid=" .$siteid. " or b.siteid=" .$siteid. " or a.siteid=" .$siteid. " or ''=" .$siteid. ") 
                and (a.orgid=" .$orgid. " or ''=" .$orgid. ")
        ) a order by a.compname asc;
        ";

        $query = $this->db->query($sql);
        if ($query == null)     return $query;

        return $query->result_array();
    }
}

  