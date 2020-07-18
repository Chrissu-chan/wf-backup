<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konversi_satuan_m extends BaseModel {

    protected $table = 'konversi_satuan';
    protected $primary_key = 'id';
    protected $fillable = array('id_satuan_asal','id_satuan_tujuan','konversi');

    public function view_konversi_satuan() {
        $this->db->select('konversi_satuan.*, satuan_asal.satuan as satuan_asal, satuan_tujuan.satuan as satuan_tujuan')
            ->join('satuan satuan_asal', 'satuan_asal.id = konversi_satuan.id_satuan_asal')
            ->join('satuan satuan_tujuan', 'satuan_tujuan.id = konversi_satuan.id_satuan_tujuan');
    }

    public function convert($dari, $ke, $value) {
        $result = $this->where('id_satuan_asal', $dari)
            ->where('id_satuan_tujuan', $ke)
            ->first();
        if ($result) {
            return $value * $result->konversi;
        } else {
            return 1;
        }
    }
}