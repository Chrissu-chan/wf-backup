<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_identitas_m extends BaseModel {

    protected $table = 'jenis_identitas';
    protected $primary_key = 'id';
    protected $fillable = array('jenis_identitas');
}