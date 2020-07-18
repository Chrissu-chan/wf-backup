<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_supplier_m extends BaseModel {

    protected $table = 'kategori_supplier';
    protected $primary_key = 'id';
    protected $fillable = array('kategori_supplier','parent_id');
}