<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_aktif_m extends BaseModel {

    protected $table = 'shift_aktif';
    protected $fillable = array('id_cabang', 'id_shift', 'id_shift_waktu', 'active');
    protected $authors = true;
    protected $timestamps = true;

    public function view_shift_aktif() {
        $this->db->select('shift_aktif.*, shift_waktu.shift_waktu, shift_aktif_kasir.id AS id_shift_aktif_kasir, shift_aktif_kasir.uang_awal, shift_aktif_kasir.uang_akhir, users.username, users.password, users.name')
            ->join('shift_waktu', 'shift_waktu.id = shift_aktif.id_shift_waktu')
            ->join('shift_aktif_kasir', 'shift_aktif_kasir.id_shift_aktif = shift_aktif.id')
            ->join('users', 'users.id = shift_aktif_kasir.id_user');
    }

	public function scope_cabang() {
		$this->db->where('shift_aktif.id_cabang', $this->session->userdata('cabang')->id);
	}

    public function scope_aktif() {
        $this->db->where('shift_aktif.active', 1);
    }
}