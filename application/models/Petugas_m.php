<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_m extends BaseModel {

    protected $table = 'petugas';
    protected $primary_key = 'id';
    protected $fillable = array('nama','id_jenis_petugas');

    public function view_petugas() {
    	$this->db->select('petugas.*, jenis_petugas.jenis_petugas, petugas_cabang.id_cabang')
    	->distinct()
    	->join('jenis_petugas', 'jenis_petugas.id = petugas.id_jenis_petugas')
		->join('petugas_cabang', 'petugas_cabang.id_petugas = petugas.id')
    	->where_in('petugas_cabang.id_cabang', $this->auth->cabang);
    }

}