<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_hpp_m extends BaseModel {

    protected $table = 'view_hpp';
    protected $primary_key = 'id_barang';

    public function view_hpp() {
        $this->db->select('view_hpp.id_gudang, view_hpp.id_barang, view_hpp.id_satuan, AVG(view_hpp.harga_min) AS harga_min, AVG(view_hpp.harga_max) AS harga_max, AVG(view_hpp.hpp) AS hpp, SUM(view_hpp.jumlah) AS jumlah, SUM(view_hpp.total) AS total');
    }

}