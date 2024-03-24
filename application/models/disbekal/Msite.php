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

    function get_allowed_siteids() {
        $user_siteid = $this->session->userdata("siteid");

        if (empty($user_siteid)) {
            $sql = "select siteid from tcg_site a where a.is_deleted=0";
        }
        else {
            $user_siteid = $this->db->escape($user_siteid);
            $sql = "
            select a.siteid 
            from tcg_site a
            left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0
            left join tcg_site c on c.siteid=b.parentid and c.is_deleted=0
            where a.is_deleted=0
                and (a.siteid=" .$user_siteid. " or b.siteid=" .$user_siteid. " or c.siteid=" .$user_siteid. ")
                ";
        }

        $siteids = array();

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null) return $siteids;

        foreach($arr as $s) {
            $siteids[] = $s['siteid'];
        }

        return $siteids;
    }

    function check_siteid($siteid) {
        $user_siteid = $this->session->userdata("siteid");

        if (empty($siteid) && empty($user_siteid))  return null;
        if (empty($user_siteid) && !empty($siteid)) return $siteid;
        if (!empty($user_siteid) && empty($siteid)) return $user_siteid;
        if ($siteid == $user_siteid)    return $siteid;

        //TODO: store list of allowed siteid in session for easy access
        //check if siteid is below user_siteid
        $siteid = $this->db->escape($siteid);
        $user_siteid = $this->db->escape($user_siteid);

        $sql = "
        select count(*) as cnt
        from tcg_site a
        left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0
        left join tcg_site c on c.siteid=b.parentid and c.is_deleted=0
        where a.is_deleted=0
            and a.siteid=" .$siteid. "
            and (b.siteid=" .$user_siteid. " or c.siteid=" .$user_siteid. ")
            ";

        $arr = $this->db->query($sql)->row_array();

        if (empty($arr["cnt"])) {
            //siteid not under user_siteid
            return $user_siteid;
        }  

        //siteid under user_siteid
        return $siteid;
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        //TODO: implement other filters!

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
              
        //TODO: use list() for consistency

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
        
        $siteid = $this->session->userdata("siteid");
        if (!empty($filter['siteid'])) {
            $this->load->model('disbekal/Msite');
            $siteid = $this->Msite->check_siteid($filter['siteid']);
        }
        unset($filter['siteid']);

        $this->db->select("a.siteid as value, a.name as label");
        if (empty($siteid)) {
            $this->db->select("a.level");
        }
        else {
            $this->db->select("a.level - coalesce(d.level,0) as level");
        }
        $this->db->select("case when a.level=0 then concat(a.orgid,'-',lpad(`a`.`siteid`,3,'0')) 
                                when a.level=1 then concat(a.orgid,'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                                else concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
                            end AS `compname`");
        $this->db->from("tcg_site a");
        $this->db->where("a.is_deleted=0");
        $this->db->join("tcg_site b", "b.siteid=a.parentid and b.is_deleted=0", "LEFT OUTER");
        $this->db->join("tcg_site c", "c.siteid=b.parentid and c.is_deleted=0", "LEFT OUTER");

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $this->db->where("a.$key", $val);
            }
        }
        if (!empty($siteid)) {
            $this->db->join("tcg_site d", "d.siteid=" .$this->db->escape($siteid). " and b.is_deleted=0", "LEFT OUTER");
            $this->db->group_start();
            $this->db->where("a.siteid", $siteid);
            $this->db->or_where("b.siteid", $siteid);
            $this->db->or_where("c.siteid", $siteid);
            $this->db->group_end();
        }

        $subquery1 = $this->db->get_compiled_select();

        $this->db->reset_query();
        
        $query = $this->db->query("select a.* from (" .$subquery1. ") a order by a.compname asc");
        if ($query == null)     return $query;

        return $query->result_array();

        // $orgid = $this->session->userdata('orgid');
        // if (empty($orgid))  $orgid = '';

        // $siteid = $this->session->userdata('siteid');
        // if (empty($siteid)) $siteid = '';

        // //filter by orgid (only for admin)
        // if (""==$orgid && !empty($filter['orgid']))   $orgid=$filter['orgid'];

        // $orgid = $this->db->escape($orgid);
        // $siteid = $this->db->escape($siteid);

        // $sql = "SELECT a.*
        // from (
        //     SELECT a.siteid as value, a.name as label,  a.level - coalesce(d.level,0) as level,
        //         case when a.level=0 then concat(a.orgid,'-',lpad(`a`.`siteid`,3,'0')) 
        //              when a.level=1 then concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
        //              else concat(a.orgid,'-',lpad(`c`.`siteid`,3,'0'),'-',lpad(`b`.`siteid`,3,'0'),'-',lpad(`a`.`siteid`,3,'0')) 
        //              end AS `compname`
        //     FROM tcg_site a
        //     left join tcg_site b on b.siteid=a.parentid and b.is_deleted=0 and (b.orgid=" .$orgid. " or ''=" .$orgid. ")
        //     left join tcg_site c on c.orgid=a.orgid and c.level=0 and c.is_deleted=0 and a.level>0 and (c.orgid=" .$orgid. " or ''=" .$orgid. ")
        //     left join tcg_site d on d.siteid=" .$siteid. " and d.is_deleted=0
        //     where a.is_deleted=0 and (c.siteid=" .$siteid. " or b.siteid=" .$siteid. " or a.siteid=" .$siteid. " or ''=" .$siteid. ") 
        //         and (a.orgid=" .$orgid. " or ''=" .$orgid. ")
        // ) a order by a.compname asc;
        // ";

        // $query = $this->db->query($sql);
        // if ($query == null)     return $query;

        // return $query->result_array();
    }
}

  