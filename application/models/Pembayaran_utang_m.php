<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_utang_m extends BaseModel {

    protected $table = 'pembayaran_utang';
    protected $primary_key = 'id';
    protected $fillable = array('id_utang', 'tanggal_bayar', 'jumlah_bayar', 'file', 'id_kas_bank', 'no_ref_pembayaran', 'keterangan', 'status', 'flag_jurnal', 'proses_jurnal', 'batal', 'jenis_batal', 'alasan_batal', 'deleted_by', 'deleted_at');
	protected $default = array(
		'status' => 'approved',
		'flag_jurnal' => 'true',
		'proses_jurnal' => 'false',
		'batal' => 0
	);
    protected $authors = true;
    protected $timestamps = true;

    public function view_utang() {
        return $this->db->select('pembayaran_utang.*, utang.no_utang, utang.jenis_utang')
            ->join('utang', 'utang.id = pembayaran_utang.id_utang');
    }

    public function view_kas_bank() {
        return $this->db->select('pembayaran_utang.*, kas_bank.nama as kas_bank, kas_bank.jenis_kas_bank, kas_bank.nomor_rekening, kas_bank.nama_rekening, bank.bank, bank.telepon')
            ->join('kas_bank', 'kas_bank.id = pembayaran_utang.id_kas_bank', 'left')
            ->join('bank', 'bank.id = kas_bank.id_bank', 'left');
    }

	public function enum_jenis_batal() {
		return array(
			'cancel' => 'Cancel',
			'retur' => 'Retur'
		);
	}

    public function set_tanggal_bayar($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah_bayar($value) {
    	return $this->localization->number_value($value);
    }

    public function set_sisa_utang($value) {
    	return $this->localization->number_value($value);
    }

}