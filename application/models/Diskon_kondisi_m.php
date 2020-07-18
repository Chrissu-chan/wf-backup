<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_kondisi_m extends BaseModel {

    protected $table = 'diskon_kondisi';
    protected $primary_key = 'id';
    protected $fillable = array('id_diskon', 'column', 'operation', 'from', 'to');
}