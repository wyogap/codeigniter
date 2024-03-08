<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdashboard extends CI_Model
{
    public function detailstok() {
        //TODO
    }

    public function detailtransaksi($periode=null, $offset=null) {
        //TODO
    }

    public function nilaistok($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT sum(a.price * a.currentstock) as nilai_total 
            FROM tcg_inventory a
            where a.is_deleted=0 and a.status='INSTOCK'";    
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT sum(a.price * a.currentstock) as nilai_total 
            FROM tcg_inventory a
            where a.is_deleted=0 and a.status='INSTOCK' and (a.storeid=" .$storeidstr. ")";    
        }

        $total = 0;
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if ($data != null) {
            $total = empty($data['nilai_total']) ? 0 : $data['nilai_total'];
        }

        if (empty($storeid)) {
            $sql = "SELECT a.`status`, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
            where a.is_deleted=0 and a.writeoff=0
            group by a.`status`";
        } 
        else {
            $sql = "SELECT a.`status`, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
            where a.is_deleted=0 and a.writeoff=0 and (b.storeid=" .$storeidstr. ")
            group by a.`status`";
        } 

        $rusak = $kadaluarsa = 0;
        $query = $this->db->query($sql);
        if ($query != null) {
            $data = $query->result_array();
            foreach($data as $row) {
                if ($row['status'] == 'BROKEN') {
                    $rusak = $row['nilai_total'];
                }
                else if ($row['status'] == 'EXPIRED') {
                    $kadaluarsa = $row['nilai_total'];
                }
            }
        }

        $retval = array();
        $retval['total'] = $total;
        $retval['rusak'] = $rusak;
        $retval['kadaluarsa'] = $kadaluarsa;

        return $retval;
    }

    public function itemstok($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT count(distinct itemid) as cnt FROM rpt_lowstock a
            where a.sitecode is null and a.is_deleted=0";    
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT count(distinct itemid) as cnt FROM rpt_lowstock a
            where a.sitecode is null and a.is_deleted=0 and (a.storeid=" .$storeidstr. ")";    
        }

        $hampirhabis = 0;
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if ($data != null) {
            $hampirhabis = empty($data['cnt']) ? 0 : $data['cnt'];
        }

        if (empty($storeid)) {
            $sql = "SELECT a.status, count(distinct a.itemid) as cnt FROM `tcg_invstatus` a 
            where a.writeoff=0 and a.is_deleted=0 
            group by a.status";
        } 
        else {
            $sql = "SELECT a.status, count(distinct a.itemid) as cnt FROM `tcg_invstatus` a
            join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0 
            where a.writeoff=0 and a.is_deleted=0 and (b.storeid=" .$storeidstr. ")
            group by a.status";
        } 

        $rusak = $kadaluarsa = 0;
        $query = $this->db->query($sql);
        if ($query != null) {
            $data = $query->result_array();
            foreach($data as $row) {
                if ($row['status'] == 'BROKEN') {
                    $rusak = $row['cnt'];
                }
                else if ($row['status'] == 'EXPIRED') {
                    $kadaluarsa = $row['cnt'];
                }
            }
        }

        $retval = array();
        $retval['hampirhabis'] = $hampirhabis;
        $retval['rusak'] = $rusak;
        $retval['kadaluarsa'] = $kadaluarsa;

        return $retval;
    }

    public function stokpergudang($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.storeid, a.storecode, a.description, a.latitude, a.longitude, a.orgcode, a.sitecode
                , coalesce(b.nilai_total,0) as nilai_total
                , coalesce(c.nilai_total,0) as rusak
                , coalesce(d.nilai_total,0) as kadaluarsa
            from tcg_store a
            left join (
                SELECT a.`storeid`, sum(a.price * a.currentstock) as nilai_total 
                FROM tcg_inventory a
                where a.is_deleted=0 and a.status='INSTOCK'
                group by a.storeid
            ) b on b.storeid=a.storeid
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN'
                group by b.storeid
            ) c on c.storeid=a.storeid
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED'
                group by b.storeid
            ) d on d.storeid=a.storeid
            where a.is_deleted=0";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.storeid, a.storecode, a.description
                , coalesce(b.nilai_total,0) as nilai_total
                , coalesce(c.nilai_total,0) as rusak
                , coalesce(d.nilai_total,0) as kadaluarsa
            from tcg_store a
            left join (
                SELECT a.`storeid`, sum(a.price * a.currentstock) as nilai_total 
                FROM tcg_inventory a
                where a.is_deleted=0 and a.status='INSTOCK' and (a.storeid=" .$storeidstr. ")
                group by a.storeid
            ) b on b.storeid=a.storeid
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN' and (b.storeid=" .$storeidstr. ")
                group by b.storeid
            ) c on c.storeid=a.storeid
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED' and (b.storeid=" .$storeidstr. ")
                group by b.storeid
            ) d on d.storeid=a.storeid
            where a.is_deleted=0 and a.storeid=" .$storeidstr. "";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function stokperkategori($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT b.categoryid, sum(a.price * a.currentstock) as nilai_total 
                FROM tcg_inventory a
                join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                where a.is_deleted=0 and a.status='INSTOCK'
                group by b.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1
            order by a.categoryid";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT b.categoryid, sum(a.price * a.currentstock) as nilai_total 
                FROM tcg_inventory a
                join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                where a.is_deleted=0 and a.status='INSTOCK' and (a.storeid=" .$storeidstr. ")
                group by b.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1
            order by a.categoryid
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function kadaluarsapergudang($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.storeid, a.storecode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_store a
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED'
                group by b.storeid
            ) b on b.storeid=a.storeid
            where a.is_deleted=0";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.storeid, a.storecode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_store a
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED' and (b.storeid=" .$storeidstr. ")
                group by b.storeid
            ) b on b.storeid=a.storeid
            where a.is_deleted=0 and a.storeid=" .$storeidstr. "";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }
    
    public function kadaluarsaperkategori($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED'
                group by c.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='EXPIRED' and (b.storeid=" .$storeidstr. ")
                group by c.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function rusakpergudang($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.storeid, a.storecode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_store a
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN'
                group by b.storeid
            ) b on b.storeid=a.storeid
            where a.is_deleted=0";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.storeid, a.storecode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_store a
            left join (
                SELECT b.storeid, sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN' and (b.storeid=" .$storeidstr. ")
                group by b.storeid
            ) b on b.storeid=a.storeid
            where a.is_deleted=0 and a.storeid=" .$storeidstr. "";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }
    
    public function rusakperkategori($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT c.categoryid, sum(b.price * a.count) as nilai_total 
                FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN'
                group by c.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.categoryid, a.categorycode, a.description, coalesce(b.nilai_total,0) as nilai_total
            from tcg_itemcategory a
            left join (
                SELECT c.categoryid, sum(b.price * a.count) as nilai_total 
                FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                where a.is_deleted=0 and a.writeoff=0 and a.status='BROKEN' and (b.storeid=" .$storeidstr. ")
                group by c.categoryid
            ) b on b.categoryid=a.categoryid
            where a.is_deleted=0 and a.level=1";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function perkiraankadaluarsa($storeid = null)
    {
        if (empty($storeid)) {
            $sql = "SELECT a.value, a.label, sum(coalesce(b.count,0) * coalesce(b.price,0)) as nilai_total
            from lk_status a
            left join
            (
                SELECT a.inventoryid, a.availableamount as count, a.price
                    , case when a.shelflifeuntil is null or cast(a.shelflifeuntil as date) = '0000-00-00' then '10YEARS'
                           when datediff(now(), a.shelflifeuntil) > (365*5) then '5YEARS'
                           when datediff(now(), a.shelflifeuntil) > (365) then '1YEAR'
                           when datediff(now(), a.shelflifeuntil) > (180) then '6MONTHS'
                           when datediff(now(), a.shelflifeuntil) > 0 then 'EXPIRING'
                           else 'EXPIRED'
                      end estkadaluarsa
                FROM tcg_inventory a
                where a.is_deleted=0 and a.status='INSTOCK'
            ) b on b.estkadaluarsa=a.value
            where a.domain='est_kadaluarsa' and a.is_deleted=0
            group by a.value, a.label order by a.statusid";
        }
        else {
            $storeidstr = $this->db->escape($storeid);
            $sql = "SELECT a.value, a.label, sum(coalesce(b.count,0) * coalesce(b.price,0)) as nilai_total
            from lk_status a
            left join
            (
                SELECT a.inventoryid, a.availableamount as count, a.price
                    , case when a.shelflifeuntil is null or cast(a.shelflifeuntil as date) = '0000-00-00' then '10YEARS'
                           when datediff(now(), a.shelflifeuntil) > (365*5) then '5YEARS'
                           when datediff(now(), a.shelflifeuntil) > (365) then '1YEAR'
                           when datediff(now(), a.shelflifeuntil) > (180) then '6MONTHS'
                           when datediff(now(), a.shelflifeuntil) > 0 then 'EXPIRING'
                           else 'EXPIRED'
                      end estkadaluarsa
                FROM tcg_inventory a
                where a.is_deleted=0 and a.status='INSTOCK' and (a.storeid=" .$storeidstr. ")
            ) b on b.estkadaluarsa=a.value
            where a.domain='est_kadaluarsa' and a.is_deleted=0
            group by a.value, a.label order by a.statusid";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function pergerakanbarang($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_pergerakanbarang_all($periode, $offset);
        } else {
            return $this->_pergerakanbarang_storeid($storeid, $periode, $offset);
        }
    }

    public function _pergerakanbarang_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $label = $periode;
            if (!empty($offset)) $label .= '-' .$offset;

            $label = $this->db->escape($label);

            $sql = "SELECT 
                ".$label." as label
                , YEAR(".$datestr.") as tahun
                , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                    coalesce(d.nilai_total,0) as nilai_total
                , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
            from (
                SELECT sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and YEAR(a.receiveddate)=YEAR(".$datestr.")
            ) a
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and YEAR(a.checkoutdate)=YEAR(".$datestr.")
            ) b on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                    and YEAR(a.writeoffdate)=YEAR(".$datestr.")
            ) c on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                    and YEAR(a.writeoffdate)=YEAR(".$datestr.")
            ) d on 1=1
            ";
        }
        else if ($periode == 'MTD') {
            $label = $periode;
            if (!empty($offset)) $label .= '-' .$offset;

            $label = $this->db->escape($label);

            $sql = "SELECT 
                " .$label. " as label
                , YEAR(".$datestr.") as tahun
                , MONTH(".$datestr.") as bulan
                , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                    coalesce(d.nilai_total,0) as nilai_total
                , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
            from (
                SELECT sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
            ) a
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
            ) b on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                    and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
            ) c on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                    and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
            ) d on 1=1
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $label = $periode .' DAY(S)';
            $periodestr = $this->db->escape('-' .$periode);

            $label = $this->db->escape($label);

            $sql = "SELECT 
                " .$datestr. " as tanggal
                ," .$label. " as periode
                , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                    coalesce(d.nilai_total,0) as nilai_total
                , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
            from (
                SELECT sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                    and datediff(a.receiveddate,".$datestr.") < 0
            ) a
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.status not in ('DRAFT')
                    and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                    and datediff(a.checkoutdate,".$datestr.") < 0
            ) b on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                    and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                    and datediff(a.writeoffdate,".$datestr.") < 0
            ) c on 1=1
            left join (
                SELECT -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                    and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                    and datediff(a.writeoffdate,".$datestr.") < 0
            ) d on 1=1
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->row_array();
    }

    public function _pergerakanbarang_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $label = $periode;
            if (!empty($offset)) $label .= '-' .$offset;

            $label = $this->db->escape($label);

            $sql = "SELECT 
                " .$label. " as label
                , YEAR(".$datestr.") as tahun
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.penggunaan) as penggunaan
                , sum(a.hapusbuku_rusak) as hapusbuku_rusak
                , sum(a.hapusbuku_kadaluarsa) as hapusbuku_kadaluarsa
                , sum(a.transfer_masuk) as transfer_masuk
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, x.storecode, x.description
                    , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                        coalesce(d.nilai_total,0) + coalesce(e.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
                    , coalesce(e.nilai_total,0) as transfer_masuk, coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.")
                    group by b.storeid 
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                left join (
                    SELECT a.fromstoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            ";
        }
        else if ($periode == 'MTD') {
            $label = $periode;
            if (!empty($offset)) $label .= '-' .$offset;

            $label = $this->db->escape($label);

            $sql = "SELECT 
                ".$label." as label
                , YEAR(".$datestr.") as tahun
                , MONTH(".$datestr.") as bulan
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.penggunaan) as penggunaan
                , sum(a.hapusbuku_rusak) as hapusbuku_rusak
                , sum(a.hapusbuku_kadaluarsa) as hapusbuku_kadaluarsa
                , sum(a.transfer_masuk) as transfer_masuk
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, x.storecode, x.description
                    , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                        coalesce(d.nilai_total,0) + coalesce(e.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
                    , coalesce(e.nilai_total,0) as transfer_masuk, coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by b.storeid 
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                left join (
                    SELECT a.fromstoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ) a
            where a.storeid=".$storeidstr."
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $label = $periode .' DAY(S)';
            $periodestr = $this->db->escape('-' .$periode);

            $label = $this->db->escape($label);

            $sql = "SELECT 
                " .$datestr. " as tanggal
                ," .$label. " as periode
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.penggunaan) as penggunaan
                , sum(a.hapusbuku_rusak) as hapusbuku_rusak
                , sum(a.hapusbuku_kadaluarsa) as hapusbuku_kadaluarsa
                , sum(a.transfer_masuk) as transfer_masuk
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, x.storecode, x.description
                    , coalesce(a.nilai_total,0) + coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + 
                        coalesce(d.nilai_total,0) + coalesce(e.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan, coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as hapusbuku_rusak, coalesce(d.nilai_total,0) as hapusbuku_kadaluarsa
                    , coalesce(e.nilai_total,0) as transfer_masuk, coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid 
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                left join (
                    SELECT a.fromstoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ) a
            where a.storeid=".$storeidstr."
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->row_array();
    }

    public function barangmasukperwaktu($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_barangmasukperwaktu_all($periode, $offset);
        } else {
            return $this->_barangmasukperwaktu_storeid($storeid, $periode, $offset);
        }
    }

    public function _barangmasukperwaktu_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT YEAR(".$datestr.") as tahun, y.id as bulan, y.shortname as nama_pendek, y.longname as nama_panjang
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM lk_month y 
                left join (
                    SELECT month(a.receiveddate) as bulan, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by month(a.receiveddate)
                ) as a on a.bulan=y.id
                where (YEAR(curdate())=YEAR(".$datestr.") and y.id<=MONTH(".$datestr.")) or (YEAR(curdate())!=YEAR(".$datestr."))
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT y.caldate as tanggal
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM lk_calendar y
                left join (
                    SELECT a.receiveddate, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.receiveddate
                ) as a on a.receiveddate=y.caldate
                where YEAR(y.caldate)=YEAR(".$datestr.") and MONTH(y.caldate)=MONTH(".$datestr.") and y.caldate<=curdate()
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT y.caldate as tanggal
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM lk_calendar y 
                left join (
                    SELECT a.receiveddate, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.receiveddate
                ) as a on a.receiveddate=y.caldate
                where datediff(y.caldate,".$datestr.") >= " .$periodestr. " and datediff(y.caldate,".$datestr.") < 0
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangmasukperwaktu_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                YEAR(".$datestr.") as tahun, a.id as bulan, a.shortname as nama_pendek, a.longname as nama_panjang
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.id, y.shortname, y.longname
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join lk_month y on (YEAR(curdate())=YEAR(".$datestr.") and y.id<=MONTH(".$datestr.")) or (YEAR(curdate())!=YEAR(".$datestr."))
                left join (
                    SELECT a.storeid, month(a.receiveddate) as bulan, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by a.storeid, month(a.receiveddate)
                ) as a on a.storeid=x.storeid and a.bulan=y.id
                left join (
                    SELECT a.tostoreid, month(a.transferdate) as bulan, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.tostoreid, month(a.transferdate)
                ) e on e.tostoreid=x.storeid and e.bulan=y.id
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.id, a.shortname, a.longname
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                a.caldate as tanggal
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.caldate
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join lk_calendar y on YEAR(y.caldate)=YEAR(".$datestr.") and MONTH(y.caldate)=MONTH(".$datestr.") and y.caldate<=curdate()
                left join (
                    SELECT a.storeid, month(a.receiveddate) as bulan, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.storeid, month(a.receiveddate)
                ) as a on a.storeid=x.storeid and a.bulan=y.id
                left join (
                    SELECT a.tostoreid, month(a.transferdate) as bulan, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.tostoreid, month(a.transferdate)
                ) e on e.tostoreid=x.storeid and e.bulan=y.id
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.caldate
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT
                a.caldate as tanggal
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.caldate
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join lk_calendar y on datediff(y.caldate,".$datestr.") >= " .$periodestr. " and datediff(y.caldate,".$datestr.") < 0
                left join (
                    SELECT a.storeid, month(a.receiveddate) as bulan, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.storeid, month(a.receiveddate)
                ) as a on a.storeid=x.storeid and a.bulan=y.id
                left join (
                    SELECT a.tostoreid, month(a.transferdate) as bulan, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.tostoreid, month(a.transferdate)
                ) e on e.tostoreid=x.storeid and e.bulan=y.id
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.caldate
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function barangmasukpergudang($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_barangmasukpergudang_all($periode, $offset);
        } else {
            return $this->_barangmasukpergudang_storeid($storeid, $periode, $offset);
        }
    }

    public function _barangmasukpergudang_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    x.storeid, x.storecode, x.description, YEAR(".$datestr.") as tahun
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                    x.storeid, x.storecode, x.description, YEAR(".$datestr.") as tahun, MONTH(".$datestr.") as bulan
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                    x.storeid, x.storecode, x.description, ".$datestr." as tanggal, " .$periodestr. " as periode
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangmasukpergudang_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    YEAR(".$datestr.") as tahun, x.storeid
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0 and x.storeid=" .$storeidstr. "
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                    YEAR(".$datestr.") as tahun, MONTH(".$datestr.") as bulan, x.storeid
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0 and a.storeid=" .$storeidstr. "
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                    ".$datestr." as tanggal, " .$periodestr. " as periode, x.storeid
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                left join (
                    SELECT a.storeid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.storeid
                ) as a on a.storeid=x.storeid
                left join (
                    SELECT a.tostoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.tostoreid
                ) e on e.tostoreid=x.storeid
                where x.is_deleted=0 and a.storeid=" .$storeidstr. "
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function barangmasukperkategori($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_barangmasukperkategori_all($periode, $offset);
        } else {
            return $this->_barangmasukperkategori_storeid($storeid, $periode, $offset);
        }
    }

    public function _barangmasukperkategori_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM tcg_itemcategory y
                left join (
                    SELECT b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by b.categoryid
                ) as a on a.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT
                    y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM tcg_itemcategory y
                left join (
                    SELECT b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by b.categoryid
                ) as a on a.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT
                    y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , 0 as transfer_masuk
                FROM tcg_itemcategory y
                left join (
                    SELECT b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by b.categoryid
                ) as a on a.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }
   
        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangmasukperkategori_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join tcg_itemcategory y on 1=1
                left join (
                    SELECT a.storeid, b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.")
                    group by a.storeid, b.categoryid
                ) as a on a.storeid=x.storeid and a.categoryid=y.categoryid
                left join (
                    SELECT a.tostoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.tostoreid, c.categoryid
                ) e on e.tostoreid=x.storeid and e.categoryid=y.categoryid
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join tcg_itemcategory y on 1=1
                left join (
                    SELECT a.storeid, b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.receiveddate)=YEAR(".$datestr.") and MONTH(a.receiveddate)=MONTH(".$datestr.")
                    group by a.storeid, b.categoryid
                ) as a on a.storeid=x.storeid and a.categoryid=y.categoryid
                left join (
                    SELECT a.tostoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.tostoreid, c.categoryid
                ) e on e.tostoreid=x.storeid and e.categoryid=y.categoryid
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penerimaan) as penerimaan
                , sum(a.transfer_masuk) as transfer_masuk
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(a.nilai_total,0) + coalesce(e.nilai_total,0) as nilai_total
                    , coalesce(a.nilai_total,0) as penerimaan
                    , coalesce(e.nilai_total,0) as transfer_masuk
                FROM tcg_store x
                join tcg_itemcategory y on 1=1
                left join (
                    SELECT a.storeid, b.categoryid, sum(a.price * a.receivedamount) as nilai_total from tcg_inventory a
                    join tcg_item b on b.itemid=a.itemid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.receiveddate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.receiveddate,".$datestr.") < 0
                    group by a.storeid, b.categoryid
                ) as a on a.storeid=x.storeid and a.categoryid=y.categoryid
                left join (
                    SELECT a.tostoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT') 
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.tostoreid, c.categoryid
                ) e on e.tostoreid=x.storeid and e.categoryid=y.categoryid
                where x.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function barangkeluarperwaktu($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_barangkeluarperwaktu_all($periode, $offset);
        } else {
            return $this->_barangkeluarperwaktu_storeid($storeid, $periode, $offset);
        }

    }

    public function _barangkeluarperwaktu_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT YEAR(".$datestr.") as tahun, y.id as bulan, y.shortname as nama_pendek, y.longname as nama_panjang
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM lk_month y 
                left join (
                    SELECT month(a.checkoutdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.")
                    group by month(a.checkoutdate) 
                ) b on b.bulan=y.id
                left join (
                    SELECT month(a.writeoffdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by month(a.writeoffdate)
                ) c on c.bulan=y.id
                left join (
                    SELECT month(a.writeoffdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by month(a.writeoffdate)
                ) d on d.bulan=y.id
                where (YEAR(curdate())=YEAR(".$datestr.") and y.id<=MONTH(".$datestr.")) or (YEAR(curdate())!=YEAR(".$datestr."))
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT y.caldate as tanggal
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM lk_calendar y
                left join (
                    SELECT a.checkoutdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by a.checkoutdate
                ) b on b.checkoutdate=y.caldate
                left join (
                    SELECT a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by a.writeoffdate
                ) c on c.writeoffdate=y.caldate
                left join (
                    SELECT a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by a.writeoffdate
                ) d on d.writeoffdate=y.caldate
                where YEAR(y.caldate)=YEAR(".$datestr.") and MONTH(y.caldate)=MONTH(".$datestr.") and y.caldate<=curdate()
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT y.caldate as tanggal
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM lk_calendar y 
                left join (
                    SELECT a.checkoutdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by a.checkoutdate
                ) b on b.checkoutdate=y.caldate
                left join (
                    SELECT a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by a.writeoffdate
                ) c on c.writeoffdate=y.caldate
                left join (
                    SELECT a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by a.writeoffdate
                ) d on d.writeoffdate=y.caldate
                where datediff(y.caldate,".$datestr.") >= " .$periodestr. " and datediff(y.caldate,".$datestr.") < 0
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangkeluarperwaktu_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                YEAR(".$datestr.") as tahun, a.id as bulan, a.shortname as nama_pendek, a.longname as nama_panjang
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.id, y.shortname, y.longname
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM lk_month y 
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, month(a.checkoutdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.")
                    group by b.storeid, month(a.checkoutdate) 
                ) b on b.storeid=x.storeid and b.bulan=y.id
                left join (
                    SELECT b.storeid, month(a.writeoffdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid, month(a.writeoffdate)
                ) c on c.storeid=x.storeid and c.bulan=y.id
                left join (
                    SELECT b.storeid, month(a.writeoffdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid, month(a.writeoffdate)
                ) d on d.storeid=x.storeid and d.bulan=y.id
                left join (
                    SELECT a.fromstoreid, month(a.transferdate) as bulan, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.fromstoreid, month(a.transferdate)
                ) f on f.fromstoreid=x.storeid and f.bulan=y.id
                where (YEAR(curdate())=YEAR(".$datestr.") and y.id<=MONTH(".$datestr.")) or (YEAR(curdate())!=YEAR(".$datestr."))
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.id, a.shortname, a.longname
                ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                a.caldate as tanggal
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.caldate
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM lk_calendar y
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, a.checkoutdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by b.storeid, a.checkoutdate
                ) b on b.storeid=x.storeid and b.checkoutdate=y.caldate
                left join (
                    SELECT b.storeid, a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid, a.writeoffdate
                ) c on c.storeid=x.storeid and c.writeoffdate=y.caldate
                left join (
                    SELECT b.storeid, a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid, a.writeoffdate
                ) d on d.storeid=x.storeid and d.writeoffdate=y.caldate
                left join (
                    SELECT a.fromstoreid, a.transferdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.fromstoreid, a.transferdate
                ) f on f.fromstoreid=x.storeid and f.transferdate=y.caldate
                where YEAR(y.caldate)=YEAR(".$datestr.") and MONTH(y.caldate)=MONTH(".$datestr.") and y.caldate<=curdate()
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.caldate
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                a.caldate as tanggal
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.caldate
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM lk_calendar y 
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, a.checkoutdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid, a.checkoutdate
                ) b on b.storeid=x.storeid and b.checkoutdate=y.caldate
                left join (
                    SELECT b.storeid, a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid, a.writeoffdate
                ) c on c.storeid=x.storeid and c.writeoffdate=y.caldate
                left join (
                    SELECT b.storeid, a.writeoffdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid, a.writeoffdate
                ) d on d.storeid=x.storeid and d.writeoffdate=y.caldate
                left join (
                    SELECT a.fromstoreid, a.transferdate, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.fromstoreid, a.transferdate
                ) f on f.fromstoreid=x.storeid and f.transferdate=y.caldate
                where datediff(y.caldate,".$datestr.") >= " .$periodestr. " and datediff(y.caldate,".$datestr.") < 0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.caldate
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function barangkeluarpergudang($storeid = null, $periode = null, $offset)
    {
        if (empty($storeid)) {
            return $this->_barangkeluarpergudang_all($periode, $offset);
        } else {
            return $this->_barangkeluarpergudang_storeid($storeid, $periode, $offset);
        }
    }

    public function _barangkeluarpergudang_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    YEAR(".$datestr.") as tahun, x.storeid, x.storecode, x.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT
                    YEAR(".$datestr.") as tahun, MONTH(".$datestr.") as bulan
                    , x.storeid, x.storecode, x.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT
                    ".$datestr." as tanggal, " .$periodestr. " as periode
                    , x.storeid, x.storecode, x.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangkeluarpergudang_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    x.storeid, x.storecode, x.description, YEAR(".$datestr.") as tahun
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.")
                    group by b.storeid 
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0 and x.storeid=" .$storeidstr. "
                ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                    x.storeid, x.storecode, x.description, YEAR(".$datestr.") as tahun, MONTH(".$datestr.") as bulan
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as pengunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x 
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0 and x.storeid=" .$storeidstr. "
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT
                    x.storeid, x.storecode, x.description, ".$datestr." as tanggal, " .$periodestr. " as periode
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as pengunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_store x
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid
                ) b on b.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid
                ) c on c.storeid=x.storeid
                left join (
                    SELECT b.storeid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid
                ) d on d.storeid=x.storeid
                left join (
                    SELECT a.fromstoreid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.fromstoreid
                ) f on f.fromstoreid=x.storeid
                where x.is_deleted=0 and x.storeid=" .$storeidstr. "
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function barangkeluarperkategori($storeid = null, $periode = null, $offset = null)
    {
        if (empty($storeid)) {
            return $this->_barangkeluarperkategori_all($periode, $offset);
        } else {
            return $this->_barangkeluarperkategori_storeid($storeid, $periode, $offset);
        }
    }

    public function _barangkeluarperkategori_all($periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                    y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as pengunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM tcg_itemcategory y
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(now())-1
                    group by c.categoryid 
                ) b on b.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(now())-1
                    group by c.categoryid
                ) c on c.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(now())
                    group by c.categoryid
                ) d on d.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT
                    y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as pengunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM tcg_itemcategory y
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by c.categoryid
                ) b on b.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by c.categoryid
                ) c on c.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by c.categoryid
                ) d on d.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                    y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                FROM tcg_itemcategory y
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by c.categoryid
                ) b on b.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by c.categoryid
                ) c on c.categoryid=y.categoryid
                left join (
                    SELECT c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by c.categoryid
                ) d on d.categoryid=y.categoryid
                where y.is_deleted=0 and y.level=1
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

    public function _barangkeluarperkategori_storeid($storeid, $periode = null, $offset = null) {
        if (empty($periode))    $periode='30';
        if (empty($offset))     $offset=0;

        $periode = strtoupper($periode);

        $datestr = null;
        if ($periode == 'YTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' year'));
        } else if ($periode == 'MTD') {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' month'));
        } else {
            $datestr=date('Y-m-d', strtotime('-' .$offset. ' day'));
        }

        $datestr = $this->db->escape($datestr);
        $storeidstr = $this->db->escape($storeid);

        $sql = "";
        if ($periode == 'YTD') {
            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_itemcategory y 
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(now())-1
                    group by b.storeid, c.categoryid 
                ) b on b.storeid=x.storeid and b.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(now())-1
                    group by b.storeid, c.categoryid
                ) c on c.storeid=x.storeid and c.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(now())
                    group by b.storeid, c.categoryid
                ) d on d.storeid=x.storeid and d.categoryid=y.categoryid
                left join (
                    SELECT a.fromstoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(now())
                    group by a.fromstoreid, c.categoryid
                ) f on f.fromstoreid=x.storeid and f.categoryid=y.categoryid
                where y.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
                ";
        }
        else if ($periode == 'MTD') {
            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_itemcategory y
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.checkoutdate)=YEAR(".$datestr.") and MONTH(a.checkoutdate)=MONTH(".$datestr.")
                    group by b.storeid, c.categoryid
                ) b on b.storeid=x.storeid and b.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid, c.categoryid
                ) c on c.storeid=x.storeid and c.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and YEAR(a.writeoffdate)=YEAR(".$datestr.") and MONTH(a.writeoffdate)=MONTH(".$datestr.")
                    group by b.storeid, c.categoryid
                ) d on d.storeid=x.storeid and d.categoryid=y.categoryid
                left join (
                    SELECT a.fromstoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and YEAR(a.transferdate)=YEAR(".$datestr.") and MONTH(a.transferdate)=MONTH(".$datestr.")
                    group by a.fromstoreid, c.categoryid
                ) f on f.fromstoreid=x.storeid and f.categoryid=y.categoryid
                where y.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
            ";
        }
        else {
            if (!is_numeric($periode))   $periode='30';
            $periodestr = $this->db->escape('-' .$periode);

            $sql = "SELECT 
                a.categoryid, a.categorycode, a.description
                , sum(a.nilai_total) as nilai_total
                , sum(a.penggunaan) as penggunaan
                , sum(a.rusak) as rusak
                , sum(a.kadaluarsa) as kadaluarsa
                , sum(a.transfer_keluar) as transfer_keluar
            from
            (
                SELECT x.storeid, y.categoryid, y.categorycode, y.description
                    , coalesce(b.nilai_total,0) + coalesce(c.nilai_total,0) + coalesce(d.nilai_total,0) + coalesce(f.nilai_total,0) as nilai_total
                    , coalesce(b.nilai_total,0) as penggunaan
                    , coalesce(c.nilai_total,0) as rusak
                    , coalesce(d.nilai_total,0) as kadaluarsa
                    , coalesce(f.nilai_total,0) as transfer_keluar
                FROM tcg_itemcategory y 
                join tcg_store x on 1=1
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invusage a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.checkoutdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.checkoutdate,".$datestr.") < 0
                    group by b.storeid, c.categoryid
                ) b on b.storeid=x.storeid and b.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='BROKEN'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid, c.categoryid
                ) c on c.storeid=x.storeid and c.categoryid=y.categoryid
                left join (
                    SELECT b.storeid, c.categoryid, -1 * sum(b.price * a.count) as nilai_total FROM tcg_invstatus a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.writeoff=1 and a.status='EXPIRED'
                        and datediff(a.writeoffdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.writeoffdate,".$datestr.") < 0
                    group by b.storeid, c.categoryid
                ) d on d.storeid=x.storeid and d.categoryid=y.categoryid
                left join (
                    SELECT a.fromstoreid, c.categoryid, sum(b.price * a.count) as nilai_total FROM tcg_invtransfer a
                    join tcg_inventory b on b.inventoryid=a.inventoryid and b.is_deleted=0
                    join tcg_item c on c.itemid=b.itemid and c.is_deleted=0
                    where a.is_deleted=0 and a.status not in ('DRAFT')
                        and datediff(a.transferdate,".$datestr.") >= " .$periodestr. "
                        and datediff(a.transferdate,".$datestr.") < 0
                    group by a.fromstoreid, c.categoryid
                ) f on f.fromstoreid=x.storeid and f.categoryid=y.categoryid
                where y.is_deleted=0
            ) a
            where a.storeid=" .$storeidstr. "
            group by a.categoryid, a.categorycode, a.description
            ";
        }

        $query = $this->db->query($sql);
        if ($query == null) return null;

        return $query->result_array();
    }

}

  