<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_m extends BaseModel {

    protected $table = 'penjualan';
    protected $primary_key = 'id';
    protected $fillable = array('id_penjualan', 'resep', 'tanggal', 'total');
    protected $authors = true;
    protected $timestamps = true;

    public function set_total($value) {
        return $this->localization->number_value($value);
    }
}