<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_m extends BaseModel {

    protected $table = 'produk';
    protected $primary_key = 'id';
    protected $fillable = array('kode','barcode','produk','jenis','id_ref','ppn_persen','laba_persen');

    public function produk($tanggal_mutasi) {
        $id_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first()->id_gudang;
        $this->db->select("
                produk.*,
                barang.kode as kode_barang,
                barang.nama as nama_barang,
                barang.id_satuan,
                satuan.satuan,
                CASE WHEN jenis = 'barang' THEN barang_stok.jumlah ELSE 0 END AS stok,
                CASE WHEN jenis = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) ELSE 0 END AS stok_awal,
                CASE WHEN jenis = 'barang' THEN COALESCE(mutasi.mutasi, 0) ELSE 0 END AS mutasi,
                CASE WHEN jenis = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) + COALESCE(mutasi.mutasi, 0) ELSE 0 END AS stok_akhir
            ")
            ->join("barang", "barang.id = produk.id_ref AND produk.jenis = 'barang'", "left")
            ->join("satuan", "satuan.id = barang.id_satuan", "left")
            ->join("obat", "obat.id_barang = barang.id_barang")
            ->join("barang_stok", "barang_stok.id_barang = barang.id AND produk.jenis = 'barang' AND barang_stok.id_gudang = '".$id_gudang."'", "left")
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
            ) mutasi", "mutasi.id_gudang = barang_stok.id_gudang AND mutasi.id_barang = barang_stok.id_barang", "left")
            ->join("jasa", "jasa.id = produk.id_ref AND produk.jenis = 'jasa'", "left");

        /*$this->db->query("
                SELECT
                produk.*,
                barang.kode as kode_barang,
                barang.nama as nama_barang,
                barang.id_satuan,
                satuan.satuan,
                CASE WHEN jenis = 'barang' THEN barang_stok.jumlah ELSE 0 END AS stok,
                CASE WHEN jenis = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) ELSE 0 END AS stok_awal,
                CASE WHEN jenis = 'barang' THEN COALESCE(mutasi.mutasi, 0) ELSE 0 END AS mutasi,
                CASE WHEN jenis = 'barang' THEN COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) + COALESCE(mutasi.mutasi, 0) ELSE 0 END AS stok_akhir
                FROM produk
                JOIN barang ON barang.id = produk.id_ref AND produk.jenis = 'barang'
                JOIN satuan ON satuan.id = barang.id_satuan
                JOIN barang_stok ON barang_stok.id_barang = barang.id
                LEFT JOIN (
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
                ) stok_awal ON stok_awal.id_gudang = barang_stok.id_gudang AND stok_awal.id_barang = barang_stok.id_barang
                JOIN (
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
                ) mutasi_awal ON mutasi_awal.id_gudang = barang_stok.id_gudang AND mutasi_awal.id_barang = barang_stok.id_barang
                JOIN (
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
                    WHERE tanggal_mutasi >= '".date('Y-m-d', strtotime($tanggal_mutasi))."'
                    AND tanggal_mutasi <= '".date('Y-m-d', strtotime($tanggal_mutasi))."'
                    GROUP BY id_gudang, id_barang
                ) mutasi ON mutasi.id_gudang = barang_stok.id_gudang AND mutasi.id_barang = barang_stok.id_barang
            ");*/
        return $this;
    }

    public function view_produk_barang() {
        $this->db->select('produk.*, barang.kode as kode_barang, barang.nama as nama_barang, barang.id_satuan, satuan.satuan')
            ->join('barang', 'barang.id = produk.id_ref')
            ->join('satuan', 'satuan.id = barang.id_satuan');
    }

    public function view_produk_jasa() {
        $this->db->select('produk.*, produk_jasa_komisi.komisi, jasa.jasa')
            ->join('jasa', 'jasa.id = produk.id_ref')
            ->join('produk_jasa_komisi', 'produk_jasa_komisi.id_produk = produk.id AND produk_jasa_komisi.id_cabang = 0 AND produk_jasa_komisi.id_petugas = 0');
    }

    public function view_satuan() {
        $this->db->select('satuan.id, satuan.satuan')
            ->join('(SELECT DISTINCT id_satuan, id_produk FROM produk_harga) produk_satuan', 'produk_satuan.id_produk = produk.id')
            ->join('satuan', 'satuan.id = produk_satuan.id_satuan');
    }

    public function enum_jenis() {
        return array(
            'barang' => 'Barang',
            'jasa' => 'Jasa',
            'paket' => 'Paket'
        );
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_laba_persen($value) {
        return $this->localization->number_value($value);
    }
}