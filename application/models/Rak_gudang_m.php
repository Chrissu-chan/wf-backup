<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak_gudang_m extends BaseModel {

    protected $table = 'rak_gudang';
    protected $primary_key = 'id';
    protected $fillable = array('id_gudang','rak');

    public function view_rak_gudang() {
    	return $this->db->select('rak_gudang.*, gudang.gudang')
    				->join('gudang', 'gudang.id = rak_gudang.id_gudang');
    }
}