<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_member_aktif_cabang_m extends BaseModel {

    protected $table = 'jenis_member_aktif_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_jenis_member','id_cabang');
}