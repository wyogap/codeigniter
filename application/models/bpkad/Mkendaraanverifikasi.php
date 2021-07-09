<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/Mcrud.php');

class Mkendaraanverifikasi extends Mcrud
{
    protected static $TABLE_NAME = "tcg_kendaraan_dinas";
    protected static $PRIMARY_KEY = "no_aset";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();

    protected static $COL_LABEL = 'no_polisi';
    protected static $COL_VALUE = 'no_aset';

    protected static $VIEW_TABLE_NAME = 'v_tcg_kendaraan_dinas_perlu_verifikasi_v3';
    protected static $SOFT_DELETE = true;

    function update($id, $valuepair, $filter = null) {
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

  