<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_alergi_m extends BaseModel {

    protected $table = 'pasien_alergi';
    protected $primary_key = 'id';
    protected $fillable = array('id_pasien', 'id_alergi');

    public function view_alergi() {
    	return $this->db->select('pasien_alergi.*, alergi.penyakit alergi')
    		->join('penyakit alergi', 'alergi.id = pasien_alergi.id_alergi');
    }

}