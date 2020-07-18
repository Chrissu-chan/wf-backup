<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_menus_m extends BaseModel {

    protected $table = 'application_menus';
    protected $primary_key = 'id';
    protected $fillable = array('application_id','menu','description','module_feature_action_id','url','icon','attributes', 'sequence','parent_id');

    public function view_menus() {
        $this->db->select('application_menus.*, modules.module, module_features.feature, module_features.class, module_feature_actions.action, module_feature_actions.label')
        ->join('module_feature_actions', 'module_feature_actions.id = application_menus.module_feature_action_id', 'left')
        ->join('module_features', 'module_features.id = module_feature_actions.module_feature_id', 'left')
        ->join('modules', 'modules.id = module_features.module_id', 'left');
    }

    public function view_number_of_child() {
        $this->db->select('application_menus.*, (SELECT count(1) AS number_of_child FROM application_menus child_menus WHERE child_menus.parent_id = application_menus.id) as number_of_child');
    }

    public function sequence($parent_id) {
        $sequence = 1;
        $max = $this->db->select_max('sequence')
        ->where('parent_id', $parent_id)
        ->get('application_menus')
        ->row();
        if ($max) {
            $sequence = $max->sequence+1;
        }
        return $sequence;
    }
}