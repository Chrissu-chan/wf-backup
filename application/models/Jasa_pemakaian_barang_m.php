<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jasa_pemakaian_barang_m extends BaseModel {

    protected $table = 'jasa_pemakaian_barang';
    protected $fillable = array('id_jasa', 'id_barang', 'id_satuan', 'jumlah');

    public function view_pemakaian_barang() {
        $this->db->select('jasa_pemakaian_barang.*, barang.kode as kode_barang, barang.nama as nama_barang, barang.id_satuan as barang_id_satuan, barang_satuan.satuan as barang_satuan, satuan.satuan')
            ->join('barang', 'barang.id = jasa_pemakaian_barang.id_barang')
            ->join('satuan barang_satuan', 'barang_satuan.id = barang.id_satuan')
            ->join('satuan', 'satuan.id = jasa_pemakaian_barang.id_satuan');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }
}