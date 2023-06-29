<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mcheckin extends CI_Model
{

    function detail($no_registrasi) {
        $sql = "
            select
                a.id, a.checkin, a.checkin_time, a.is_ktp_valid, a.is_hp_valid, a.nama_lengkap, a.no_ktp, a.no_hp
            from iwakk_pendaftar a
            where
                a.is_deleted=0 
                and a.no_registrasi=?
        ";
        $arr = $this->db->query($sql, array($no_registrasi))->row_array();

        $status = array();

        //no_registrasi invalid
        if ($arr == null) {
            $status['error'] = 1;
            $status['message'] = 'Nomer registrasi tidak ditemukan.';
            return $status;
        }

        $status['no_registrasi'] = $no_registrasi;
        $status['nama_lengkap'] = $arr['nama_lengkap'];
        $len = strlen($arr['no_ktp']);
        $status['no_ktp'] = substr($arr['no_ktp'], 0, 4) .str_pad('', $len-6, 'x'). substr($arr['no_ktp'], $len-2, 2);
        $len = strlen($arr['no_hp']);
        $status['no_hp'] = substr($arr['no_hp'], 0, 3) .str_pad('', $len-5, 'x'). substr($arr['no_hp'], $len-2, 2);

        $status["error"] = 0;
        return $status;    
    }

    function checkin($no_registrasi) {
        $sql = "
            select
                a.id, a.checkin, a.checkin_time, a.is_ktp_valid, a.is_hp_valid, a.nama_lengkap, a.no_ktp, a.no_hp
            from iwakk_pendaftar a
            where
                a.is_deleted=0 
                and a.no_registrasi=?
        ";
        $arr = $this->db->query($sql, array($no_registrasi))->row_array();

        $status = array();

        //no_registrasi invalid
        if ($arr == null) {
            $status['error'] = 1;
            $status['message'] = 'Nomer registrasi tidak ditemukan.';
            return $status;
        }

        $status['no_registrasi'] = $no_registrasi;
        $status['nama_lengkap'] = $arr['nama_lengkap'];
        $len = strlen($arr['no_ktp']);
        $status['no_ktp'] = substr($arr['no_ktp'], 0, 4) .str_pad('', $len-6, 'x'). substr($arr['no_ktp'], $len-2, 2);
        $len = strlen($arr['no_hp']);
        $status['no_hp'] = substr($arr['no_hp'], 0, 3) .str_pad('', $len-5, 'x'). substr($arr['no_hp'], $len-2, 2);

        //invalid data
        if ($arr['is_ktp_valid'] == '0' || $arr['is_hp_valid'] == '0') {
            $status['error'] = 2;
            $status['message'] = 'Perlu konfirmasi data.';
            $status['no_ktp'] = $arr['no_ktp'];
            $status['no_hp'] = $arr['no_hp'];
            $status['is_ktp_valid'] = $arr['is_ktp_valid'];
            $status['is_hp_valid'] = $arr['is_hp_valid'];
            $status['id'] = $arr['id'];
            return $status;
        }

        //sudah checkin (success)
        if ($arr['checkin'] == '1' && $arr['checkin_time'] != null) {
            $status['error'] = 0;
            $status['message'] = 'Sudah Check-in';
            return $status;
        }

        //do checkin
        $sql = "
            update iwakk_pendaftar
            set
                checkin=1
                , checkin_time=now()
            where
                no_registrasi=?
        ";
        $this->db->query($sql, array($no_registrasi));

        $status['error'] = 0;
        return $status; 
    }

    function update($id, $no_ktp, $no_hp) {
        $sql = "
            update iwakk_pendaftar
            set
                no_ktp=?, no_hp=?
                , is_ktp_valid=1, is_hp_valid=1
            where
                id=?
        ";
        $this->db->query($sql, array($no_ktp, $no_hp, $id));

        $sql = "
            select
                a.id, a.no_registrasi, a.checkin, a.checkin_time, a.is_ktp_valid, a.is_hp_valid, a.nama_lengkap, a.no_ktp, a.no_hp
            from iwakk_pendaftar a
            where
                a.is_deleted=0 
                and a.id=?
        ";
        $arr = $this->db->query($sql, array($id))->row_array();

        $status = array();

        //no_registrasi invalid
        if ($arr == null) {
            $status['error'] = 1;
            $status['message'] = 'Nomer registrasi tidak ditemukan.';
            return $status;
        }

        $status['no_registrasi'] = $arr['no_registrasi'];
        $status['nama_lengkap'] = $arr['nama_lengkap'];
        $len = strlen($arr['no_ktp']);
        $status['no_ktp'] = substr($arr['no_ktp'], 0, 4) .str_pad('', $len-6, 'x'). substr($arr['no_ktp'], $len-2, 2);
        $len = strlen($arr['no_hp']);
        $status['no_hp'] = substr($arr['no_hp'], 0, 3) .str_pad('', $len-5, 'x'). substr($arr['no_hp'], $len-2, 2);
        
        $status['error'] = 0;
        return $status; 
    }

    function is_open() {
        $sql = "
            select
                a.value
            from dbo_settings a
            where
                a.is_deleted=0 
                and a.group='silaturahmi2023' and a.name='allow_checkin'
        ";
        $arr = $this->db->query($sql)->row_array();
        if ($arr == null) return 0;

        return $arr['value'];
    }

    function settings() {
        $sql = "
            select
                a.name, a.value
            from dbo_settings a
            where
                a.is_deleted=0 
                and a.group='silaturahmi2023'
        ";
        $arr = $this->db->query($sql)->result_array();
        if ($arr == null) return 0;

        $settings = array();
        foreach($arr as $row) {
            $settings[$row['name']] = $row['value'];
        }

        return $settings;
    }

}