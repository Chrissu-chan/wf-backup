<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_m extends BaseModel {

    protected $table = 'produksi';
    protected $primary_key = 'id';
    protected $fillable = array('no_produksi','tanggal_produksi','id_barang_produksi','id_barang','id_satuan','jumlah','total_bahan_baku','total_biaya_lainnya','total_biaya_produksi','hpp','keterangan');
    protected $authors = true;
    protected $timestamps = true;

    public function view_produksi() {
        $this->db->select('produksi.*, barang_produksi.nama, barang.kode AS kode_barang, barang.nama AS nama_barang, satuan.satuan')
            ->join('barang_produksi', 'barang_produksi.id = produksi.id_barang_produksi')
            ->join('barang', 'barang.id = produksi.id_barang')
            ->join('satuan', 'satuan.id = produksi.id_satuan');
    }

    public function set_tanggal_produksi($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_total_bahan_baku($value) {
        return $this->localization->number_value($value);
    }

    public function set_total_biaya_lainnya($value) {
        return $this->localization->number_value($value);
    }

    public function set_total_biaya_produksi($value) {
        return $this->localization->number_value($value);
    }

    public function set_hpp($value) {
        return $this->localization->number_value($value);
    }
}