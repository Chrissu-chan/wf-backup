<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_penyakit_m extends BaseModel {

    protected $table = 'pasien_penyakit';
    protected $primary_key = 'id';
    protected $fillable = array('id_pasien', 'id_penyakit');

    public function view_penyakit() {
    	return $this->db->select('pasien_penyakit.*, penyakit.penyakit')
    		->join('penyakit', 'penyakit.id = pasien_penyakit.id_penyakit');
    }

}