<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unserviced_detail_m extends BaseModel {

    protected $table = 'unserviced_detail';
    protected $primary_key = 'id';
    protected $fillable = array('id_servis', 'id_barang', 'nama_barang', 'id_satuan', 'satuan', 'jumlah');

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function scope_cabang() {
        $this->db->where('pembelian.id_cabang', $this->session->userdata('cabang')->id);
    }
}