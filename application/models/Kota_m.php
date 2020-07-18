<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kota_m extends BaseModel {

    protected $table = 'kota';
    protected $primary_key = 'id';
    protected $fillable = array('kota');
}