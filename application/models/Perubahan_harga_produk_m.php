<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perubahan_harga_produk_m extends BaseModel {

    protected $table = 'perubahan_harga_produk';
    protected $primary_key = 'id';
    protected $fillable = array('id_perubahan_harga','id_produk');
}