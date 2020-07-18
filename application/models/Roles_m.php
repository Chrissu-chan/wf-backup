<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_m extends BaseModel {

    protected $table = 'roles';
    protected $primary_key = 'id';
    protected $fillable = array('role','description');
}