<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_m extends BaseModel {

    protected $table = 'penjualan';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','id_shift_aktif','jenis_penjualan','id_pelanggan','no_penjualan','tanggal','diskon_persen','subtotal','ppn','tuslah','total','metode_pembayaran','jatuh_tempo','id_kas_bank','no_ref', 'bayar', 'kembali', 'flag_jurnal', 'proses_jurnal', 'batal', 'jenis_batal', 'alasan_batal', 'deleted_by', 'deleted_at');
    protected $authors = true;
    protected $timestamps = true;

    public function __construct() {
        $this->default = array(
            'id_cabang' => $this->session->userdata('cabang')->id,
	        'flag_jurnal' => 'true',
	        'proses_jurnal' => 'false',
	        'batal' => 0
        );
    }

    public function view_penjualan() {
        $this->db->select(array(
		        'penjualan.*',
	            'CASE WHEN penjualan.jenis_penjualan = \'cabang\' THEN cabang.nama ELSE pelanggan.nama END AS pelanggan',
	            'kas_bank.nama AS kas_bank'
            ))
            ->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan', 'left')
	        ->join('cabang', 'cabang.id = penjualan.id_pelanggan', 'left')
            ->join('kas_bank', 'kas_bank.id = penjualan.id_kas_bank');
    }

    public function scope_cabang_aktif() {
        $this->db->where('penjualan.id_cabang', $this->session->userdata('cabang')->id);
    }

	public function scope_cabang() {
		$this->db->where('jenis_penjualan', 'cabang');
	}

	public function scope_umum() {
		$this->db->where('jenis_penjualan', 'umum');
	}

	public function scope_approved() {
		$this->db->where('penjualan.batal', 0);
	}

    public function enum_metode_pembayaran() {
        return array(
            'tunai' => 'Tunai',
            'utang' => 'Utang'
        );
    }

	public function enum_jenis_penjualan() {
		return array(
			'cabang' => 'Cabang',
			'umum' => 'Umum'
		);
	}

	public function enum_jenis_batal() {
		return array(
			'cancel' => 'Cancel',
			'retur' => 'Retur'
		);
	}

    public function set_tanggal($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_jatuh_tempo($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_subtotal($value) {
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

    public function set_bayar($value) {
        return $this->localization->number_value($value);
    }

    public function set_kembali($value) {
        return $this->localization->number_value($value);
    }
}