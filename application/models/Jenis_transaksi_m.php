<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_transaksi_m extends BaseModel {

    protected $table = 'jenis_transaksi';
    protected $primary_key = 'id';
    protected $fillable = array('kode_jenis_transaksi','jenis_transaksi','tipe');

    public function enum_tipe() {
    	return array(
    		'pemasukan' => 'Pemasukan',
    		'pengeluaran' => 'Pengeluaran'
    	);
    }

    public function scope_pemasukan() {
    	return $this->db->where('tipe', 'pemasukan');
    }

    public function scope_pengeluaran() {
    	return $this->db->where('tipe', 'pengeluaran');
    }
}