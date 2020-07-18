<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utang_m extends BaseModel {

    protected $table = 'utang';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'no_utang','jenis_utang','no_ref','nama','tanggal_utang','tanggal_jatuh_tempo','jumlah_utang','jumlah_bayar','sisa_utang','keterangan','lunas','file','flag_jurnal','proses_jurnal','batal');
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

    public function scope_utang() {
        return $this->db->where('lunas', 0);
    }

    public function set_tanggal_utang($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_jatuh_tempo($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_jumlah_utang($value) {
    	return $this->localization->number_value($value);
    }

    public function set_jumlah_bayar($value) {
    	return $this->localization->number_value($value);
    }

    public function set_sisa_utang($value) {
    	return $this->localization->number_value($value);
    }

    public function enum_jenis_utang() {
    	return array(
            'pembelian' => 'Pembelian',
    		'utang_usaha' => 'Utang Usaha'
    	);
    }
}