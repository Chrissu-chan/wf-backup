<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perpanjangan_masa_aktif_member_m extends BaseModel {

    protected $table = 'perpanjangan_masa_aktif_member';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'id_member', 'id_jenis_member', 'biaya', 'diskon', 'diskon_persen', 'ppn', 'ppn_persen', 'total', 'metode_pembayaran', 'id_kas_bank', 'no_ref_pembayaran', 'bayar', 'kembali');

    protected $timestamps = true;
    protected $authors = true;

    public function set_biaya($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }

    public function set_bayar($value) {
        return $this->localization->number_value($value);
    }

    public function set_kembali($value) {
        return $this->localization->number_value($value);
    }

}