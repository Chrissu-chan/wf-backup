<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perubahan_harga_cabang_m extends BaseModel {

    protected $table = 'perubahan_harga_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_perubahan_harga', 'id_cabang');
}