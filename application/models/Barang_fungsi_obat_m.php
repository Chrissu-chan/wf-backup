<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_fungsi_obat_m extends BaseModel {

    protected $table = 'barang_fungsi_obat';
    protected $primary_key = 'id';
    protected $fillable = array('id_barang', 'id_fungsi_obat');

    public function view_fungsi_obat()
    {
    	return $this->db->select('fungsi_obat.fungsi')
    		->join('fungsi_obat', 'fungsi_obat.id = barang_fungsi_obat.id_fungsi_obat');
    }

}