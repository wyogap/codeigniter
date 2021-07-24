<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud_tablemeta.php');

class Mkendaraanverifikasi extends Mcrud_tablemeta
{
    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        if (!empty($valuepair['status_verifikasi'])) {
            if ($valuepair['status_verifikasi'] == 'valid') {
                $detail = $this->detail($id);
                if ($detail['status_verifikasi'] != 'valid') {
                    $valuepair['tanggal_verifikasi'] = date('Y/m/d H:i:s');
                }
            }
            else {
                $valuepair['tanggal_verifikasi'] = null;
            }
        }

        if (empty($valuepair['tanggal_verifikasi']) || '0000-00-00 00:00:00' == $valuepair['tanggal_verifikasi']) {
            $valuepair['tanggal_verifikasi'] = null;
        }

        return parent::update($id, $valuepair, $filter);
    }
}

  