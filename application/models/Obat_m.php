<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_m extends BaseModel {

    protected $table = 'obat';
    protected $primary_key = 'id';
    protected $fillable = array('id_barang', 'id_jenis_obat', 'kandungan_obat', 'dosis', 'hpp', 'diskon_persen', 'hna', 'ppn_persen', 'total');

    public function set_dosis($value) {
        return $this->localization->number_value($value);
    }

    public function set_hpp($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_hna($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }
}