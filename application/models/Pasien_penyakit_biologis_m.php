<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_penyakit_biologis_m extends BaseModel {

    protected $table = 'pasien_penyakit_biologis';
    protected $primary_key = 'id';
    protected $fillable = array('id_pasien', 'id_penyakit_biologis');

    public function view_penyakit_biologis() {
    	return $this->db->select('pasien_penyakit_biologis.*, penyakit_biologis.penyakit penyakit_biologis')
    		->join('penyakit penyakit_biologis', 'penyakit_biologis.id = pasien_penyakit_biologis.id_penyakit_biologis');
    }

}