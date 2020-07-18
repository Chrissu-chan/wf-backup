<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_m extends BaseModel {

    protected $table = 'pasien';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'nama','id_jenis_identitas','no_identitas','jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'pendidikan', 'telepon','hp','id_kota','alamat', 'status_pernikahan', 'pekerjaan');

    public function view_pasien() {
        $this->db->select('pasien.*, jenis_identitas.jenis_identitas, kota.kota, kota_lahir.kota as tempat_lahir')
        ->join('jenis_identitas', 'jenis_identitas.id = pasien.id_jenis_identitas')
        ->join('kota', 'kota.id = pasien.id_kota', 'left')
        ->join('kota kota_lahir', 'kota_lahir.id = pasien.tempat_lahir', 'left');
    }

    public function set_tanggal_lahir($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function enum_jenis_kelamin() {
    	return array(
    		'male' => 'Laki-laki',
    		'female' => 'Perempuan'
    	);
    }

    public function enum_agama() {
        return array(
            'islam' => 'Islam',
            'kristen' => 'Kristen',
            'hindu' => 'Hindu',
            'budha' => 'Budha',
            'konghucu' => 'Konghucu',
        );
    }

    public function enum_pendidikan() {
        return array(
            'sd' => 'SD/Sederajat',
            'smp' => 'SMP/Sederajat',
            'sma' => 'SMA/Sederajat',
            's1' => 'S1/Sederajat',
            's2' => 'S2/Sederajat',
            's3' => 'S3/Sederajat'
        );
    }

    public function enum_status_pernikahan() {
        return array(
            'menikah' => 'Menikah',
            'belum_menikah' => 'Belum Menikah'
        );
    }

}