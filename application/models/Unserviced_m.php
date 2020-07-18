<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unserviced_m extends BaseModel {

    protected $table = 'unserviced';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'id_shift_aktif', 'id_pelanggan', 'no_servis', 'tanggal',  'batal', 'jenis_batal', 'alasan_batal', 'deleted_by', 'deleted_at');
    protected $authors = true;
    protected $timestamps = true;

    public function __construct() {
        $this->default = array(
            'id_cabang' => $this->session->userdata('cabang')->id,
	        'batal' => 0
        );
    }

    public function scope_cabang() {
        $this->db->where('id_cabang', $this->session->userdata('cabang')->id);
    }

	public function enum_jenis_batal() {
		return array(
			'cancel' => 'Cancel',
			'retur' => 'Retur'
		);
	}

    public function set_tanggal($value) {
        return date('Y-m-d', strtotime($value));
    }
}