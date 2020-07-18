<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_cabang_m extends BaseModel {

    protected $table = 'diskon_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_diskon', 'id_cabang');
}