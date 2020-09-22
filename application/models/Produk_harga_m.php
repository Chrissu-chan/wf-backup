<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_harga_m extends BaseModel {

    protected $table = 'produk_harga';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','id_produk','id_satuan','jumlah','margin_persen','laba_persen','harga','ppn_persen','ppn','urutan','utama');

	public function view_margin_laba() {
		$this->db->select('
				produk.id,
				produk.kode,
				produk.barcode,
				produk.produk,
				COALESCE ( cabang.nama, \'GENERAL\' ) AS cabang,
				satuan.satuan,
				produk_harga.jumlah,
				produk_harga.margin_persen,
				((( produk_harga.harga / COALESCE ( konversi_satuan.konversi, 1 )) - obat.total ) / obat.total ) * 100 AS laba_persen,
				COALESCE ( konversi_satuan.konversi, 1 ) AS konversi,
				obat.total AS harga_beli_terakhir,
				produk_harga.harga
			')
			->join('produk', 'produk.id = produk_harga.id_produk')
			->join('barang', 'barang.id = produk.id_ref')
			->join('obat', 'obat.id_barang = barang.id')
			->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = produk_harga.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left')
			->join('satuan', 'satuan.id = produk_harga.id_satuan')
			->join('cabang', 'cabang.id = produk_harga.id_cabang', 'left')
			->where('produk.jenis', 'barang')
			->where('laba_persen < ', 'produk_harga.margin_persen', FALSE)
			->group_start()
				->where('produk_harga.id_cabang', $this->session->userdata('cabang')->id)
				->or_where('produk_harga.id_cabang', 0)
			->group_end()
			->order_by('
				produk.kode,
				produk_harga.id_cabang,
				produk_harga.id_satuan,
				produk_harga.jumlah
			');
	}

	public function view_produk_harga() {
		$this->db->select('produk_harga.*, produk.kode, produk.barcode, produk.produk, cabang.nama AS cabang, barang.id_satuan AS barang_id_satuan, satuan.satuan AS barang_satuan')
			->join('produk', 'produk.id = produk_harga.id_produk AND produk_harga.jumlah = 1 AND urutan = 1')
			->join('cabang', 'cabang.id = produk_harga.id_cabang', 'left')
			->join('barang', 'barang.id = produk.id_ref AND produk.jenis = \'barang\'', 'left')
			->join('satuan', 'satuan.id = barang.id_satuan', 'left')
			->group_by('produk_harga.id_cabang, produk_harga.id_produk');
	}

	public function view_produk_harga_browse() {
		$this->db->select('harga.*, produk.kode, produk.barcode, produk.produk, COALESCE(rak_gudang.rak, \'General\') AS rak, produk.jenis, produk.kategori, produk.kandungan, barang_stok.jumlah AS stok')
			->join('view_produk_browse AS produk', 'produk.id = produk_harga.id_produk AND produk_harga.jumlah = 1 AND urutan = 1 AND utama = 1')
			->join('produk_cabang', 'produk_cabang.id_produk = produk.id')
			->join('(SELECT
					*
				FROM
					( SELECT * FROM produk_harga where utama = 1 and jumlah = 1 GROUP BY id_cabang, id_produk ORDER BY id_cabang DESC) x
				GROUP BY
					id_produk) harga', 'harga.id_produk = produk.id')
			->join('barang', 'barang.id = produk.id_ref AND produk.jenis_produk = \'barang\'', 'left')
			->join('cabang_gudang', 'cabang_gudang.id_cabang = harga.id_cabang OR cabang_gudang.id_cabang = \''.$this->session->userdata('cabang')->id.'\'', 'left')
			->join('barang_stok', 'barang_stok.id_barang = barang.id AND barang_stok.id_gudang = cabang_gudang.id_gudang AND cabang_gudang.utama = 1', 'left')
			->join('rak_gudang', 'rak_gudang.id = barang.id_rak_gudang', 'left')
			->group_start()
				->where('produk_cabang.id_cabang', $this->session->userdata('cabang')->id)
				->or_where('produk_cabang.id_cabang', 0)
			->group_end()
			->group_by('produk.id, produk_cabang.id_cabang');
	}

    public function harga_satuan($id_produk) {
        $harga_satuan = array();
        $result = $this->select('produk_harga.*, satuan.satuan')
            ->join('satuan', 'satuan.id = produk_harga.id_satuan')
            ->scope('cabang')
            ->where('id_produk', $id_produk)
            ->get();
        if (!$result) {
            $result = $this->select('produk_harga.*, satuan.satuan')
                ->join('satuan', 'satuan.id = produk_harga.id_satuan')
                ->scope('general')
                ->where('id_produk', $id_produk)
                ->get();
        }
        if ($result) {
            foreach ($result as $produk_harga) {
                $harga_satuan['id_'.$produk_harga->id_satuan]['id_satuan'] = $produk_harga->id_satuan;
                $harga_satuan['id_'.$produk_harga->id_satuan]['satuan'] = $produk_harga->satuan;
                $harga_satuan['id_'.$produk_harga->id_satuan]['utama'] = ($produk_harga->utama) ? true : (isset($harga_satuan['id_'.$produk_harga->id_satuan]['utama']) ? $harga_satuan['id_'.$produk_harga->id_satuan]['utama'] : false);
                $harga_satuan['id_'.$produk_harga->id_satuan]['harga'][] = array(
                    'jumlah' => $produk_harga->jumlah,
                    'harga' => $produk_harga->harga,
                    'ppn_persen' => $produk_harga->ppn_persen
                );
            };
        }
        return $harga_satuan;
    }

    public function view_harga_satuan() {
        $this->db->select('produk_harga.*, satuan.satuan')
            ->join('satuan', 'satuan.id = produk_harga.id_satuan', 'left');
    }

    public function scope_cabang() {
        $this->db->where('produk_harga.id_cabang', $this->session->userdata('cabang')->id);
    }
    public function scope_general() {
        $this->db->where('produk_harga.id_cabang', 0);
    }

    public function scope_utama() {
        $this->db->where('utama', 1);
    }

    public function set_urutan($value) {
        return $this->localization->number_value($value);
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_harga($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }
}