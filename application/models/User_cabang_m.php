<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_cabang_m extends BaseModel {

    protected $table = 'user_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_user', 'id_cabang');

    public function view_cabang() {
        $this->db->select('cabang.*')
            ->join('cabang', 'cabang.id = user_cabang.id_cabang');
    }

    public function view_users() {
        $this->db->select('users.*')
            ->join('users', 'users.id = user_cabang.id_user');
    }

    public function scope_cabang_aktif() {
        $this->db->where('id_cabang', $this->session->userdata('cabang')->id)
            ->or_where('id_cabang', 0);
    }
}