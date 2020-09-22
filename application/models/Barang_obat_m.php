<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_obat_m extends Barang_m {

    public function view_barang() {
        $this->db->select('barang.*, satuan.satuan, satuan.grup, obat.id AS id_obat, obat.id_jenis_obat, obat.kandungan_obat, obat.dosis, obat.hpp, obat.diskon_persen, obat.hna, obat.ppn_persen, obat.total, produsen.nama_produsen as nama_produsen, obat.id_produsen as id_produsen')
            ->join('satuan', 'satuan.id = barang.id_satuan')
            ->join('obat', 'obat.id_barang = barang.id')
            ->join('produsen', 'produsen.id = obat.id_produsen','left');
        }
        
    public function view_obat() {
            $this->db->select('
            barang.*, obat.id_jenis_obat, obat.kandungan_obat, obat.dosis, obat.hpp, obat.diskon_persen, obat.hna, obat.ppn_persen, obat.total, kategori_barang.kategori_barang, jenis_barang.jenis_barang, satuan.grup, satuan.satuan, satuan_beli.satuan AS satuan_beli, jenis_obat.jenis_obat,
            barang_kategori_obat.kategori_obat, barang_fungsi_obat.fungsi_obat,
            obat.dosis, barang.minus, obat.hpp, obat.diskon_persen, obat.hna, obat.ppn_persen, obat.total, produsen.nama_produsen as nama_produsen, produk.id as id_produk
            ')
            ->join('obat', 'obat.id_barang = barang.id')
            ->join('kategori_barang', 'kategori_barang.id = barang.id_kategori_barang', 'left')
	        ->join('view_barang_kategori_obat barang_kategori_obat', 'barang_kategori_obat.id_barang = barang.id', 'left')
	        ->join('view_barang_fungsi_obat barang_fungsi_obat', 'barang_fungsi_obat.id_barang = barang.id', 'left')
            ->join('jenis_barang', 'jenis_barang.id = barang.id_jenis_barang')
            ->join('satuan', 'satuan.id = barang.id_satuan')
            ->join('satuan AS satuan_beli', 'satuan_beli.id = barang.id_satuan_beli', 'left')
            ->join('jenis_obat', 'jenis_obat.id = obat.id_jenis_obat', 'left')
            ->join('produk', "barang.id = produk.id_ref AND produk.jenis = 'barang'", 'left')
            ->join('produsen','produsen.id = obat.id_produsen','left');
        }

	public function view_obat_export() {
		$this->db->select('
                barang.*, obat.id_jenis_obat, obat.kandungan_obat, obat.dosis, obat.hpp, obat.diskon_persen, obat.hna, obat.ppn_persen, obat.total, kategori_barang.kategori_barang, jenis_barang.jenis_barang, satuan.grup, satuan.satuan, satuan_beli.satuan AS satuan_beli, jenis_obat.jenis_obat,
                barang_kategori_obat.kategori_obat, barang_fungsi_obat.fungsi_obat,
                obat.dosis, barang.minus, obat.hpp, obat.diskon_persen, obat.hna, obat.ppn_persen, obat.total,
                produk.id AS id_produk, produk.produk, produk.ppn_persen AS produk_ppn_persen, produk.laba_persen AS produk_laba_persen, produk_harga.margin_persen, produk_harga.margin_persen_atas, produk_harga.harga
            ')
			->join('obat', 'obat.id_barang = barang.id')
			->join('kategori_barang', 'kategori_barang.id = barang.id_kategori_barang', 'left')
			->join('view_barang_kategori_obat barang_kategori_obat', 'barang_kategori_obat.id_barang = barang.id', 'left')
			->join('view_barang_fungsi_obat barang_fungsi_obat', 'barang_fungsi_obat.id_barang = barang.id', 'left')
			->join('jenis_barang', 'jenis_barang.id = barang.id_jenis_barang')
			->join('satuan', 'satuan.id = barang.id_satuan')
			->join('satuan AS satuan_beli', 'satuan_beli.id = barang.id_satuan_beli', 'left')
			->join('jenis_obat', 'jenis_obat.id = obat.id_jenis_obat', 'left')
			->join('produk', 'produk.id_ref = barang.id AND produk.jenis = \'barang\'', 'left')
			->join('produk_harga', 'produk_harga.id_produk = produk.id AND produk_harga.id_cabang = 0 AND produk_harga.jumlah = 1 AND urutan = 1 AND utama = 1', 'left');
    }
}
