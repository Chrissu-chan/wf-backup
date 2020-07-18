<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas_bank_m extends BaseModel {

    protected $table = 'kas_bank';
    protected $primary_key = 'id';
    protected $fillable = array('nama','jenis_kas_bank','id_bank','nomor_rekening','nama_rekening');

    public function view_kas_bank() {
        $this->db->select('kas_bank.*, GROUP_CONCAT(cabang.nama SEPARATOR \', \') as cabang, bank.bank')
        ->join('kas_bank_cabang', 'kas_bank_cabang.id_kas_bank = kas_bank.id')
        ->join('cabang', 'cabang.id = kas_bank_cabang.id_cabang')
        ->join('bank', 'bank.id = kas_bank.id_bank', 'left')
        ->group_by('kas_bank.id');
    }

    public function scope_bank() {
        $this->db->where('jenis_kas_bank', 'bank');
    }

    public function enum_kas_bank() {
    	return array(
    		'kas' => 'Kas',
    		'bank' => 'Bank'
    	);
    }
}