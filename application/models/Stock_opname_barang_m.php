<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname_barang_m extends BaseModel {

    protected $table = 'stock_opname_barang';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang', 'id_barang');
	protected $authors = true;
	protected $timestamps = true;
}