<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_paket_m extends BaseModel {

    protected $table = 'produk_paket';
    protected $primary_key = 'id';
    protected $fillable = array('id_produk','id_produk_detail','id_satuan','jumlah','nilai');

    public function view_produk_detail() {
        $this->db->select('produk_paket.*, produk.kode AS kode_produk, produk.produk AS nama_produk, produk.jenis, satuan.satuan')
            ->join('produk', 'produk.id = produk_paket.id_produk_detail')
            ->join('satuan', 'satuan.id = produk_paket.id_satuan', 'left');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_nilai($value) {
        return $this->localization->number_value($value);
    }
}