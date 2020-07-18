<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan_m extends BaseModel {

    protected $table = 'pelanggan';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','nama','id_jenis_identitas','no_identitas','jenis_kelamin','telepon','hp','id_kota','alamat');

    public function view_pelanggan() {
        $this->db->select('pelanggan.*, jenis_identitas.jenis_identitas, kota.kota')
        ->join('jenis_identitas', 'jenis_identitas.id = pelanggan.id_jenis_identitas')
        ->join('kota', 'kota.id = pelanggan.id_kota', 'left');
    }

    public function enum_jenis_kelamin() {
    	return array(
    		'male' => 'Laki-laki',
    		'female' => 'Perempuan'
    	);
    }
}