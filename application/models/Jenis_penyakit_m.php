<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_penyakit_m extends BaseModel {

    protected $table = 'jenis_penyakit';
    protected $primary_key = 'id';
    protected $fillable = array('kode_jenis_penyakit', 'jenis_penyakit');
}