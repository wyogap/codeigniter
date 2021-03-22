<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mwilayah extends CI_Model
{
    protected static $TABLE_NAME = "ref_mst_wilayah";
    protected $provinsi_aktif = array();

    public function __construct()
    {
        parent::__construct();

        $filter_value = $this->setting->get('app_kode_provinsi');
        if (!empty($filter_value)) {
            $this->provinsi_aktif = array_map('trim', explode(',', $filter_value));
        }
    }


    function cascade_down($target_level, $value=null) {
        $filter = array (
            // 'is_deleted'        => 0,
            'id_level_wilayah'  => $target_level
        );

        $filter_value = null;
        if (!empty($value)) {
            $arr = $this->detail($value);
            if ($arr == null)   return null;

            //var_dump($arr);

            $filter_value = $arr['kode_wilayah'];
        }

        if (!empty($filter_value)) {
            $filter['mst_kode_wilayah'] = $filter_value;
        }

        if (count($this->provinsi_aktif))
            $this->db->where_in('kode_wilayah_prov', $this->provinsi_aktif);

        $this->db->order_by('nama');
        return $this->db->get_where(static::$TABLE_NAME, $filter)->result_array();
    }

    function list($target_level, $value=null) {
        $filter = array (
            // 'is_deleted'        => 0,
            'id_level_wilayah'  => $target_level
        );

        $filter_value = null;
        if (!empty($value)) {
            $arr = $this->detail($value, $target_level);
            if ($arr == null)   return null;

            //var_dump($arr);

            $filter_value = $arr['mst_kode_wilayah'];
        }

        if (!empty($filter_value)) {
            $filter['mst_kode_wilayah'] = $filter_value;
        }

        if (count($this->provinsi_aktif))
            $this->db->where_in('kode_wilayah_prov', $this->provinsi_aktif);
            
        $this->db->order_by('nama');
        return $this->db->get_where(static::$TABLE_NAME, $filter)->result_array();
    }

    function detail($value, $target_level = null) {
        $target_level = intval($target_level);

        $cur_level = 0;
        $cur_value = $value;
        $cur_detail = null;

        $filter = array (
            // 'is_deleted'    => 0,
        );

        do {
            $filter['kode_wilayah'] = $cur_value;

            $cur_detail = $this->db->get_where(static::$TABLE_NAME, $filter)->row_array();
            if ($cur_detail == null)            return null;

            //var_dump($cur_detail);

            //no target level specified. just get the data
            if ($target_level == 0)             return $cur_detail;

            //see if we need to cascade up to target level
            $cur_level = intval($cur_detail['id_level_wilayah']);

            //only cascade up
            if ($cur_level < $target_level)     return null;

            //found the target level
            if ($cur_level == $target_level)    return $cur_detail;

            //cascade up
            $cur_value = $cur_detail['mst_kode_wilayah'];
            if (empty($cur_value))              return null;

        }
        while ($cur_level >= $target_level && !empty($cur_value));

        return null;
    }

}

  