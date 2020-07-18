<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_jasa_m extends BaseModel {

    protected $table = 'kategori_jasa';
    protected $primary_key = 'id';
    protected $fillable = array('kategori_jasa', 'parent_id');
    protected $default = array(
        'parent_id' => 0
    );

    public function scope_parent() {
        $this->db->where('parent_id', 0);
    }

    public function view_kategori_jasa() {
    	$this->db->select('kategori_jasa.kategori_jasa, b.kategori_jasa as induk')
			->join('kategori_jasa b', 'b.id = kategori_jasa.parent_id', 'LEFT');
    }

}