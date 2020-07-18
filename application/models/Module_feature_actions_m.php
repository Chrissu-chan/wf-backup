<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_feature_actions_m extends BaseModel {

    protected $table = 'module_feature_actions';
    protected $primary_key = 'id';
    protected $fillable = array('module_feature_id','action', 'label');

    public function view_actions() {
        $this->db->select('module_feature_actions.*, modules.id as module_id, modules.module, module_features.feature')
        ->join('module_features', 'module_features.id = module_feature_actions.module_feature_id')
        ->join('modules', 'modules.id = module_features.module_id')
        ->order_by('module_feature_id', 'asc');
    }
}