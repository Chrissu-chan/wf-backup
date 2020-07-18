<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_produksi_m extends Barang_m {

    protected $table = 'barang_produksi';
    protected $primary_key = 'id';
    protected $fillable = array('id_barang', 'nama', 'id_satuan');
	protected $default = array(
		'id_rak_gudang' => 0
	);

    public function view_barang_produksi() {
        $this->db->select('barang_produksi.*, barang.kode as kode_barang, barang.nama as nama_barang, barang.id_satuan as barang_id_satuan, satuan.satuan')
            ->join('barang', 'barang.id = barang_produksi.id_barang')
            ->join('satuan', 'satuan.id = barang_produksi.id_satuan');
    }

}