<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization_middleware {

    public function handle($handle) {
        $handle->load->model('role_permissions_m');
        $module = trim($handle->router->directory, '/');
        $class = trim($handle->router->fetch_class(), '/');
        $method = trim($handle->router->fetch_method(), '/');
        $class = implode('/', array($module, $class)).'.php';
        $role_permission = $handle->role_permissions_m->scope('auth')
        ->view('permissions')
        ->where('LOWER(module_features.class)', strtolower($class))
        ->where('LOWER(module_feature_action_methods.method)', strtolower($method))
        ->where('role_permissions.application_id', $handle->config->item('application_id'))
        ->first();
        if ($role_permission) {
            if ($role_permission->permission == 0) {
                $handle->exceptions->failed_authorization($handle);
            }
        } else {
            $handle->exceptions->failed_authorization($handle);
        }
    }
}