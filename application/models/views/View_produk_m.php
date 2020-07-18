<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_produk_m extends BaseModel {

    protected $table = 'view_produk';
    protected $primary_key = 'kode_produk';

    public function produk() {
        $id_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first()->id_gudang;
        $this->db->select("
                view_produk.*,
                CASE WHEN jenis_produk = 'barang' THEN barang_stok.jumlah ELSE 0 END AS stok,

            ")
            ->join("produk_cabang", "produk_cabang.id_produk = view_produk.id_produk")
            ->join("barang_stok", "barang_stok.id_barang = view_produk.id_ref AND view_produk.jenis_produk = 'barang' AND barang_stok.id_gudang = '".$id_gudang."'", "left");
        return $this;
    }

	/*public function produk($tanggal_mutasi) {
		$id_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first()->id_gudang;
		$this->db->select("
                view_produk.*,
                CASE WHEN jenis_produk = 'barang' THEN barang_stok.jumlah ELSE 0 END AS stok,
                CASE WHEN jenis_produk = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) ELSE 0 END AS stok_awal,
                CASE WHEN jenis_produk = 'barang' THEN COALESCE(mutasi.mutasi, 0) ELSE 0 END AS mutasi,
                CASE WHEN jenis_produk = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) + COALESCE(mutasi.mutasi, 0) ELSE 0 END AS stok_akhir
            ")
			->join("produk_cabang", "produk_cabang.id_produk = view_produk.id_produk")
			->join("barang_stok", "barang_stok.id_barang = view_produk.id_ref AND view_produk.jenis_produk = 'barang' AND barang_stok.id_gudang = '".$id_gudang."'", "left")
			->join("(
                SELECT
                    a.id_gudang,
                    a.id_barang,
                    a.jumlah AS stok
                FROM barang_stok_periode a
                JOIN (
                    SELECT id_gudang, id_barang, MAX(periode) AS periode
                    FROM barang_stok_periode
                    WHERE periode < '".date('Y-m', strtotime($tanggal_mutasi))."'
                    GROUP BY id_gudang, id_barang
                ) b ON b.id_gudang = a.id_gudang AND b.id_barang = a.id_barang AND b.periode = a.periode
            ) stok_awal", "stok_awal.id_gudang = barang_stok.id_gudang AND stok_awal.id_barang = barang_stok.id_barang", "left")
			->join("(
                SELECT
                    id_gudang,
                    id_barang,
                    SUM(
                        CASE WHEN tipe_mutasi = 'masuk' THEN
                            jumlah
                        ELSE
                            jumlah * -1
                        END
                    ) AS mutasi
                FROM barang_stok_mutasi
                WHERE tanggal_mutasi < '".date('Y-m-d', strtotime($tanggal_mutasi))."'
                AND CASE WHEN (SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_mutasi))."' AND id_barang = barang_stok_mutasi.id_barang) IS NOT NULL THEN tanggal_mutasi >= DATE_ADD(CONCAT((SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_mutasi))."' AND id_barang = barang_stok_mutasi.id_barang),'-01'), INTERVAL 1 MONTH) ELSE TRUE END
                GROUP BY id_gudang, id_barang
            ) mutasi_awal", "mutasi_awal.id_gudang = barang_stok.id_gudang AND mutasi_awal.id_barang = barang_stok.id_barang", "left")
			->join("(
                SELECT
                    id_gudang,
                    id_barang,
                    SUM(
                        CASE WHEN tipe_mutasi = 'masuk' THEN
                            jumlah
                        ELSE
                            jumlah * -1
                        END
                    ) AS mutasi
                FROM barang_stok_mutasi
                WHERE tanggal_mutasi = '".date('Y-m-d', strtotime($tanggal_mutasi))."'
                GROUP BY id_gudang, id_barang
            ) mutasi", "mutasi.id_gudang = barang_stok.id_gudang AND mutasi.id_barang = barang_stok.id_barang", "left");
		return $this;
	}*/

    public function scope_cabang_aktif() {
        $this->db->group_start()
            ->where('produk_cabang.id_cabang', $this->session->userdata('cabang')->id)
            ->or_where('produk_cabang.id_cabang', 0)
            ->group_end();
    }

	public function scope_utama() {
		$this->db->where('view_produk.utama', 1);
	}
}