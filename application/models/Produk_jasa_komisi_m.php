<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_jasa_komisi_m extends BaseModel {

    protected $table = 'produk_jasa_komisi';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','id_produk','id_petugas','komisi');

    public function view_jasa_komisi() {
        $this->db->select("produk_jasa_komisi.*, CASE WHEN (petugas.nama IS NOT NULL) THEN petugas.nama ELSE 'Semua Petugas' END AS petugas")
            ->join('petugas', 'petugas.id = produk_jasa_komisi.id_petugas', 'left');
    }

    public function set_komisi($value) {
        return $this->localization->number_value($value);
    }
}