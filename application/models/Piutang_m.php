<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang_m extends BaseModel {

    protected $table = 'piutang';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'no_piutang','jenis_piutang','no_ref','nama','tanggal_piutang','tanggal_jatuh_tempo','jumlah_piutang','jumlah_bayar','sisa_piutang','keterangan','lunas','file','flag_jurnal','proses_jurnal','batal');
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

    public function scope_lunas() {
        return $this->db->where('lunas', 1);
    }

    public function scope_piutang() {
        return $this->db->where('lunas', 0);
    }

    public function set_tanggal_piutang($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_jatuh_tempo($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah_piutang($value) {
    	return $this->localization->number_value($value);
    }

    public function set_jumlah_bayar($value) {
    	return $this->localization->number_value($value);
    }

    public function set_sisa_piutang($value) {
    	return $this->localization->number_value($value);
    }

    public function enum_jenis_piutang() {
	    return array(
		    'penjualan' => 'Penjualan',
		    'penjualan_cabang' => 'Penjualan Cabang',
		    'piutang_usaha' => 'Piutang Usaha'
	    );
    }
}