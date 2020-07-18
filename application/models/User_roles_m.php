<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_roles_m extends BaseModel {

    protected $table = 'user_roles';
    protected $primary_key = 'id';
    protected $fillable = array('user_id', 'role_id');

    public function view_roles() {
        $this->db->select('roles.*')
        ->join('roles', 'roles.id = user_roles.role_id');
    }
}