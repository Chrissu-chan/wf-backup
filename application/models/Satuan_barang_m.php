<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan_barang_m extends BaseModel {

    protected $table = 'satuan_barang';
    protected $primary_key = 'id';
    protected $fillable = array('kode', 'satuan_barang','pengali','parent_id');

    public function view_satuan() {
    	$this->db->select('satuan_barang.*')
        ->join('barang', 'barang.id_satuan_barang = satuan_barang.id');
    }

    public function scope_parent() {
    	$this->db->where('parent_id', 0);
    }

}