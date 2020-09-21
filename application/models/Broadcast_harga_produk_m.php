<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broadcast_harga_produk_m extends BaseModel {

    protected $table = 'broadcast_harga_produk';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'tanggal', 'id_produk', 'id_satuan', 'jumlah', 'harga_awal', 'harga_akhir');
	protected $authors = true;
	protected $timestamps = true;

	public function view_broadcast_harga_produk() {
		$this->db->select('broadcast_harga_produk.*, produk.kode, produk.produk, satuan.satuan, cabang.nama AS nama_cabang')
			->join('cabang', 'cabang.id = broadcast_harga_produk.id_cabang', 'left')
			->join('produk', 'produk.id = broadcast_harga_produk.id_produk')
			->join('satuan', 'satuan.id = broadcast_harga_produk.id_satuan', 'left')
			->where('broadcast_harga_produk.harga_awal != broadcast_harga_produk.harga_akhir');
	}

	public function scope_cabang_aktif() {
		$this->db->group_start();
		$this->db->where('id_cabang', 0) -> or_where('id_cabang', $this->session->userdata('cabang')->id);
		$this->db->group_end();
	}

	public function set_jumlah($value) {
		return $this->localization->number_value($value);
	}

	public function set_harga_awal($value) {
		return $this->localization->number_value($value);
	}

	public function set_harga_akhir($value) {
		return $this->localization->number_value($value);
	}
}