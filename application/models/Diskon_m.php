<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_m extends BaseModel {

    protected $table = 'diskon';
    protected $primary_key = 'id';
    protected $fillable = array('diskon','potongan','tanggal_mulai','tanggal_selesai','keterangan','aktif');
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

    public function set_diskon($value) {
        return $this->localization->number_value($value);
    }

    public function set_potongan($value) {
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
}