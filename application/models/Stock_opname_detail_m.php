<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname_detail_m extends BaseModel {

    protected $table = 'stock_opname_detail';
    protected $primary_key = 'id';
    protected $fillable = array('id_stock_opname', 'id_obat', 'jumlah', 'stok_awal', 'stok_akhir', 'so_by');

    public function view_stock_opname_detail() {
        $this->db->select('stock_opname_detail.*, barang.kode AS kode_barang, barang.nama AS nama_barang')
            ->join('barang', 'barang.id = stock_opname_detail.id_obat');
    }

    public function view_barang_so() {
        $this->db->where('so_by IS NOT NULL', NULL)
            ->group_by('id_obat');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_stok_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_stok_awkhir($value) {
        return $this->localization->number_value($value);
    }
}