<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_cabang_m extends BaseModel {

    protected $table = 'produk_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_produk','id_cabang');

    public function view_produk() {
        $this->db->select('produk.*')
            ->join('produk', 'produk.id = produk_cabang.id_produk')
            ->group_by('produk.id');
    }

    public function view_produk_cabang() {
        $this->db->select('produk_cabang.id, produk_cabang.id_produk, cabang_gudang.id_gudang, cabang.id AS id_cabang, cabang.nama')
            ->join('cabang_gudang', 'cabang_gudang.id_cabang = produk_cabang.id_cabang OR produk_cabang.id_cabang = 0')
            ->join('cabang', 'cabang.id = cabang_gudang.id_cabang');
    }

    public function scope_auth() {
        $this->db->where_in('cabang.id', $this->auth->cabang);
    }

    public function scope_cabang_aktif() {
        $this->db->group_start()
            ->where('produk_cabang.id_cabang', $this->session->userdata('cabang')->id)
            ->or_where('produk_cabang.id_cabang', 0)
            ->group_end();
    }
}