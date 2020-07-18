<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_obat_m extends BaseModel {

    protected $table = 'jenis_obat';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_obat');
}