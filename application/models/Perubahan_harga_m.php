<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perubahan_harga_m extends BaseModel {

    protected $table = 'perubahan_harga';
    protected $primary_key = 'id';
    protected $fillable = array('keterangan','perubahan_harga','tanggal_mulai','tanggal_selesai','aktif');
    protected $authors = true;
    protected $timestamps = true;

    public function scope_active() {
        $this->db->where('aktif', 1)
            ->where('tanggal_mulai <=', date('Y-m-d'))
            ->group_start()
                ->where('tanggal_selesai >= ', date('Y-m-d'))
                ->or_where('tanggal_selesai', NULL)
            ->group_end();
    }

    public function set_perubahan_harga($value) {
        return $this->localization->number_value($value);
    }

    public function set_tanggal_mulai($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_tanggal_selesai($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function enum_aktif() {
        return array(
            '0' => 'Non Aktif',
            '1' => 'Aktif'
        );
    }

	public function enum_permanen() {
		return array(
			'0' => 'Periode',
			'1' => 'Permanen'
		);
	}
}