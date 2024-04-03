<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mrencanadistribusi extends Mcrud_tablemeta
{

    function get_timeseries($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        $series = array();

        //usagerequest
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            SELECT MONTH(a.usagedate) as `month`, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            where a.is_deleted=0 
                -- and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
            group by MONTH(a.usagedate)
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            SELECT MONTH(a.usagedate) as `month`, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
            left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
            left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0 
                -- and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by MONTH(a.usagedate)
            ";
        }

        $result = $this->db->query($sql)->result_array();
        if ($result != null) {
            foreach($result as $key=>$arr) {
                $mon = $arr['month'];
                $val = $arr['count'];
                $data[$mon-1]+=$val;
            }
        }

        $series['usagerequest'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['usagerequest'][$i];
            $data[$i] = $total;
        }        

        $series['totalusagerequest'] = $data;

        //demand
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            select month(a.demanddate) as `month`, sum(b.count) as cnt 
            from rpt_demandinstance a
            join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
            where a.is_deleted=0
                and b.itemid=" .$itemid. " 
                and year(a.demanddate)=" .$year. "
            group by month(a.demanddate)
            ";
        } 
        else {
            $sql = "
            select month(a.demanddate) as `month`, sum(b.count) as cnt 
            from rpt_demandinstance a
            join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
            join tcg_demand c on c.demandid=a.demandid and c.is_deleted=0
            join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0
                and b.itemid=" .$itemid. " 
                and year(a.demanddate)=" .$year. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by month(a.demanddate)
            ";
        }

        $result = $this->db->query($sql)->result_array();
        if ($result != null) {
            foreach($result as $key=>$arr) {
                $mon = $arr['month'];
                $val = $arr['cnt'];
                $data[$mon-1]+=$val;
            }
        }

        $series['demand'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['demand'][$i];
            $data[$i] = $total;
        }        

        $series['totaldemand'] = $data;

        //po
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            select a.`month`, sum(a.cnt) as cnt
            from (
                select month(a.targetdeliverydate) as `month`, sum(b.count) as cnt
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                where a.status in ('APPR','TENDER') 
                    and a.is_deleted=0
                    and b.itemid=" .$itemid. "
                    and a.financial_year=" .$year. " and year(a.targetdeliverydate)=" .$year. "
                group by month(a.targetdeliverydate)

                union all
                
                select month(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate))) as `month`, sum(b.count) as cnt
                from tcg_po a
                join tcg_contract c on c.poid=a.poid and c.is_deleted=0
                join tcg_contractitem b on b.contractid=c.contractid and b.is_deleted=0
                where a.status not in ('DRAFT','APPR','TENDER')
                    and a.is_deleted=0
                    and b.itemid=" .$itemid. "
                    and a.financial_year=" .$year. " and year(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate)))=" .$year. "
                group by month(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate)))
            ) a
            group by a.`month`
            ";
        } 
        else {
            $sql = "
            select a.`month`, sum(a.cnt) as cnt
            from (
                select month(a.targetdeliverydate) as `month`, sum(b.count) as cnt
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                    where a.status in ('APPR','TENDER') 
                    and a.is_deleted=0
                    and b.itemid=" .$itemid. "
                    and a.financial_year=" .$year. " and year(a.targetdeliverydate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by month(a.targetdeliverydate)

                union all
                
                select month(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate))) as `month`, sum(b.count) as cnt
                from tcg_po a
                join tcg_contract c on c.poid=a.poid and c.is_deleted=0
                join tcg_contractitem b on b.contractid=c.contractid and b.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                    where a.status not in ('DRAFT','APPR','TENDER')
                    and a.is_deleted=0
                    and b.itemid=" .$itemid. "
                    and a.financial_year=" .$year. " and year(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate)))=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by month(coalesce(a.deliverydate,coalesce(c.targetdeliverydate, a.targetdeliverydate)))
            ) a
            group by a.`month`
            ";
        }

        $result = $this->db->query($sql)->result_array();
        if ($result != null) {
            foreach($result as $key=>$arr) {
                $mon = $arr['month'];
                $val = $arr['cnt'];
                $data[$mon-1]+=$val;
            }
        }

        $series['po'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['po'][$i];
            $data[$i] = $total;
        }        

        $series['totalpo'] = $data;

        //stock
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        $stock = 0;
        if (empty($siteid)) {
            $sql = "
            select sum(a.availableamount) as stock
            from rpt_invsnapshot a
            where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                and a.itemid=" .$itemid. "
            ";
        } 
        else {
            $sql = "
            select sum(a.availableamount) as stock
            from rpt_invsnapshot a
            join tcg_store b on b.storeid=a.storeid and a.is_deleted=0
            join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                and a.itemid=" .$itemid. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            ";
        }

        $result = $this->db->query($sql)->row_array();
        if ($result != null) {
            $stock = $result['stock'];
        }

        for($i=0; $i<12; $i++) {
            $data[$i] = $stock + $series['totalpo'][$i] - $series['totalusagerequest'][$i];
        }

        $series['totalstock'] = $data;

        return $series;
    }

    function get_po($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $sql = "
            select a.*, b.count
            from tcg_po a
            join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
            where a.status != 'DRAFT' and a.is_deleted=0
                and a.financial_year=" .$year. "
                and b.itemid=" .$itemid. "
            ";
        } 
        else {
            $sql = "
            select a.*, b.count
            from tcg_po a
            join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
            join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.status != 'DRAFT' and a.is_deleted=0
                and a.financial_year=" .$year. "
                and b.itemid=" .$itemid. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            ";
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function get_demand($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $sql = "
            select c.*, a.demanddate, b.count
            from rpt_demandinstance a
            join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
            join tcg_demand c on c.demandid=a.demandid
            where year(a.demanddate)=" .$year. " and a.is_deleted=0
                and b.itemid=" .$itemid. "
            ";
        } 
        else {
            $sql = "
            select c.*, a.demanddate, b.count
            from rpt_demandinstance a
            join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
            join tcg_demand c on c.demandid=a.demandid
            join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where year(a.demanddate)=" .$year. " and a.is_deleted=0
                and b.itemid=" .$itemid. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            ";
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function get_usagerequest($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $sql = "
            SELECT a.usagerequestid, a.usagerequestnum, a.status, a.usagedate, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            where a.is_deleted=0 
                -- and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
            group by a.usagerequestid, a.usagerequestnum
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            SELECT a.usagerequestid, a.usagerequestnum, a.status, a.usagedate, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
            left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
            left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0 
                -- and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by a.usagerequestid, a.usagerequestnum
            ";
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function get_stock($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $sql = "
            select b.storeid, b.storecode, b.description as storeid_label, sum(a.availableamount) as count
            from rpt_invsnapshot a
            join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
            where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                and a.itemid=" .$itemid. "
            group by a.storeid, b.storecode, b.description
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            select b.storeid, b.storecode, b.description as storeid_label, sum(a.availableamount) as count
            from rpt_invsnapshot a
            join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
            join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
            left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
            left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                and a.itemid=" .$itemid. "
                and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by a.storeid, b.storecode, b.description
            ";
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        $year = $this->db->escape(date('Y'));
        $siteid = null;
        $itemtypeid = null;
        $itemid = null;

        //default
        if (empty($filter['siteid'])) {
            $filter['siteid'] = $this->session->userdata("siteid");
        }

        if ($filter!=null) {
            if (!empty($filter['year']))        $year = $this->db->escape($filter['year']);
            if (!empty($filter['siteid']))      $siteid = $this->db->escape($filter['siteid']);
            if (!empty($filter['itemtypeid']))  $itemtypeid = $this->db->escape($filter['itemtypeid']);
            if (!empty($filter['itemid']))      $itemid = $this->db->escape($filter['itemid']);
        }
        
        $sql = "";
        if ($siteid == null) {
            $sql = "
            select
                " .$year. " as `year`, a.itemid, x.description as itemid_label
                , y.categoryid, x.description as categoryid_label
                , z.typeid as itemtypeid, z.typecode as itemtypeid_label
                , a.usagerequest, coalesce(c.stock,0) as stock, coalesce(d.po,0) as po
                , coalesce(c.stock,0)+coalesce(d.po,0)-a.usagerequest as remaining
                , coalesce(e.demand,0) as demand
            from 
            (
                SELECT b.itemid, sum(b.count) as usagerequest 
                FROM tcg_usagerequest a
                join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
                where a.is_deleted=0 
                    -- and a.status!='DRAFT'
                    and YEAR(a.usagedate)=" .$year. "
                group by b.itemid
            ) a
            left join (
                select a.itemid, sum(a.availableamount) as stock
                from rpt_invsnapshot a
                where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                group by a.itemid
            ) c on c.itemid=a.itemid
            left join (
                select b.itemid, sum(b.count) as po
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                where a.status != 'DRAFT' and a.is_deleted=0
                    and a.financial_year=" .$year. "
                group by b.itemid
            ) d on d.itemid=a.itemid
            left join (
                select b.itemid, sum(b.count) as demand
                from rpt_demandinstance a
                join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
                where year(a.demanddate)=" .$year. " and a.is_deleted=0
                group by b.itemid
            ) e on e.itemid=a.itemid
            join tcg_item x on x.itemid=a.itemid and x.is_deleted=0
            join tcg_itemcategory y on y.categoryid=x.categoryid and y.is_deleted=0
            join tcg_itemtype z on z.typeid=x.typeid and z.is_deleted=0
            where 1=1
            ";

            if ($itemtypeid != null) {
                $sql .= " and z.typeid=" .$itemtypeid;
            }

            if ($itemid != null) {
                $sql .= " and a.itemid=" .$itemid;
            }
        }
        else {
            $sql = "
            select
                " .$year. " as `year`, a.itemid, x.description as itemid_label, y.categoryid, x.description as categoryid_label
                , z.typeid as itemtypeid, z.typecode as itemtypeid_label
                , a.usagerequest, coalesce(c.stock,0) as stock, coalesce(d.po,0) as po
                , coalesce(c.stock,0)+coalesce(d.po,0)-a.usagerequest as remaining
                , coalesce(e.demand,0) as demand
            from (
                SELECT b.itemid, sum(b.count) as usagerequest 
                FROM tcg_usagerequest a
                join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 
                    -- and a.status!='DRAFT'
                    and YEAR(a.usagedate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) a
            left join (
                select a.itemid, sum(a.availableamount) as stock
                from rpt_invsnapshot a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site c on c.siteid=b.siteid and c.is_deleted=0
                left join tcg_site d on d.siteid=c.parentid and d.is_deleted=0
                left join tcg_site e on e.siteid=d.parentid and e.is_deleted=0
                where a.snapshotdate=concat(" .$year. ",'-01-01') and a.is_deleted=0
                    and (c.siteid=" .$siteid. " or d.siteid=" .$siteid. " or e.siteid=" .$siteid. ")
                group by a.itemid
            ) c on c.itemid=a.itemid
            left join (
                select b.itemid, sum(b.count) as po
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                join tcg_site c on c.siteid=a.siteid and c.is_deleted=0
                left join tcg_site d on d.siteid=c.parentid and d.is_deleted=0
                left join tcg_site e on e.siteid=d.parentid and e.is_deleted=0
                where a.status != 'DRAFT' and a.is_deleted=0
                    and a.financial_year=" .$year. "
                    and (c.siteid=" .$siteid. " or d.siteid=" .$siteid. " or e.siteid=" .$siteid. ")
                group by b.itemid
            ) d on d.itemid=a.itemid
            left join (
                select b.itemid, sum(b.count) as demand
                from rpt_demandinstance a
                join rpt_demandinstanceitem b on b.demandinstanceid=a.demandinstanceid and b.is_deleted=0
                join tcg_demand e on e.demandid=a.demandid and e.is_deleted=0
                join tcg_site f on f.siteid=e.siteid and f.is_deleted=0
                left join tcg_site g on g.siteid=f.parentid and g.is_deleted=0
                left join tcg_site h on h.siteid=g.parentid and h.is_deleted=0
                where year(a.demanddate)=" .$year. " and a.is_deleted=0
                    and (f.siteid=" .$siteid. " or g.siteid=" .$siteid. " or h.siteid=" .$siteid. ")
                group by b.itemid
            ) e on e.itemid=a.itemid
            join tcg_item x on x.itemid=a.itemid and x.is_deleted=0
            join tcg_itemcategory y on y.categoryid=x.categoryid and y.is_deleted=0
            join tcg_itemtype z on z.typeid=x.typeid and z.is_deleted=0
            where 1=1
            ";

            if ($itemtypeid != null) {
                $sql .= " and z.typeid=" .$itemtypeid;
            }

            if ($itemid != null) {
                $sql .= " and a.itemid=" .$itemid;
            }
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        //remaining -> gap must be negative number, otherwise it is over supply
        for($i=0; $i<count($arr); $i++) {
            if ($arr[$i]['remaining']>0)    $arr[$i]['remaining']=0;
        }        

        return $arr;
    }    

    function detail($itemid, $filter = null) {
        $this->reset_error();
        
        if ($filter == null)    $filter = array();

        //add filter based on key
        $filter['itemid'] = $itemid;

        //default filter
        if (empty($filter['year']))    $filter['year'] = date('Y');

        $arr = $this->list($filter);
        
        if ($arr == null || count($arr) == 0)       return $arr;

        return $arr[0];
    }

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        return 0;
    }

    function delete($id, $filter = null) {
        return 0;
    }

    function add($valuepair, $enforce_edit_columns = true) {
        return 0;
    }
}

  