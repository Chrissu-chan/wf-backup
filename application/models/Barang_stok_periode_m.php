<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_stok_periode_m extends BaseModel {

    protected $table = 'barang_stok_periode';
    protected $primary_key = 'id';
    protected $fillable = array('periode','id_gudang','id_rak_gudang','id_barang','id_satuan','index_awal','index_akhir','tanggal_masuk_terakhir','tanggal_keluar_terakhir','jumlah');
	protected $authors = true;
	protected $timestamps = true;

    public function set_periode($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_index_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_index_akhir($value) {
        return $this->localization->number_value($value);
    }

    public function set_tanggal_masuk_terakhir($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_keluar_terakhir($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }
}