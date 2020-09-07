<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_ignore_expired_m extends BaseModel {

    protected $table = 'barang_ignore_expired';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'id_barang', 'expired');
	protected $authors = true;
	protected $timestamps = true;
    protected $default = array();

    public function __construct() {
        $this->default = array(
            'id_gudang' => $this->cabang_gudang_m->scope('utama')
                ->where('id_cabang', $this->session->userdata('cabang')->id)
                ->first()->id_gudang
        );
    }
    
    public function view_barang_ignore_expired() {
    	$this->db->select('
    	        barang_ignore_expired.*, 
    	        gudang.gudang,
                barang.kode AS kode_barang,
                barang.nama AS nama_barang,
                satuan.satuan'
	        )
		    ->join('gudang', 'gudang.id = barang_ignore_expired.id_gudang')
		    ->join('barang', 'barang.id = barang_ignore_expired.id_barang')
		    ->join('satuan', 'satuan.id = barang.id_satuan');
    }
}