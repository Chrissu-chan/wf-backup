<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname_detail_m extends BaseModel {

    protected $table = 'stock_opname_detail';
    protected $primary_key = 'id';
    protected $fillable = array('id_stock_opname', 'id_obat', 'selisih', 'hna', 'expired', 'so_by');

    public function view_stock_opname_detail() {
        $this->db->select('stock_opname_detail.*, barang.id AS id_barang, barang.kode AS kode_barang, barang.nama AS nama_barang, barang_stok.jumlah AS stok_awal, obat.total AS harga_beli')
	        ->join('stock_opname', 'stock_opname.id = stock_opname_detail.id_stock_opname')
            ->join('barang', 'barang.id = stock_opname_detail.id_obat')
	        ->join('obat', 'obat.id_barang = stock_opname_detail.id_obat')
	        ->join('barang_stok', 'barang_stok.id_barang = barang.id AND barang_stok.id_gudang = stock_opname.id_gudang');
    }

    public function scope_done() {
        $this->db->where('so_by IS NOT NULL', NULL);
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_stok_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_stok_akhir($value) {
        return $this->localization->number_value($value);
    }

	public function set_hna($value) {
		return $this->localization->number_value($value);
	}

	public function set_expired($value) {
		return date('Y-m-d', strtotime($value));
	}
}