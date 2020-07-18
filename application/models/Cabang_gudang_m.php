<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang_gudang_m extends BaseModel {

    protected $table = 'cabang_gudang';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','id_gudang','utama');

    public function view_cabang_gudang() {
        $this->db->select('gudang.*, cabang_gudang.utama, cabang.nama, cabang.alamat, cabang.telepon, cabang.keterangan')
            ->join('cabang', 'cabang.id = cabang_gudang.id_cabang')
            ->join('gudang', 'gudang.id = cabang_gudang.id_gudang');
    }

    public function scope_aktif_cabang() {
    	$this->db->where('id_cabang', $this->session->userdata('cabang')->id);
    }

    public function scope_utama() {
    	$this->db->where('utama', 1);
    }

    public function scope_auth() {
        $this->db->where_in('id_cabang', $this->auth->cabang);
    }
}