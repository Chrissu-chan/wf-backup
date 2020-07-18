<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas_bank_cabang_m extends BaseModel {

    protected $table = 'kas_bank_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_kas_bank', 'id_cabang', 'utama');

    public function view_kas_bank() {
	    $this->db->select('kas_bank.*')
		    ->join('kas_bank', 'kas_bank.id = kas_bank_cabang.id_kas_bank')
		    ->group_by('kas_bank.id');
    }

	public function scope_cabang_aktif() {
		$this->db->group_start()
			->where('id_cabang', $this->session->userdata('cabang')->id)
			->or_where('id_cabang', 0)
			->group_end();
	}

	public function scope_utama() {
		$this->db->where('utama', 1);
	}
}