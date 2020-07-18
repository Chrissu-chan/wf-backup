<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_feature_action_methods_m extends BaseModel {

    protected $table = 'module_feature_action_methods';
    protected $primary_key = 'id';
    protected $fillable = array('module_feature_action_id','method');

    public function set_method($value) {
        return strtolower(trim($value));
    }
}