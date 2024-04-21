<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Manalisastok extends Mcrud_tablemeta
{

    function get_timeseries($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $siteid = $this->session->userdata("siteid");
        }

        $series = array();

        //bekalmasuk
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            select MONTH(a.deliverydate) as `month`, sum(count) as cnt
            from (
                /* pengadaan */
                select 
                    a.poid, a.ponum, coalesce(c.contractid) as contractid, coalesce(c.contractnum) as contractnum
                    , a.status
                    , case 
                        -- sudah delivery
                        when c.status='COMP' or c.status='CLOSED' then c.completeddate
                        -- belum/sedang delivery
                        when c.status='APPR' then c.targetdeliverydate
                        -- belum kontrak
                        else a.targetdeliverydate
                    end as deliverydate
                    , sum(
                        case when c.status is not null and c.status!='DRAFT' then coalesce(d.count,0)
                        else coalesce(b.count,0)
                        end
                    ) as count
                from tcg_po a
                left join (
                    select b.poid, sum(a.count) as count
                    from tcg_poitem a
                    join tcg_po b on b.poid=a.poid and b.is_deleted=0 and b.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and b.financial_year=" .$year. "
                    group by a.poid
                ) b on b.poid=a.poid
                left join tcg_contract c on c.poid=a.poid and c.is_deleted=0 and c.status!='DRAFT'
                left join (
                    select b.contractid, sum(a.count) as count
                    from tcg_contractitem a
                    join tcg_contract b on b.contractid=a.contractid and b.is_deleted=0 and b.status!='DRAFT'
                    join tcg_po c on c.poid=b.poid and c.is_deleted=0 and c.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and c.financial_year=" .$year. "
                    group by b.contractid
                ) d on d.contractid=c.contractid
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
                group by a.poid, a.ponum, c.contractid, c.contractnum

                union all

                /* langsung invreceive */
                SELECT null as poid, ponum, null as contractid, contractnum
                    , 'COMP' as status
                    , a.receiveddate as deliverydate
                    , sum(a.receivedamount) as sum
                FROM tcg_invreceive a
                where a.is_deleted=0 and a.poid is null
                    and a.status!='DRAFT'
                    and a.itemid=" .$itemid. "
                    and YEAR(a.receiveddate)=" .$year. "
                group by ponum, contractnum
            ) a
            where a.count>0
            group by MONTH(a.deliverydate)            
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            select MONTH(a.deliverydate) as `month`, sum(count) as cnt
            from (
                select 
                    a.poid, a.ponum, coalesce(c.contractid) as contractid, coalesce(c.contractnum) as contractnum
                    , case 
                        -- sudah delivery
                        when c.status='COMP' or c.status='CLOSED' then c.completeddate
                        -- belum/sedang delivery
                        when c.status='APPR' then c.targetdeliverydate
                        -- belum kontrak
                        else a.targetdeliverydate
                    end as deliverydate
                    , sum(
                        case when c.status is not null and c.status!='DRAFT' then coalesce(d.count,0)
                        else coalesce(b.count,0)
                        end
                    ) as count
                from tcg_po a
                left join (
                    select b.poid, sum(a.count) as count
                    from tcg_poitem a
                    join tcg_po b on b.poid=a.poid and b.is_deleted=0 and b.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and b.financial_year=" .$year. "
                    group by a.poid
                ) b on b.poid=a.poid
                left join tcg_contract c on c.poid=a.poid and c.is_deleted=0 and c.status!='DRAFT'
                left join (
                    select b.contractid, sum(a.count) as count
                    from tcg_contractitem a
                    join tcg_contract b on b.contractid=a.contractid and b.is_deleted=0 and b.status!='DRAFT'
                    join tcg_po c on c.poid=b.poid and c.is_deleted=0 and c.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and c.financial_year=" .$year. "
                    group by b.contractid
                ) d on d.contractid=c.contractid
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.poid, a.ponum, c.contractid, c.contractnum

                union all

                SELECT null as poid, ponum, null as contractid, contractnum
                    , a.receiveddate as deliverydate
                    , sum(a.receivedamount) as count
                FROM tcg_invreceive a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.poid is null
                    and a.status!='DRAFT'
                    and a.itemid=" .$itemid. "
                    and YEAR(a.receiveddate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by ponum, contractnum           
            ) a
            where a.count>0
            group by MONTH(a.deliverydate)            
            ";
        }

        $result = $this->db->query($sql)->result_array();

        //var_dump($result); exit;
        if ($result != null) {
            foreach($result as $key=>$arr) {
                $mon = intval($arr['month']);
                if ($mon==0) continue;
                $val = $arr['cnt'];
                $data[$mon-1]+=$val;
            }
        }
        
        $series['bekalmasuk'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['bekalmasuk'][$i];
            $data[$i] = $total;
        }        

        $series['totalbekalmasuk'] = $data;

        //bekalkeluar
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            SELECT MONTH(a.usagedate) as `month`, sum(b.count) as cnt 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            where a.is_deleted=0 and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
            group by MONTH(a.usagedate)
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            SELECT MONTH(a.usagedate) as `month`, sum(b.count) as cnt 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
            left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
            left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0 and a.status!='DRAFT'
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
                $val = $arr['cnt'];
                $data[$mon-1]+=$val;
            }
        }

        $series['bekalkeluar'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['bekalkeluar'][$i];
            $data[$i] = $total;
        }        

        $series['totalbekalkeluar'] = $data;

        //transfer
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (!empty($siteid)) {
            $sql = "
            select MONTH(a.transferdate) as `month`, sum(count) as cnt
            from (
                SELECT a.transferrequestid, a.transferrequestnum, a.transferdate
                    , a.tostoreid as fromto_storeid, d.storecode as fromto_storecode, sum(-1 * b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                join tcg_store c on c.storeid=a.fromstoreid and c.is_deleted=0
                join tcg_store d on d.storeid=a.tostoreid and d.is_deleted=0
                join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and b.itemid=" .$itemid. "
                    and YEAR(a.transferdate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.transferrequestid, a.transferrequestnum, a.tostoreid, d.storecode
        
                union all
        
                SELECT a.transferrequestid, a.transferrequestnum, a.transferdate
                    , a.fromstoreid as fromto_storeid, c.storecode as fromto_storecode, sum(b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                join tcg_store c on c.storeid=a.fromstoreid and c.is_deleted=0
                join tcg_store d on d.storeid=a.tostoreid and d.is_deleted=0
                join tcg_site x on x.siteid=d.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and b.itemid=" .$itemid. "
                    and YEAR(a.transferdate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.transferrequestid, a.transferrequestnum, a.fromstoreid, c.storecode
            ) a
            group by MONTH(a.transferdate)
            ";

            $result = $this->db->query($sql)->result_array();
            if ($result != null) {
                foreach($result as $key=>$arr) {
                    $mon = $arr['month'];
                    $val = $arr['cnt'];
                    $data[$mon-1]+=$val;
                }
            }        
        }

        $series['bekaltransfer'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['bekaltransfer'][$i];
            $data[$i] = $total;
        }        

        $series['totalbekaltransfer'] = $data;

        //hapusbekal
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        if (empty($siteid)) {
            $sql = "
            SELECT MONTH(a.writeoffdate) as `month`, sum(a.count) as cnt 
            FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
            where a.is_deleted=0
                and a.writeoff=1
                and b.itemid=" .$itemid. "
                and YEAR(a.writeoffdate)=" .$year. "
            group by MONTH(a.writeoffdate)
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);

            $sql = "
            SELECT MONTH(a.writeoffdate) as `month`, sum(a.count) as cnt 
            FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
            join tcg_store c on c.storeid=b.storeid and c.is_deleted=0
            join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0
                and a.writeoff=1
                and b.itemid=" .$itemid. "
                and YEAR(a.writeoffdate)=" .$year. "
			and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by MONTH(a.writeoffdate)
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

        $series['hapusbekal'] = $data;
 
        $total = 0;
        $data = [ 0,0,0,0,0,0,0,0,0,0,0,0 ];
        for($i=0; $i<12; $i++) {
            $total += $series['hapusbekal'][$i];
            $data[$i] = $total;
        }        

        $series['totalhapusbekal'] = $data;

        //estimasi stock
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
            join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
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
            $data[$i] = $stock + $series['totalbekalmasuk'][$i] - $series['totalbekalkeluar'][$i] + $series['totalbekaltransfer'][$i] - $series['totalhapusbekal'][$i];
        }

        $series['totalstock'] = $data;

        return $series;
    }

    function get_hapusbekal($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $siteid = $this->session->userdata("siteid");
        }

        if (empty($siteid)) {
            $sql = "
            SELECT a.writeoffdate, a.status, sum(a.count) as count 
            FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
            where a.is_deleted=0
                and a.writeoff=1
                and b.itemid=" .$itemid. "
                and YEAR(a.writeoffdate)=" .$year. "
            group by a.writeoffdate, a.status
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);

            $sql = "
            SELECT a.writeoffdate, a.status, sum(a.count) as count 
            FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
            join tcg_store c on c.storeid=b.storeid and c.is_deleted=0
            join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
			left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
			left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0
                and a.writeoff=1
                and b.itemid=" .$itemid. "
                and YEAR(a.writeoffdate)=" .$year. "
			and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            group by a.writeoffdate, a.status
            ";
        }

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function get_bekaltransfer($itemid, $siteid, $year) {
        if (empty($siteid)) {
            $siteid = $this->session->userdata("siteid");
        }

        if (empty($siteid)) {
            return array();
        }

        $itemid = $this->db->escape($itemid);
        $siteid = $this->db->escape($siteid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        $sql = "
        SELECT a.transferrequestid, a.transferrequestnum, a.transferdate
            , a.tostoreid as fromto_storeid, d.storecode as fromto_storecode, sum(-1 * b.count) as count 
        FROM tcg_transferrequest a
        join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
        join tcg_store c on c.storeid=a.fromstoreid and c.is_deleted=0
        join tcg_store d on d.storeid=a.tostoreid and d.is_deleted=0
        join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
        left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
        left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
        where a.is_deleted=0
            and a.status!='DRAFT'
            and b.itemid=" .$itemid. "
            and YEAR(a.transferdate)=" .$year. "
            and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
        group by a.transferrequestid, a.transferrequestnum, a.tostoreid, d.storecode

        union all

        SELECT a.transferrequestid, a.transferrequestnum, a.transferdate
            , a.fromstoreid as fromto_storeid, c.storecode as fromto_storecode, sum(b.count) as count 
        FROM tcg_transferrequest a
        join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
        join tcg_store c on c.storeid=a.fromstoreid and c.is_deleted=0
        join tcg_store d on d.storeid=a.tostoreid and d.is_deleted=0
        join tcg_site x on x.siteid=d.siteid and x.is_deleted=0
        left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
        left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
        where a.is_deleted=0
            and a.status!='DRAFT'
            and b.itemid=" .$itemid. "
            and YEAR(a.transferdate)=" .$year. "
            and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
        group by a.transferrequestid, a.transferrequestnum, a.fromstoreid, c.storecode
        ";

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null)       return $arr;

        return $arr;
    }

    function get_bekalkeluar($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $siteid = $this->session->userdata("siteid");
        }

        if (empty($siteid)) {
            $sql = "
            SELECT a.usagerequestid, a.usagerequestnum, a.usagedate, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            where a.is_deleted=0 and a.status!='DRAFT'
                and b.itemid=" .$itemid. "
                and YEAR(a.usagedate)=" .$year. "
            group by a.usagerequestid, a.usagerequestnum
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            SELECT a.usagerequestid, a.usagerequestnum, a.usagedate, sum(b.count) as count 
            FROM tcg_usagerequest a
            join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
            join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
            left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
            left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
            where a.is_deleted=0 and a.status!='DRAFT'
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

    function get_bekalmasuk($itemid, $siteid, $year) {
        $itemid = $this->db->escape($itemid);

        if (empty($year))   $year = date('Y');
        $year = $this->db->escape($year);

        if (empty($siteid)) {
            $siteid = $this->session->userdata("siteid");
        }

        if (empty($siteid)) {
            $sql = "
            select *
            from (
                select 
                    a.poid, a.ponum, coalesce(c.contractid) as contractid, coalesce(c.contractnum) as contractnum
                    , a.status
                    , case 
                        -- sudah delivery
                        when c.status='COMP' or c.status='CLOSED' then c.completeddate
                        -- belum/sedang delivery
                        when c.status='APPR' then c.targetdeliverydate
                        -- belum kontrak
                        else a.targetdeliverydate
                    end as deliverydate
                    , sum(
                        case when c.status is not null and c.status!='DRAFT' then coalesce(d.count,0)
                        else coalesce(b.count,0)
                        end
                    ) as count
                from tcg_po a
                left join (
                    select b.poid, sum(a.count) as count
                    from tcg_poitem a
                    join tcg_po b on b.poid=a.poid and b.is_deleted=0 and b.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and b.financial_year=" .$year. "
                    group by a.poid
                ) b on b.poid=a.poid
                left join tcg_contract c on c.poid=a.poid and c.is_deleted=0 and c.status!='DRAFT'
                left join (
                    select b.contractid, sum(a.count) as count
                    from tcg_contractitem a
                    join tcg_contract b on b.contractid=a.contractid and b.is_deleted=0 and b.status!='DRAFT'
                    join tcg_po c on c.poid=b.poid and c.is_deleted=0 and c.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and c.financial_year=" .$year. "
                    group by b.contractid
                ) d on d.contractid=c.contractid
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
                group by a.poid, a.ponum, c.contractid, c.contractnum

                union all

                SELECT null as poid, ponum, null as contractid, contractnum
                    ,'COMP' as status
                    , a.receiveddate, sum(a.receivedamount)
                FROM tcg_invreceive a
                where a.is_deleted=0 and a.poid is null
                    and a.status!='DRAFT'
                    and a.itemid=" .$itemid. "
                    and YEAR(a.receiveddate)=" .$year. "
                group by ponum, contractnum         
            ) a
            where a.count>0
            ";
        }
        else {
            $siteid = $this->db->escape($siteid);
            $sql = "
            select *
            from (
                select 
                    a.poid, a.ponum, coalesce(c.contractid) as contractid, coalesce(c.contractnum) as contractnum
                    , a.status
                    , case 
                        -- sudah delivery
                        when c.status='COMP' or c.status='CLOSED' then c.completeddate
                        -- belum/sedang delivery
                        when c.status='APPR' then c.targetdeliverydate
                        -- belum kontrak
                        else a.targetdeliverydate
                    end as deliverydate
                    , sum(
                        case when c.status is not null and c.status!='DRAFT' then coalesce(d.count,0)
                        else coalesce(b.count,0)
                        end
                    ) as count
                from tcg_po a
                left join (
                    select b.poid, sum(a.count) as count
                    from tcg_poitem a
                    join tcg_po b on b.poid=a.poid and b.is_deleted=0 and b.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and b.financial_year=" .$year. "
                    group by a.poid
                ) b on b.poid=a.poid
                left join tcg_contract c on c.poid=a.poid and c.is_deleted=0 and c.status!='DRAFT'
                left join (
                    select b.contractid, sum(a.count) as count
                    from tcg_contractitem a
                    join tcg_contract b on b.contractid=a.contractid and b.is_deleted=0 and b.status!='DRAFT'
                    join tcg_po c on c.poid=b.poid and c.is_deleted=0 and c.status!='DRAFT'
                    where a.is_deleted=0
                        and a.itemid=" .$itemid. " and c.financial_year=" .$year. "
                    group by b.contractid
                ) d on d.contractid=c.contractid
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.poid, a.ponum, c.contractid, c.contractnum

                union all

                SELECT null as poid, ponum, null as contractid, contractnum
                    , 'COMP' as status
                    , a.receiveddate, sum(a.receivedamount)
                FROM tcg_invreceive a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.poid is null
                    and a.status!='DRAFT'
                    and a.itemid=" .$itemid. "
                    and YEAR(a.receiveddate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by ponum, contractnum            
            ) a
            where a.count>0
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
            $siteid = $this->session->userdata("siteid");
        }

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
            if (!empty($filter['itemtypeid']))  $itemtypeid = $this->db->escape($filter['itemtypeid']);
            if (!empty($filter['itemid']))      $itemid = $this->db->escape($filter['itemid']);
            if (!empty($filter['siteid']))      $siteid = $this->db->escape($filter['siteid']);
        }
        
        $sql = "";
        if ($siteid == null) {
            $sql = "
            select " .$year. " as `year`, a.itemid, x.description as itemid_label
                , y.categoryid, x.description as categoryid_label
                , z.typeid as itemtypeid, z.typecode as itemtypeid_label
                , coalesce(b.stock,0) as stock
                , coalesce(c.count,0)+coalesce(d.count,0) as stockin
                , coalesce(e.count,0)+coalesce(f.count,0) as purchasing
                , coalesce(g.count,0) as stockout
                , coalesce(h.count,0) as transferout
                , coalesce(i.count,0) as transferin
                , coalesce(i.count,0)-coalesce(h.count,0) as transfer
                , coalesce(j.count,0) as writeoff
            from (
                /* yang ada di stock -> invusage sudah tercover di sini*/
                SELECT distinct a.itemid
                FROM rpt_invsnapshot a
                where a.is_deleted=0 /* and a.poid is null */
                    and a.snapshotdate=concat(" .$year. ",'-01-01')
            
                union
            
                SELECT distinct a.itemid
                FROM tcg_invreceive a
                where a.is_deleted=0 and a.status!='DRAFT'
                    and YEAR(a.receiveddate)=" .$year. "
                    
                union
            
                select distinct b.itemid
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
            ) a
            left join (
                /* stock awal tahun */
                select a.itemid, sum(a.availableamount) as stock
                from rpt_invsnapshot a
                where a.is_deleted=0
                    and a.snapshotdate=concat(" .$year. ",'-01-01')
                group by a.itemid
            ) b on b.itemid=a.itemid
            left join (
                /* bekal masuk tanpa PO (initial data) */
                select a.itemid, sum(a.receivedamount) as count
                from tcg_invreceive a
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.receiveddate=concat(" .$year. ",'-01-01')
                group by a.itemid
            ) c on c.itemid=a.itemid
            left join (
                /* bekal masuk: delivered -> po yang sudah COMP / CLOSED pasti sudah lewat ini*/
                select b.itemid, sum(b.count) as count
                from tcg_contract a
                join tcg_contractitem b on b.contractid=a.contractid and b.is_deleted=0
                join tcg_po c on c.poid=a.poid and c.is_deleted=0
                where a.is_deleted=0
                    /* contract status -> sudah selesai */ 
                    and (a.status='COMP' or a.status='CLOSED')
                    and c.financial_year=" .$year. "
                group by b.itemid
            ) d on d.itemid=a.itemid
            left join (
                /* bekal masuk: belum/sedang delivery */
                select b.itemid, sum(b.count) as count
                from tcg_contract a
                join tcg_contractitem b on b.contractid=a.contractid and b.is_deleted=0
                join tcg_po c on c.poid=a.poid and c.is_deleted=0
                where a.is_deleted=0 
                    /* contract status -> belum selesai */ 
                    and a.status='APPR'
                    and c.financial_year=" .$year. "
                group by b.itemid
            ) e on e.itemid=a.itemid
            left join (
                /* bekal masuk: belum kontrak */
                select b.itemid, sum(b.count) as count
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                left join tcg_contract c on c.contractid=a.contractid and c.is_deleted=0
                where a.is_deleted=0 
                    and (
                        /* po belum/dalam tender */
                        a.status in ('APPR', 'TENDER') 
                        /* po sudah contrak (tender selesai) tapi contract belum disetujui */
                        or (a.status = 'CONTRACT' and c.status = 'DRAFT')
                    )
                    and a.financial_year=" .$year. "
                group by b.itemid
            ) f on f.itemid=a.itemid
            left join (
                /* bekal keluar */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_usagerequest a
                join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and YEAR(a.usagedate)=" .$year. "
                group by b.itemid
            ) g on g.itemid=a.itemid
            left join (
                /* transfer out */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and YEAR(a.transferdate)=" .$year. "
                group by b.itemid
            ) h on h.itemid=a.itemid
            left join (
                /* transfer-in */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and YEAR(a.transferdate)=" .$year. "
                group by b.itemid
            ) i on i.itemid=a.itemid
            left join (
                /* hapus buku */
                SELECT b.itemid, sum(a.count) as count 
                FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
                where a.is_deleted=0
                    and a.writeoff=1
                    and YEAR(a.writeoffdate)=" .$year. "
                group by b.itemid
            ) j on j.itemid=a.itemid
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
            select " .$year. " as `year`, a.itemid, x.description as itemid_label
                , y.categoryid, x.description as categoryid_label
                , z.typeid as itemtypeid, z.typecode as itemtypeid_label
                , coalesce(b.stock,0) as stock
                , coalesce(c.count,0)+coalesce(d.count,0) as stockin
                , coalesce(e.count,0)+coalesce(f.count,0) as purchasing
                , coalesce(g.count,0) as stockout
                , coalesce(h.count,0) as transferout
                , coalesce(i.count,0) as transferin
                , coalesce(i.count,0)-coalesce(h.count,0) as transfer
                , coalesce(j.count,0) as writeoff
            from (
                SELECT distinct a.itemid
                FROM rpt_invsnapshot a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 /* and a.poid is null */
                    and a.snapshotdate=concat(" .$year. ",'-01-01')
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            
                union
            
                SELECT distinct a.itemid
                FROM tcg_invreceive a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and YEAR(a.receiveddate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                    
                union
            
                select distinct b.itemid
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
            ) a
            left join (
                /* stock awal tahun */
                select a.itemid, sum(a.availableamount) as stock
                from rpt_invsnapshot a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.snapshotdate=concat(" .$year. ",'-01-01')
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.itemid
            ) b on b.itemid=a.itemid
            left join (
                /* bekal masuk tanpa PO (initial data) */
                select a.itemid, sum(a.receivedamount) as count
                from tcg_invreceive a
                join tcg_store b on b.storeid=a.storeid and b.is_deleted=0
                join tcg_site x on x.siteid=b.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and a.receiveddate=concat(" .$year. ",'-01-01')
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by a.itemid
            ) c on c.itemid=a.itemid
            left join (
                /* bekal masuk: delivered -> po yang sudah COMP / CLOSED pasti sudah lewat ini*/
                select b.itemid, sum(b.count) as count
                from tcg_contract a
                join tcg_contractitem b on b.contractid=a.contractid and b.is_deleted=0
                join tcg_po c on c.poid=a.poid and c.is_deleted=0
                join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 
                    /* contract status -> sudah selesai */ 
                    and (a.status='COMP' or a.status='CLOSED')
                    and c.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) d on d.itemid=a.itemid
            left join (
                /* bekal masuk: belum/sedang delivery */
                select b.itemid, sum(b.count) as count
                from tcg_contract a
                join tcg_contractitem b on b.contractid=a.contractid and b.is_deleted=0
                join tcg_po c on c.poid=a.poid and c.is_deleted=0
                join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 
                    /* contract status -> belum selesai */ 
                    and a.status='APPR'
                    and c.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) e on e.itemid=a.itemid
            left join (
                /* bekal masuk: belum kontrak */
                select b.itemid, sum(b.count) as count
                from tcg_po a
                join tcg_poitem b on b.poid=a.poid and b.is_deleted=0
                left join tcg_contract c on c.contractid=a.contractid and c.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 
                    and (
                        /* po belum/dalam tender */
                        a.status in ('APPR', 'TENDER') 
                        /* po sudah contrak (tender selesai) tapi contract belum disetujui */
                        or (a.status = 'CONTRACT' and c.status = 'DRAFT')
                    )
                    and a.financial_year=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) f on f.itemid=a.itemid
            left join (
                /* bekal keluar */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_usagerequest a
                join tcg_usagerequestitem b on b.usagerequestid=a.usagerequestid and b.is_deleted=0
                join tcg_site x on x.siteid=a.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0 and a.status!='DRAFT'
                    and YEAR(a.usagedate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) g on g.itemid=a.itemid
            left join (
                /* transfer out */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                join tcg_store c on c.storeid=a.fromstoreid and c.is_deleted=0
                join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and YEAR(a.transferdate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) h on h.itemid=a.itemid
            left join (
                /* transfer-in */
                SELECT b.itemid, sum(b.count) as count 
                FROM tcg_transferrequest a
                join tcg_transferrequestitem b on b.transferrequestid=a.transferrequestid and b.is_deleted=0
                join tcg_store d on d.storeid=a.tostoreid and d.is_deleted=0
                join tcg_site x on x.siteid=d.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.status!='DRAFT'
                    and YEAR(a.transferdate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) i on i.itemid=a.itemid
            left join (
                /* hapus buku */
                SELECT b.itemid, sum(a.count) as count 
                FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
                join tcg_store c on c.storeid=b.storeid and c.is_deleted=0
                join tcg_site x on x.siteid=c.siteid and x.is_deleted=0
                left join tcg_site y on y.siteid=x.parentid and y.is_deleted=0
                left join tcg_site z on z.siteid=y.parentid and z.is_deleted=0
                where a.is_deleted=0
                    and a.writeoff=1
                    and YEAR(a.writeoffdate)=" .$year. "
                    and (x.siteid=" .$siteid. " or y.siteid=" .$siteid. " or z.siteid=" .$siteid. ")
                group by b.itemid
            ) j on j.itemid=a.itemid
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
            $arr[$i]['remaining'] = $arr[$i]['stock'] 
                    + $arr[$i]['stockin'] + $arr[$i]['purchasing'] 
                    - $arr[$i]['stockout'] + $arr[$i]['transfer'] - $arr[$i]['writeoff'];
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

  