<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_produk_m extends BaseModel {

    protected $table = 'penjualan_produk';
    protected $primary_key = 'id';
    protected $fillable = array('id_penjualan', 'id_produk', 'id_satuan', 'jumlah', 'harga', 'diskon_persen', 'diskon', 'potongan', 'subtotal', 'ppn_persen', 'ppn', 'tuslah', 'total', 'expired');

    public function view_penjualan_produk() {
        $this->db->select('penjualan_produk.*, produk.kode AS kode_produk, produk.produk AS nama_produk, satuan.satuan as satuan')
            ->join('produk', 'produk.id = penjualan_produk.id_produk')
            ->join('satuan', 'satuan.id = penjualan_produk.id_satuan', 'left');
    }

	public function view_penjualan_barang() {
		$this->db->select('penjualan_produk.*, barang.kode AS kode_produk, barang.nama AS nama_produk, satuan.satuan as satuan')
			->join('barang', 'barang.id = penjualan_produk.id_produk')
			->join('satuan', 'satuan.id = penjualan_produk.id_satuan');
	}

    public function view_penjualan_nota() {
        $this->db->select('penjualan_produk.*,
	            penjualan.no_penjualan,
	            penjualan.tanggal,
	            penjualan.ppn as penjualan_ppn,
	            penjualan.total as penjualan_total,
	            produk.kode,
	            produk.produk,
	            satuan_produk.satuan as satuan_produk,
	            satuan_barang.satuan as satuan_barang,
	            pelanggan.nama as pelanggan,
	            users.name as kasir,
	            shift_waktu.shift_waktu,
	            konversi_satuan.konversi')
            ->join('penjualan', 'penjualan.id = penjualan_produk.id_penjualan')
            ->join('produk', 'produk.id = penjualan_produk.id_produk')
	        ->join('barang', 'barang.id = produk.id_ref AND produk.jenis = \'barang\'', 'left')
	        ->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = penjualan_produk.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left')
            ->join('users', 'users.username = penjualan.created_by')
            ->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan', 'left')
            ->join('satuan satuan_produk', 'satuan_produk.id = penjualan_produk.id_satuan', 'left')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan', 'left')
            ->join('shift_aktif', 'shift_aktif.id = penjualan.id_shift_aktif', 'left')
            ->join('shift_waktu', 'shift_waktu.id = shift_aktif.id_shift_waktu', 'left');
    }

    public function view_penjualan_rekap_harian() {
        $this->db->select('penjualan_produk.id_satuan,
	            penjualan.tanggal,
	            produk.kode,
	            produk.produk,
	            satuan_produk.satuan as satuan_produk,
	            barang.id_satuan as id_satuan_barang,
	            satuan_barang.satuan as satuan_barang,
	            COALESCE(konversi_satuan.konversi, 1) AS konversi,
	            sum(penjualan_produk.total) as total,
	            sum(penjualan_produk.ppn) as ppn,
	            sum(penjualan_produk.tuslah) as tuslah,
	            sum(penjualan_produk.diskon * penjualan_produk.jumlah) as diskon,
	            sum(penjualan_produk.potongan * penjualan_produk.jumlah) as potongan,
	            sum(penjualan_produk.jumlah * penjualan_produk.harga) as subtotal,
	            sum(penjualan_produk.jumlah) as jumlah')
            ->join('penjualan', 'penjualan.id = penjualan_produk.id_penjualan')
            ->join('produk', 'produk.id = penjualan_produk.id_produk')
            ->join('users', 'users.username = penjualan.created_by')
            ->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan', 'left')
            ->join('satuan satuan_produk', 'satuan_produk.id = penjualan_produk.id_satuan', 'left')
            ->join('shift_aktif', 'shift_aktif.id = penjualan.id_shift_aktif', 'left')
            ->join('shift_waktu', 'shift_waktu.id = shift_aktif.id_shift_waktu', 'left')
            ->join('barang', 'barang.id = produk.id_ref and produk.jenis = \'barang\'', 'left')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan', 'left')
            ->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = penjualan_produk.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left');
    }

    public function view_penjualan_rekap_bulanan() {
        $this->db->select('penjualan_produk.id_satuan,
            left(penjualan.tanggal, 7) as bulan,
            produk.kode,
            produk.produk,
            satuan_produk.satuan as satuan_produk,
            barang.id_satuan as id_satuan_barang,
            satuan_barang.satuan as satuan_barang,
            COALESCE(konversi_satuan.konversi, 1) AS konversi,
            sum(penjualan_produk.total) as total,
            sum(penjualan_produk.ppn) as ppn,
            sum(penjualan_produk.tuslah) as tuslah,
            sum(penjualan_produk.diskon * penjualan_produk.jumlah) as diskon,
            sum(penjualan_produk.potongan * penjualan_produk.jumlah) as potongan,
            sum(penjualan_produk.jumlah * penjualan_produk.harga) as subtotal,
            sum(penjualan_produk.jumlah) as jumlah')
            ->join('penjualan', 'penjualan.id = penjualan_produk.id_penjualan')
            ->join('produk', 'produk.id = penjualan_produk.id_produk')
            ->join('users', 'users.username = penjualan.created_by')
            ->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan', 'left')
            ->join('satuan satuan_produk', 'satuan_produk.id = penjualan_produk.id_satuan', 'left')
            ->join('shift_aktif', 'shift_aktif.id = penjualan.id_shift_aktif', 'left')
            ->join('shift_waktu', 'shift_waktu.id = shift_aktif.id_shift_waktu', 'left')
            ->join('barang', 'barang.id = produk.id_ref and produk.jenis = \'barang\'', 'left')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan', 'left')
            ->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = penjualan_produk.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_harga($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon($value) {
        return $this->localization->number_value($value);
    }

    public function set_potongan($value) {
        return $this->localization->number_value($value);
    }

    public function set_subtotal($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
        return $this->localization->number_value($value);
    }

    public function set_tuslah($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }

    public function scope_cabang() {
        $this->db->where('penjualan.id_cabang', $this->session->userdata('cabang')->id);
    }
}