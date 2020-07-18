<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_member_m extends BaseModel {

    protected $table = 'jenis_member';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_member');

    public function view_jenis_member() {
    	$this->db->select('jenis_member.*, jenis_member_pendaftaran.biaya, jenis_member_pendaftaran.ppn, jenis_member_pendaftaran.ppn_persen, jenis_member_pendaftaran.total, jenis_member_pendaftaran.masa_aktif')
    		->join('jenis_member_pendaftaran', 'jenis_member_pendaftaran.id_jenis_member = jenis_member.id', 'left');
    }
}