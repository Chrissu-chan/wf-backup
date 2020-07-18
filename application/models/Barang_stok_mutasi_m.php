<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_stok_mutasi_m extends BaseModel {

    protected $table = 'barang_stok_mutasi';
    protected $primary_key = 'id';
    protected $fillable = array('tanggal_mutasi','tipe_mutasi','jenis_mutasi','id_ref','id_gudang','id_rak_gudang','id_barang','id_satuan','index_awal','index_akhir','jumlah','nilai','total', 'expired', 'batch_number');
	protected $authors = true;
	protected $timestamps = true;

	public function view_barang_stok_mutasi() {
		$this->db->select('
				CASE WHEN barang_stok_mutasi.jenis_mutasi = \'pembelian\' THEN pembelian.no_pembelian ELSE penjualan.no_penjualan END AS no_nota,
				CASE WHEN barang_stok_mutasi.jenis_mutasi = \'pembelian\' THEN supplier.supplier ELSE CASE WHEN penjualan.jenis_penjualan = \'umum\' THEN pelanggan.nama ELSE cabang.nama END END AS keterangan,
				barang_stok_mutasi.tanggal_mutasi,
				barang_stok_mutasi.jenis_mutasi,
				barang_stok_mutasi.tipe_mutasi,
				barang_stok_mutasi.expired,
				barang_stok_mutasi.batch_number,
				CASE WHEN barang_stok_mutasi.tipe_mutasi = \'masuk\' THEN barang_stok_mutasi.jumlah ELSE barang_stok_mutasi.jumlah * -1 END AS jumlah,
				barang_stok_mutasi.created_by,
				pembelian.id AS id_pembelian,
				CASE WHEN barang_stok_mutasi.jenis_mutasi = \'pembelian\' THEN pembelian.id ELSE penjualan.id END AS id_transaksi
			')
			->join('pembelian_barang', 'pembelian_barang.id = barang_stok_mutasi.id_ref AND barang_stok_mutasi.jenis_mutasi = \'pembelian\'', 'left')
			->join('pembelian', 'pembelian.id = pembelian_barang.id_pembelian', 'left')
			->join('supplier', 'supplier.id = pembelian.id_supplier', 'left')
			->join('penjualan_produk', 'penjualan_produk.id = barang_stok_mutasi.id_ref AND barang_stok_mutasi.jenis_mutasi = \'penjualan\'', 'left')
			->join('penjualan', 'penjualan.id = penjualan_produk.id_penjualan', 'left')
			->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan AND penjualan.jenis_penjualan = \'umum\'', 'left')
			->join('cabang', 'cabang.id = penjualan.id_pelanggan AND penjualan.jenis_penjualan = \'cabang\'', 'left')
			->order_by('barang_stok_mutasi.tanggal_mutasi');
	}

    public function view_stok_expired_detail() {
        $this->db->select("
                barang_stok_mutasi.*,
                keluar_akhir,
                (CASE WHEN keluar_akhir BETWEEN index_awal AND index_akhir THEN
                    index_akhir - keluar_akhir
                ELSE
                    jumlah
                END) AS stok
            ")
            ->join("(
                    SELECT
                        id_barang,id_gudang,
                        MAX(index_akhir) AS keluar_akhir
                    FROM barang_stok_mutasi
                    WHERE tipe_mutasi='keluar'
                    GROUP BY id_gudang, id_barang
                ) mutasi_keluar", "mutasi_keluar.id_gudang = barang_stok_mutasi.id_gudang AND
                mutasi_keluar.id_barang = barang_stok_mutasi.id_barang AND
                mutasi_keluar.keluar_akhir < barang_stok_mutasi.index_akhir", "left")
            ->where("tipe_mutasi", "masuk")
	        ->group_start()
                ->where("barang_stok_mutasi.expired >= ", date('Y-m-d'))
                ->or_where("barang_stok_mutasi.expired IS NULL ")
            ->group_end();
    }

    public function view_stok_expired_archives() {
        $this->db->select("
                barang_stok_mutasi.*,
                keluar_akhir,
                (CASE WHEN keluar_akhir BETWEEN index_awal AND index_akhir THEN
                    index_akhir - keluar_akhir
                ELSE
                    jumlah
                END) AS stok
            ")
            ->join("(
                    SELECT
                        id_barang,id_gudang,
                        MAX(index_akhir) AS keluar_akhir
                    FROM barang_stok_mutasi
                    WHERE tipe_mutasi='keluar'
                    GROUP BY id_gudang, id_barang
                ) mutasi_keluar", "mutasi_keluar.id_gudang = barang_stok_mutasi.id_gudang AND
                mutasi_keluar.id_barang = barang_stok_mutasi.id_barang AND
                mutasi_keluar.keluar_akhir < barang_stok_mutasi.index_akhir", "left")
            ->where("tipe_mutasi", "masuk")
            ->where("barang_stok_mutasi.expired < ", date('Y-m-d'));
        return $this;
    }

    public function set_tanggal_mutasi($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_index_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_index_akhir($value) {
        return $this->localization->number_value($value);
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_nilai($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }

    public function set_expired($value) {
        return date('Y-m-d', strtotime($value));
    }

	public function scope_penjualan() {
		$this->db->where('barang_stok_mutasi.jenis_mutasi', 'penjualan');
	}
}