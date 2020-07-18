<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applications_m extends BaseModel {

    protected $table = 'applications';
    protected $primary_key = 'id';
    protected $fillable = array('application','description');

	public function view_features() {
		$this->db->select(array(
				'applications.*',
				'application_modules.module_id',
				'modules.module',
				'module_features.id AS module_feature_id',
				'module_features.feature',
				'module_features.class'
			))
			->join('application_modules', 'application_modules.application_id = applications.id')
			->join('modules', 'modules.id = application_modules.module_id')
			->join('module_features', 'module_features.module_id = modules.id');
	}

	public function view_actions() {
		$this->db->select(array(
				'applications.*',
				'application_modules.module_id',
				'modules.module',
				'module_features.id AS module_feature_id',
				'module_features.feature',
				'module_features.class',
				'module_feature_actions.id AS module_feature_action_id',
				'module_feature_actions.action',
				'module_feature_actions.label'
			))
			->join('application_modules', 'application_modules.application_id = applications.id')
			->join('modules', 'modules.id = application_modules.module_id')
			->join('module_features', 'module_features.module_id = modules.id')
			->join('module_feature_actions', 'module_feature_actions.module_feature_id = module_features.id')
			->order_by('applications.id', 'ASC')
			->order_by('application_modules.module_id', 'ASC')
			->order_by('module_feature_actions.id', 'ASC');
	}

	public function view_methods() {
		$this->db->select(array(
				'applications.*',
				'application_modules.module_id',
				'modules.module',
				'module_features.id AS module_feature_id',
				'module_features.feature',
				'module_features.class',
				'module_feature_actions.id AS module_feature_action_id',
				'module_feature_actions.action',
				'module_feature_actions.label',
				'module_feature_action_methods.id as module_feature_action_method_id',
				'module_feature_action_methods.method'
			))
			->join('application_modules', 'application_modules.application_id = applications.id')
			->join('modules', 'modules.id = application_modules.module_id')
			->join('module_features', 'module_features.module_id = modules.id')
			->join('module_feature_actions', 'module_feature_actions.module_feature_id = module_features.id')
			->join('module_feature_action_methods', 'module_feature_action_methods.module_feature_action_id = module_feature_actions.id', 'left');
	}

}