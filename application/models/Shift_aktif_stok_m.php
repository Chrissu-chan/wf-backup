<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_aktif_stok_m extends BaseModel {

    protected $table = 'shift_aktif_stok';
    protected $fillable = array('id_shift_aktif', 'id_gudang', 'id_barang', 'stok_awal', 'stok_akhir');

    public function set_stok_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_stok_akhir($value) {
        return $this->localization->number_value($value);
    }
}