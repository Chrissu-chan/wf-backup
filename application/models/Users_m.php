<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends BaseModel {

    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = array('username','password','name', 'photo', 'device_id', 'active');
    protected $authors = true;
    protected $timestamps = true;

    public function view_user_role_permissions() {
        $this->db->select('users.*')
            ->join('user_cabang', 'user_cabang.id_user = users.id AND user_cabang.id_cabang = \''.$this->session->userdata('cabang')->id.'\'')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('role_permissions', 'role_permissions.role_id = user_roles.role_id')
            ->join('module_feature_actions', 'module_feature_actions.id = role_permissions.module_feature_action_id')
            ->join('module_features', 'module_features.id = module_feature_actions.module_feature_id')
            ->join('modules', 'modules.id = module_features.module_id')
            ->join('application_modules', 'application_modules.module_id = modules.id');

    }

    public function scope_active() {
        $this->db->where('active', 1);
    }

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