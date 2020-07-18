<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_aktif_kasir_m extends BaseModel {

    protected $table = 'shift_aktif_kasir';
    protected $fillable = array('id_shift_aktif', 'id_kas_bank', 'id_user', 'uang_awal', 'uang_akhir');

    public function set_uang_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_uang_akhir($value) {
        return $this->localization->number_value($value);
    }
}