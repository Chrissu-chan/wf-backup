<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_m extends BaseModel {

    protected $table = 'member';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'kode','id_jenis_member', 'id_pelanggan','tanggal_daftar', 'tanggal_expired');
    protected $authors = true;
    protected $timestamps = true;

    public function view_member() {
        $this->db->select('member.*, jenis_member.jenis_member')
        ->join('jenis_member', 'jenis_member.id = member.id_jenis_member');
    }

    public function view_pelanggan() {
        $this->db->select('member.*, pelanggan.nama, pelanggan.id_jenis_identitas, pelanggan.no_identitas, pelanggan.jenis_kelamin, pelanggan.telepon, pelanggan.hp, pelanggan.id_kota, pelanggan.alamat, jenis_identitas.jenis_identitas, kota.kota, jenis_member.jenis_member')
        ->join('pelanggan', 'pelanggan.id = member.id_pelanggan')
        ->join('jenis_identitas', 'jenis_identitas.id = pelanggan.id_jenis_identitas', 'left')
        ->join('kota', 'kota.id = pelanggan.id_kota', 'left')
        ->join('jenis_member', 'jenis_member.id = member.id_jenis_member');
    }

    public function set_tanggal_daftar($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_expired($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function enum_status() {
        return array(
            'expired' => 'Expired',
            'active' => 'Aktif'
        );
    }

    public function enum_jenis_kelamin() {
        return array(
            'male' => 'Laki-laki',
            'female' => 'Perempuan'
        );
    }

}