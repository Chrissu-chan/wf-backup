<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyakit_m extends BaseModel {

    protected $table = 'penyakit';
    protected $primary_key = 'id';
    protected $fillable = array('id_jenis_penyakit', 'kode_penyakit', 'penyakit', 'keterangan');

    public function view_jenis_penyakit() {
    	return $this->db->select('penyakit.*, jenis_penyakit.jenis_penyakit')
    		->join('jenis_penyakit', 'jenis_penyakit.id = penyakit.id_jenis_penyakit');
    }
}