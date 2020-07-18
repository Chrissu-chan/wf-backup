<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_barang_m extends BaseModel {

    protected $table = 'kategori_barang';
    protected $primary_key = 'id';
    protected $fillable = array('kategori_barang','parent_id');

    public function view_kategori() {
        $this->db->select('kategori_barang.*')
        ->join('barang', 'barang.id_kategori_barang = kategori_barang.id');
    }

    public function scope_parent() {
        $this->db->where('parent_id', 0);
    }
}