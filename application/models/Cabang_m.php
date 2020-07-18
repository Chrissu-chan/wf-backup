<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang_m extends BaseModel {

    protected $table = 'cabang';
    protected $primary_key = 'id';
    protected $fillable = array('nama', 'npwp','telepon','alamat', 'id_kota', 'keterangan', 'parent_id');

    public function scope_parent() {
    	$this->db->where('parent_id', 0);
    }

    public function scope_auth() {
        $this->db->where_in('id', $this->auth->cabang);
    }
}