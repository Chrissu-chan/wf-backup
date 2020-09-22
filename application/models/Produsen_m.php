<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produsen_m extends BaseModel {

    protected $table = 'produsen';
    protected $primary_key = 'id';
    protected $fillable = array('nama_produsen');
}