<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_obat_m extends BaseModel {

    protected $table = 'kategori_obat';
    protected $primary_key = 'id';
    protected $fillable = array('kategori_obat');
}