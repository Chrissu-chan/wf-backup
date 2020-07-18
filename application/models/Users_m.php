<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends BaseModel {

    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = array('username','password','name', 'photo', 'device_id', 'active');
    protected $authors = true;
    protected $timestamps = true;

    public function enum_active() {
        return array(
            0 => 'Inactive',
            1 => 'Active'
        );
    }

    public function set_password($value) {
        return md5($value);
    }
}