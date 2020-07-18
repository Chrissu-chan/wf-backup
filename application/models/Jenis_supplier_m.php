<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_supplier_m extends BaseModel {

    protected $table = 'jenis_supplier';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_supplier');
}