<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_bahan_baku_m extends BaseModel {

    protected $table = 'produksi_bahan_baku';
    protected $primary_key = 'id';
    protected $fillable = array('id_produksi','id_barang','id_satuan','jumlah','hpp','total');

    public function view_bahan_baku() {
        $this->db->select('produksi_bahan_baku.*, barang.kode as kode_barang, barang.nama as nama_barang, barang.id_satuan as barang_id_satuan, barang_satuan.satuan as barang_satuan, satuan.satuan')
            ->join('barang', 'barang.id = produksi_bahan_baku.id_barang')
            ->join('satuan barang_satuan', 'barang_satuan.id = barang.id_satuan')
            ->join('satuan', 'satuan.id = produksi_bahan_baku.id_satuan');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_hpp($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }
}