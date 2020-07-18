<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_modules_m extends BaseModel {

    protected $table = 'application_modules';
    protected $primary_key = 'id';
    protected $fillable = array('application_id','module_id');

    public function view_modules() {
        $this->db->select('application_modules.*, modules.module, modules.description as module_description')
        ->join('modules', 'modules.id = application_modules.module_id');
    }

    public function view_application_actions() {
        $this->db->select('
            application_modules.*, 
            applications.application, 
            modules.module, 
            module_features.id AS module_feature_id, 
            module_features.feature, 
            module_feature_actions.id AS module_feature_action_id, 
            module_feature_actions.action,
            module_feature_actions.label')
        ->join('applications', 'applications.id = application_modules.application_id')
        ->join('modules', 'modules.id = application_modules.module_id')
        ->join('module_features', 'module_features.module_id = modules.id')
        ->join('module_feature_actions', 'module_feature_actions.module_feature_id = module_features.id');
    }
}