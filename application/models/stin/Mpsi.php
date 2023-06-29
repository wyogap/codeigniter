<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpsi extends CI_Model
{

    function list_psi() {
        $sql = "
        SELECT 
            a.kode_validasi
            , a.kode_tes as kode_peserta
            , a.no_psi as nomor_psi
            , a.kode_wilayah as wilayah
            , case when a.jenis_kelamin='L' then 1 else 2 end as jenis_kelamin
        FROM stin_psi_mitra a
        ";

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null) return null;

        return $arr;
    }

    function list_lulus() {
        $sql = "
        SELECT 
            a.kode_validasi
            , a.kode_tes as kode_peserta
        FROM stin_psi_lulus a
        ";

        $arr = $this->db->query($sql)->result_array();
        if ($arr == null) return null;

        return $arr;
    }

    function simpan_token($token) {
        //filter
        $filter = array();
        $filter['group'] = 'stin';
        $filter['name'] = 'psi_token';

        //value
        $valuepair = array();
        $valuepair['value'] = $token;

        //inject updated 
        $valuepair['updated_on'] = date('Y/m/d H:i:s');
        $valuepair['updated_by'] = $this->session->userdata('user_id');

        //var_dump($valuepair); exit;

        $this->db->update('dbo_settings', $valuepair, $filter);

        // $affected = $this->db->affected_rows();
        // echo $affected;
    }

    function get_token() {
        $sql = "
        SELECT 
            a.value
        FROM dbo_settings a where a.group='stin' and a.name='psi_token'
        ";
        
        $arr = $this->db->query($sql)->row_array();
        if ($arr == null) return '';

        return $arr['value'];
    }

    function simpan_hasil($data) {
        //delete all data first
        $sql = 'truncate table stin_psi_hasil';
        $this->db->query($sql);

        $cnt = 0;
        foreach($data as $row) {
            $valuepair = array();
            $valuepair['no_urut'] = $row->no;
            $valuepair['nomer_psi'] = $row->nomor_psi;
            $valuepair['kode_peserta'] = $row->kode_peserta;
            $valuepair['kode_validasi'] = $row->kode_validasi;
            $valuepair['iq'] = $row->iq;
            $valuepair['nilai_psikologi'] = $row->nilai_psikologi;
            $valuepair['kategori'] = $row->kategori;
            $valuepair['wilayah'] = $row->wilayah;

            $query = $this->db->insert('stin_psi_hasil', $valuepair);
            if ($query) {
                $cnt++;
            }
        }

        return $cnt;
    }

    function get_jumlah_disarankan() {
        $kategori = 'Disarankan';

        $sql = "
        SELECT 
            count(*) as value
        FROM stin_psi_hasil a where a.kategori=?
        ";
        
        $arr = $this->db->query($sql, $kategori)->row_array();
        if ($arr == null) return '';

        return $arr['value'];
    }

    function get_jumlah_dipertimbangkan() {
        $kategori = 'Dipertimbangkan';

        $sql = "
        SELECT 
            count(*) as value
        FROM stin_psi_hasil a where a.kategori=?
        ";
        
        $arr = $this->db->query($sql, $kategori)->row_array();
        if ($arr == null) return '';

        return $arr['value'];
    }

    function get_jumlah_tidak_disarankan() {
        $kategori = 'Tidak Disarankan';

        $sql = "
        SELECT 
            count(*) as value
        FROM stin_psi_hasil a where a.kategori=?
        ";
        
        $arr = $this->db->query($sql, $kategori)->row_array();
        if ($arr == null) return '';

        return $arr['value'];
    }
}