<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_barang_m extends BaseModel {

    protected $table = 'jenis_barang';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_barang');

    public function view_jenis() {
        $this->db->select('jenis_barang.*')
        ->join('barang', 'barang.id_jenis_barang = jenis_barang.id');
    }
}