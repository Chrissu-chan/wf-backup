<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_petugas_m extends BaseModel {

    protected $table = 'jenis_petugas';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_petugas');
}