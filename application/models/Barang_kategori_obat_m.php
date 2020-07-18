<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_kategori_obat_m extends BaseModel {

    protected $table = 'barang_kategori_obat';
    protected $primary_key = 'id';
    protected $fillable = array('id_barang', 'id_kategori_obat');

    public function view_kategori_obat()
    {
    	return $this->db->select('kategori_obat.kategori_obat')
    		->join('kategori_obat', 'kategori_obat.id = barang_kategori_obat.id_kategori_obat');
    }

}