<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_stok_m extends BaseModel {

    protected $table = 'barang_stok';
    protected $primary_key = 'id';
    protected $fillable = array('id_gudang','id_rak_gudang','id_barang','id_satuan','index_awal','index_akhir','tanggal_masuk_terakhir','tanggal_keluar_terakhir','jumlah');
	protected $authors = true;
	protected $timestamps = true;
    protected $default = array(
        'id_rak_gudang' => 0
    );

    public function view_barang_stok() {
        $this->db->select('barang_stok.*, gudang.gudang, barang.kode AS kode_barang, barang.nama AS nama_barang, satuan.satuan, obat.total AS harga_beli')
            ->join('gudang', 'gudang.id = barang_stok.id_gudang')
            ->join('barang', 'barang.id = barang_stok.id_barang')
	        ->join('obat', 'obat.id_barang = barang.id')
            ->join('satuan', 'satuan.id = barang_stok.id_satuan')
            ->group_by('barang_stok.id_gudang, barang_stok.id_barang');
    }

    public function barang_stok_shift($id_shift_aktif) {
        $this->db->select('shift_aktif_stok.stok_awal, barang_stok.jumlah AS stok_akhir, gudang.gudang, barang.kode AS kode_barang, barang.nama AS nama_barang, satuan.satuan')
            ->join('shift_aktif_stok', 'shift_aktif_stok.id_gudang = barang_stok.id_gudang AND shift_aktif_stok.id_barang = barang_stok.id_barang AND shift_aktif_stok.id_shift_aktif = \''.$id_shift_aktif.'\'', 'left')
            ->join('gudang', 'gudang.id = barang_stok.id_gudang')
            ->join('barang', 'barang.id = barang_stok.id_barang')
            ->join('satuan', 'satuan.id = barang_stok.id_satuan')
            ->group_by('barang_stok.id_gudang, barang_stok.id_barang');
        return $this;
    }

	public function monitoring_shift($id_gudang, $id_shift_aktif) {
		$this->db->select(array(
				"barang.id",
				"barang.nama",
				"barang.kode",
				"satuan.satuan",
				"barang_stok.jumlah AS stok",
				"COALESCE(sum(CASE WHEN penjualan.tipe_mutasi = 'masuk' THEN penjualan.jumlah ELSE penjualan.jumlah * - 1 END ), 0) AS mutasi",
				"COALESCE(sum(penjualan.total), 0) AS total_penjualan",
				"COALESCE(shift_aktif_stok.stok_awal, 0) AS stok_awal",
				"COALESCE(shift_aktif_stok.stok_akhir, 0) AS stok_akhir",
				"(SELECT active FROM shift_aktif WHERE id = '".$id_shift_aktif."') AS shift_aktif"
			))
			->join("barang", "barang.id = barang_stok.id_barang")
			->join("satuan", "satuan.id = barang.id_satuan")
			->join("shift_aktif_stok", "shift_aktif_stok.id_barang = barang_stok.id_barang AND shift_aktif_stok.id_shift_aktif = '".$id_shift_aktif."'", "left")
			->join("(
				SELECT
					penjualan.id_shift_aktif,
					barang_stok_mutasi.id_barang,
					barang_stok_mutasi.tanggal_mutasi,
					barang_stok_mutasi.tipe_mutasi,
					barang_stok_mutasi.jenis_mutasi,
					barang_stok_mutasi.jumlah,
					penjualan_produk.total
				FROM
					barang_stok_mutasi
				JOIN penjualan_produk ON penjualan_produk.id = barang_stok_mutasi.id_ref
					AND barang_stok_mutasi.jenis_mutasi = 'penjualan'
					AND barang_stok_mutasi.id_gudang = '".$id_gudang."'
				JOIN penjualan ON penjualan.id = penjualan_produk.id_penjualan
					AND penjualan.id_shift_aktif = '".$id_shift_aktif."'
				) penjualan", "penjualan.id_barang = barang_stok.id_barang", "left")
			->where("barang_stok.id_gudang", $id_gudang)
			->group_by("barang.id");

		return $this;
	}

    public function nilai_keluar($id_gudang, $id_barang, $index_awal_keluar, $index_akhir_keluar) {
        return $this->db->query('
			SELECT
                mutasi.id_gudang AS id_gudang,
                mutasi.id_barang AS id_barang,
                mutasi.id_satuan AS id_satuan,
                MIN(mutasi.nilai) AS harga_min,
                MAX(mutasi.nilai) AS harga_max,
                SUM(mutasi.jumlah) AS jumlah,
                SUM((mutasi.nilai * mutasi.jumlah)) AS total,
                COALESCE (SUM((mutasi.nilai * mutasi.jumlah)) / SUM(mutasi.jumlah), 0) AS nilai
            FROM (
                SELECT
                    _mutasi.id_gudang AS id_gudang,
                    _mutasi.id_barang AS id_barang,
                    _mutasi.id_satuan AS id_satuan,
                    _mutasi.nilai AS nilai,
                    (CASE
                        WHEN (_mutasi.index_akhir_keluar > _mutasi.mutasi_index_akhir) THEN
                            ((_mutasi.mutasi_index_akhir - _mutasi.index_awal_keluar) + 1)
                        WHEN (_mutasi.index_awal_keluar > _mutasi.mutasi_index_awal AND _mutasi.index_akhir_keluar < _mutasi.mutasi_index_akhir) THEN
				            ((_mutasi.index_akhir_keluar - _mutasi.index_awal_keluar) + 1)
                        ELSE
                            ((_mutasi.index_akhir_keluar - _mutasi.mutasi_index_awal) + 1)
                        END
                    ) AS jumlah
                    FROM
                        (
                        SELECT
                            barang_stok.id_gudang AS id_gudang,
                            barang_stok.id_barang AS id_barang,
                            barang_stok.id_satuan AS id_satuan,
                            barang_stok.index_awal AS stok_index_awal,
                            barang_stok.index_akhir AS stok_index_akhir,
                            barang_stok_mutasi.index_awal AS mutasi_index_awal,
                            barang_stok_mutasi.index_akhir AS mutasi_index_akhir,
                            barang_stok_mutasi.nilai AS nilai,
                            \''.$index_awal_keluar.'\' AS index_awal_keluar,
                            \''.$index_akhir_keluar.'\' AS index_akhir_keluar
                        FROM
                            (
                                barang_stok
                                JOIN barang_stok_mutasi ON ((
                                    (barang_stok_mutasi.id_gudang = barang_stok.id_gudang)
                                    AND (barang_stok_mutasi.id_barang = barang_stok.id_barang)
                                    AND (barang_stok_mutasi.index_akhir >= barang_stok.index_awal)
                                    AND (barang_stok_mutasi.tipe_mutasi = \'masuk\' )
                                ))
                            )
                        WHERE
                            (barang_stok_mutasi.index_awal >= \''.$index_awal_keluar.'\' AND barang_stok_mutasi.index_awal <= \''.$index_awal_keluar.'\')
                            OR (barang_stok_mutasi.index_akhir >= \''.$index_akhir_keluar.'\' AND barang_stok_mutasi.index_akhir <= \''.$index_akhir_keluar.'\')
                            OR (barang_stok_mutasi.index_awal <= \''.$index_akhir_keluar.'\' AND barang_stok_mutasi.index_akhir >= \''.$index_awal_keluar.'\')
                        ) _mutasi
                    ) mutasi
            WHERE mutasi.id_gudang = \''.$id_gudang.'\' AND mutasi.id_barang = \''.$id_barang.'\'
            GROUP BY mutasi.id_gudang, mutasi.id_barang
            LIMIT 1');
    }

    public function stok_expired($range, $ignore = TRUE) {
        $this->db->select("
                barang_stok.*,
                expired.expired,
                gudang.gudang,
                barang.kode AS kode_barang,
                barang.nama AS nama_barang,
                satuan.satuan
            ")
            ->join("(
                SELECT
                    id_barang,
                    id_gudang,
                    MIN(expired) AS expired
                FROM barang_stok_mutasi
                WHERE tipe_mutasi = 'masuk'
                AND expired >= '".date('Y-m-d')."'
                AND CASE WHEN '".$range."' != '' THEN expired <= '".date('Y-m-d', strtotime('+'.$range.' day' , strtotime(date('Y-m-d'))))."' ELSE TRUE END
                AND ".($ignore ? 'NOT EXISTS (
                    SELECT 1 
                    FROM barang_ignore_expired 
                    WHERE barang_ignore_expired.id_gudang = barang_stok_mutasi.id_gudang 
                    AND barang_ignore_expired.id_barang = barang_stok_mutasi.id_barang 
                    AND barang_ignore_expired.expired = barang_stok_mutasi.expired
                )' : TRUE)."
                GROUP BY id_barang, id_gudang
            ) expired", "expired.id_gudang = barang_stok.id_gudang AND expired.id_barang = barang_stok.id_barang")
            ->join("gudang", "gudang.id = barang_stok.id_gudang")
            ->join("barang", "barang.id = barang_stok.id_barang")
            ->join("satuan", "satuan.id = barang_stok.id_satuan")
            ->group_by("barang_stok.id_gudang, barang_stok.id_barang");
        return $this;
    }

    public function stok($tanggal_mutasi, $tanggal_akhir = null) {
        if (!$tanggal_akhir) {
            $tanggal_akhir = $tanggal_mutasi;
        }
        $this->db->select("
                barang_stok.*,
                gudang.gudang,
                barang.kode AS kode_barang,
                barang.nama AS nama_barang,
                satuan.satuan,
                COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) AS stok_awal,
                COALESCE(mutasi.mutasi, 0) AS mutasi,
                COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) + COALESCE(mutasi.mutasi, 0) AS stok_akhir
            ")
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
                AND CASE WHEN
                    (SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_mutasi))."' AND id_barang = barang_stok_mutasi.id_barang) IS NOT NULL
                THEN
                    tanggal_mutasi >= DATE_ADD(CONCAT((SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_mutasi))."' AND id_barang = barang_stok_mutasi.id_barang),'-01'), INTERVAL 1 MONTH)
                ELSE
                    TRUE
                END
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
                WHERE tanggal_mutasi >= '".date('Y-m-d', strtotime($tanggal_mutasi))."'
                AND tanggal_mutasi <= '".date('Y-m-d', strtotime($tanggal_akhir))."'
                GROUP BY id_gudang, id_barang
            ) mutasi", "mutasi.id_gudang = barang_stok.id_gudang AND mutasi.id_barang = barang_stok.id_barang", "left")
            ->join("gudang", "gudang.id = barang_stok.id_gudang")
            ->join("barang", "barang.id = barang_stok.id_barang")
            ->join("satuan", "satuan.id = barang_stok.id_satuan");

        return $this;
    }

    public function stok_awal($tanggal_awal) {
        $this->db->select("
                barang_stok.*,
                gudang.gudang,
                barang.kode AS kode_barang,
                barang.nama AS nama_barang,
                satuan.satuan,
                COALESCE(stok_awal.stok, 0) + COALESCE(mutasi_awal.mutasi, 0) AS stok_awal
            ")
            ->join("(
                SELECT
                    a.id_gudang,
                    a.id_barang,
                    a.jumlah AS stok
                FROM barang_stok_periode a
                JOIN (
                    SELECT id_gudang, id_barang, MAX(periode) AS periode
                    FROM barang_stok_periode
                    WHERE periode < '".date('Y-m', strtotime($tanggal_awal))."'
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
                WHERE tanggal_mutasi < '".date('Y-m-d', strtotime($tanggal_awal))."'
                AND CASE WHEN
                    (SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_awal))."' AND id_barang = barang_stok_mutasi.id_barang) IS NOT NULL
                THEN
                    tanggal_mutasi >= DATE_ADD(CONCAT((SELECT MAX(periode) FROM barang_stok_periode WHERE periode < '".date('Y-m', strtotime($tanggal_awal))."' AND id_barang = barang_stok_mutasi.id_barang),'-01'), INTERVAL 1 MONTH)
                ELSE
                    TRUE
                END
                GROUP BY id_gudang, id_barang
            ) mutasi_awal", "mutasi_awal.id_gudang = barang_stok.id_gudang AND mutasi_awal.id_barang = barang_stok.id_barang", "left")
            ->join("gudang", "gudang.id = barang_stok.id_gudang")
            ->join("barang", "barang.id = barang_stok.id_barang")
            ->join("satuan", "satuan.id = barang_stok.id_satuan");

        return $this;
    }

    public function view_stock_opname_barang() {
        $this->db->select('barang_stok.*')
            ->join('stock_opname_barang', 'stock_opname_barang.id_barang = barang_stok.id_barang AND stock_opname_barang.id_cabang = \''.$this->session->userdata('cabang')->id.'\'');
    }

    public function set_index_awal($value) {
        return $this->localization->number_value($value);
    }

    public function set_index_akhir($value) {
        return $this->localization->number_value($value);
    }

    public function set_tanggal_masuk_terakhir($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_keluar_terakhir($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function enum_range_stok() {
	    return array(
		    '30' => '1 Bulan',
		    '60' => '2 Bulan',
		    '90' => '3 Bulan',
		    '180' => '6 Bulan',
		    '360' => '1 Tahun',
		    '' => '> 1 Tahun'
	    );
    }
}