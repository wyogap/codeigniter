<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdashboard extends CI_Model
{
    function kendaraan_total() {
        $sql = "
            select
                count(*) as total,
                sum(case when a.status_verifikasi = 'valid' then 1 else 0 end) as terverifikasi, 
                sum(case when a.status_verifikasi = 'valid' then 0 else 1 end) as perlu_verifikasi
            from tcg_kendaraan_dinas a
            where
                a.is_deleted=0 and (a.status_kepemilikan='aktif' or a.status_kepemilikan is null or a.status_kepemilikan='')
        ";

        return $this->db->query($sql)->row_array();
    }

    function kendaraan_total_opd($opd) {
        $sql = "
            select
                count(*) as total,
                sum(case when a.status_verifikasi = 'valid' then 1 else 0 end) as terverifikasi, 
                sum(case when a.status_verifikasi = 'valid' then 0 else 1 end) as perlu_verifikasi
            from tcg_kendaraan_dinas a
            where
                a.is_deleted=0 and (a.status_kepemilikan='aktif' or a.status_kepemilikan is null or a.status_kepemilikan='')
                and a.opd=?
        ";

        // $sql = "
        //     select
        //         a.total,
        //         (a.total - b.perlu_verifikasi) as terverifikasi, b.perlu_verifikasi
        //     from (
        //         select 
        //             count(*) as total
        //         from tcg_kendaraan_dinas a
        //         where a.is_deleted=0 and a.opd=?
        //     ) a
        //     join (
        //         select
        //             count(*) as perlu_verifikasi
        //         from v_tcg_kendaraan_dinas_perlu_verifikasi a
        //         where a.is_deleted=0 and a.opd=?
        //     ) b on 1=1
        // ";

        return $this->db->query($sql, array($opd))->row_array();
    }

    function kendaraan_per_jenis_kendaraan($opd=null) {
        $this->db->select("
            case when b.nama is null then 'lain-lain' else b.nama end as nama, 
            case when b.label is null then 'Tidak diketahui' else b.label end as label, 
            count(*) as jumlah
        ");
        $this->db->from('tcg_kendaraan_dinas a');
        $this->db->join('tcg_kode_barang b', 'b.nama=a.tipe and b.is_deleted=0', 'left outer');
        $this->db->where('a.is_deleted', 0);

        if ($opd != null) {
            $this->db->where('a.opd', $opd);
        }

        $this->db->group_by("b.nama");
        $this->db->group_by("b.label");

        return $this->db->get()->result_array();
    }

    function kendaraan_per_peruntukan($opd=null) {
        $this->db->select("
            case when b.nama is null then 'lain-lain' else b.nama end as nama, 
            case when b.label is null then 'Tidak diketahui' else b.label end as label, 
            count(*) as jumlah
        ");
        $this->db->from('tcg_kendaraan_dinas a');
        $this->db->join('tcg_peruntukan b', 'b.nama=a.peruntukan and b.is_deleted=0', 'left outer');
        $this->db->where('a.is_deleted', 0);

        if ($opd != null) {
            $this->db->where('a.opd', $opd);
        }

        $this->db->group_by("b.nama");
        $this->db->group_by("b.label");

        return $this->db->get()->result_array();
    }

    function kendaraan_per_opd() {
        $sql = "
            select
                case when (a.opd is null) then 'lain-lain' else a.opd end as opd,
                case when (c.label is null) then 'Tidak diketahui' else c.label end as label,
                a.total,
                a.jenis_roda_dua, a.jenis_roda_tiga, a.jenis_mobil, a.jenis_lainnya,
                a.peruntukan_perorangan, a.peruntukan_jabatan, a.peruntukan_operasional, a.peruntukan_khusus, a.peruntukan_pinjam_pakai, a.peruntukan_lainnya,
                a.terverifikasi, a.perlu_verifikasi
            from (
                select 
                    a.opd, 
                    count(*) as total,
                    sum(case when a.tipe='roda-dua' then 1 else 0 end) as jenis_roda_dua,
                    sum(case when a.tipe='roda-tiga' then 1 else 0 end) as jenis_roda_tiga,
                    sum(case when a.tipe='mobil' then 1 else 0 end) as jenis_mobil,
                    sum(case when a.tipe not in ('roda-dua', 'roda-tiga', 'mobil') then 1 else 0 end) as jenis_lainnya,
                    sum(case when a.peruntukan='perorangan' then 1 else 0 end) as peruntukan_perorangan,
                    sum(case when a.peruntukan='jabatan' then 1 else 0 end) as peruntukan_jabatan,
                    sum(case when a.peruntukan='operasional' then 1 else 0 end) as peruntukan_operasional,
                    sum(case when a.peruntukan='khusus' then 1 else 0 end) as peruntukan_khusus,
                    sum(case when a.peruntukan='pinjam pakai' then 1 else 0 end) as peruntukan_pinjam_pakai,
                    sum(case when a.peruntukan not in ('perorangan', 'jabatan', 'operasional', 'khusus', 'pinjam pakai') then 1 else 0 end) as peruntukan_lainnya,
                    sum(case when a.status_verifikasi = 'valid' then 1 else 0 end) as terverifikasi, 
                    sum(case when a.status_verifikasi = 'valid' then 0 else 1 end) as perlu_verifikasi    
                from tcg_kendaraan_dinas a
                where a.is_deleted=0
                group by a.opd
            ) a
            left join tcg_opd c on c.nama=a.opd and c.is_deleted=0        
        ";

        return $this->db->query($sql)->result_array();
    }
}

  