<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules_m extends BaseModel {

    protected $table = 'modules';
    protected $primary_key = 'id';
    protected $fillable = array('module','description');
}