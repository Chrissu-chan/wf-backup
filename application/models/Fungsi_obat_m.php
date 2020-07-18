<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fungsi_obat_m extends BaseModel {

    protected $table = 'fungsi_obat';
    protected $primary_key = 'id';
    protected $fillable = array('fungsi_obat');
}