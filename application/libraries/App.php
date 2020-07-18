<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App {

    protected $CI;

    protected $applications_m = 'applications_m';

    public function __construct() {
        $this->CI = get_instance();
        $this->CI->load->model($this->applications_m);
        $parse_applications_m = explode('/', $this->applications_m);
        $this->applications_m = end($parse_applications_m);
        $this->applications_m = $this->CI->{$this->applications_m};
        $module = trim($this->CI->router->directory, '/');
        $class = trim($this->CI->router->fetch_class(), '/');
        $method = trim($this->CI->router->fetch_method(), '/');
        $class = implode('/', array($module, $class)) . '.php';
        $class = trim($class, '/');
        $this->activity = $this->applications_m->view('methods')
        ->where('LOWER(module_features.class)', strtolower($class))
        ->where('LOWER(module_feature_action_methods.method)', strtolower($method))
        ->first();
    }

    public function set_activity($feature_id, $action_id) {
        $activity = new stdClass();
        $activity->id = null;
        $activity->application = null;
        $activity->module_id = null;
        $activity->module = trim($this->CI->router->directory, '/');
        $activity->feature_id = $feature_id;
        $activity->feature = null;
        $activity->class = trim($this->CI->router->fetch_class(), '/');
        $activity->action_id = $action_id;
        $activity->action = null;
        $activity->method_id = null;
        $activity->method = trim($this->CI->router->fetch_method(), '/');
    }

    public function fetch_activity() {
        $activity = new stdClass();
        $activity->id = null;
        $activity->application = null;
        $activity->module_id = null;
        $activity->module = trim($this->CI->router->directory, '/');
        $activity->feature_id = null;
        $activity->feature = null;
        $activity->class = trim($this->CI->router->fetch_class(), '/');
        $activity->action_id = null;
        $activity->action = null;
        $activity->method_id = null;
        $activity->method = trim($this->CI->router->fetch_method(), '/');
        if ($this->activity) {
            $activity->id = $this->activity->id;
            $activity->application = $this->activity->application;
            $activity->module_id = $this->activity->module_id;
            $activity->module = $this->activity->module;
            $activity->feature_id = $this->activity->module_feature_id;
            $activity->feature = $this->activity->feature;
            $parse_class = explode('/', $this->activity->class);
            $activity->class = end($parse_class);
            $activity->action_id = $this->activity->module_feature_action_id;
            $activity->action = $this->activity->action;
            $activity->method_id = $this->activity->module_feature_action_method_id;
            $activity->method = $this->activity->method;
        }
        return $activity;
    }
}