<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan_m extends BaseModel {

    protected $table = 'satuan';
    protected $primary_key = 'id';
    protected $fillable = array('satuan', 'grup', 'keterangan');

	public function view_satuan() {
		$this->db->select(array('satuan.*', 'konversi_satuan.konversi'))
			->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = satuan.id')
			->order_by('konversi', 'ASC');
	}

	public function view_satuan_old() {
        $this->db->select(array('satuan.*', 'konversi_satuan.konversi'))
            ->join('konversi_satuan', 'konversi_satuan.id_satuan_tujuan = satuan.id')
            ->order_by('konversi', 'ASC');
    }

	public function set_grup($value) {
		return strtoupper(trim($value));
	}
}