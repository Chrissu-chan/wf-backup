<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_resep_produk_m extends BaseModel {

    protected $table = 'penjualan_produk';
    protected $primary_key = 'id';
    protected $fillable = array('id_penjualan_resep', 'id_produk', 'id_satuan', 'jumlah', 'harga', 'diskon_persen', 'diskon', 'potongan', 'subtotal', 'ppn_persen', 'ppn', 'total');

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_harga($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon($value) {
        return $this->localization->number_value($value);
    }

    public function set_potongan($value) {
        return $this->localization->number_value($value);
    }

    public function set_subtotal($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }
}