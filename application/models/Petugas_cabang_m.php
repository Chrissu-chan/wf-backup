<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_cabang_m extends BaseModel {

    protected $table = 'petugas_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_petugas','id_cabang');

    public function view_petugas() {
    	$this->load->model('user_cabang_m');
    	$this->db->select('petugas.*')
    	->join('petugas', 'petugas.id = petugas_cabang.id_petugas')
    	->where_in('id_cabang', $this->user_cabang_m->select('id_cabang')->scope('auth')->get());
    }

}