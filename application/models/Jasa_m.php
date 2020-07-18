<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jasa_m extends BaseModel {

    protected $table = 'jasa';
    protected $fillable = array('jasa', 'id_kategori_jasa');

    public function view_jasa() {
        $this->db->select('jasa.*, kategori_jasa.kategori_jasa, produk.id AS id_produk, produk.kode, produk.produk, produk.ppn_persen AS produk_ppn_persen, produk_harga.harga, produk_jasa_komisi.komisi')
            ->join('kategori_jasa', 'kategori_jasa.id = jasa.id_kategori_jasa')
	        ->join('produk', 'produk.id_ref = jasa.id AND produk.jenis = \'jasa\'', 'left')
	        ->join('produk_harga', 'produk_harga.id_produk = produk.id AND produk_harga.id_cabang = 0 AND produk_harga.jumlah = 1 AND urutan = 1 AND utama = 1', 'left')
	        ->join('produk_jasa_komisi', 'produk_jasa_komisi.id_produk = produk.id AND produk_jasa_komisi.id_cabang = 0 AND produk_jasa_komisi.id_petugas = 0', 'left');
    }
}