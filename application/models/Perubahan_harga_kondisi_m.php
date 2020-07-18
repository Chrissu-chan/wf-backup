<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perubahan_harga_kondisi_m extends BaseModel {

    protected $table = 'perubahan_harga_kondisi';
    protected $primary_key = 'id';
    protected $fillable = array('id_perubahan_harga', 'column', 'operation', 'from', 'to');
}