<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_member_pendaftaran_m extends BaseModel {

    protected $table = 'jenis_member_pendaftaran';
    protected $primary_key = 'id';
    protected $fillable = array('id_jenis_member', 'id_cabang', 'biaya', 'ppn', 'ppn_persen', 'total', 'masa_aktif');

    public function set_biaya($value) {
    	return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
    	return $this->localization->number_value($value);
    }

    public function set_total($value) {
    	return $this->localization->number_value($value);
    }
}