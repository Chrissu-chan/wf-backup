<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_m extends BaseModel {

    protected $table = 'bank';
    protected $primary_key = 'id';
    protected $fillable = array('bank','telepon');
}