<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_permissions_m extends BaseModel {

    protected $table = 'role_permissions';
    protected $primary_key = 'id';
    protected $fillable = array('role_id', 'application_id', 'module_feature_action_id', 'permission');

    public function view_permissions() {
        $this->db->select('role_permissions.*, applications.application, modules.id as module_id, modules.module, module_features.feature, module_features.class, module_feature_actions.action, module_feature_actions.label, module_feature_action_methods.method')
        ->join('applications', 'applications.id = role_permissions.application_id')
        ->join('module_feature_actions', 'module_feature_actions.id = role_permissions.module_feature_action_id')
        ->join('module_feature_action_methods', 'module_feature_action_methods.module_feature_action_id = module_feature_actions.id')
        ->join('module_features', 'module_features.id = module_feature_actions.module_feature_id')
        ->join('modules', 'modules.id = module_features.module_id');
    }

    public function scope_auth() {
        if ($this->auth->roles) {
            $this->db->where_in('role_permissions.role_id', $this->auth->roles);
        } else {
            $this->db->where('role_permissions.role_id', null);
        }
    }
}