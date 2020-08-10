<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname_m extends BaseModel {

    protected $table = 'stock_opname';
    protected $primary_key = 'id';
    protected $fillable = array('id_gudang', 'waktu_mulai', 'waktu_selesai', 'opened_by', 'closed_by', 'total_barang', 'total_barang_so', 'flag_jurnal', 'proses_jurnal', 'batal');
	protected $default = array(
		'flag_jurnal' => 'true',
		'proses_jurnal' => 'false',
		'batal' => 0
	);

	public function view_stock_opname() {
		$this->db->select(array(
				'stock_opname.*',
				'gudang.gudang'
			))
			->join('gudang', 'gudang.id = stock_opname.id_gudang');
	}

    public function scope_active() {
        $this->db->where('waktu_selesai', NULL);
    }

	public function enum_status() {
		return array(
			'all' => 'Semua',
			'waiting' => 'Belum SO',
			'done' => 'Sudah SO'
		);
	}
}