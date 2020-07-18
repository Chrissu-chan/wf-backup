<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_features_m extends BaseModel {

    protected $table = 'module_features';
    protected $primary_key = 'id';
    protected $fillable = array('module_id','feature','class');
}