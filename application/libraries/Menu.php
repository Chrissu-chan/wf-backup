<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu {

    protected $CI;

    protected $model = 'application_menus_m';

    protected $role_permissions_m = 'role_permissions_m';

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model($this->model);
        $this->model = $this->CI->{$this->model};
        if ($this->CI->config->item('authorization')) {
            $this->CI->load->model($this->role_permissions_m);
            $this->role_permissions_m = $this->CI->{$this->role_permissions_m};
        }
    }

    public function load($application_id, $template, $menu_id = 0) {
        if ($this->CI->config->item('authorization')) {
            $rs_role_permissions = $this->role_permissions_m->scope('auth')
            ->where('application_id', $application_id)
            ->get();
            $role_permissions = array();
            foreach ($rs_role_permissions as $r_role_permission) {
                $role_permissions[$r_role_permission->module_feature_action_id] = $r_role_permission->permission;
            }
        }

        $rs_menus = $this->model->view('number_of_child')
        ->where('application_id', $application_id)
        ->order_by('sequence', 'asc')
        ->get();
        $menus = array();
        foreach ($rs_menus as $r_menu) {
            if ($r_menu->module_feature_action_id) {
                if (isset($role_permissions[$r_menu->module_feature_action_id]) || !$this->CI->config->item('authorization')) {
                    $menus[] = $r_menu;
                }
            } else {
                $menus[] = $r_menu;
            }
        }
        $menus = tree($menus, 'id', 'parent_id', $menu_id, true);
        $this->CI->load->view($template, array(
            'menus' => $menus
        ));
    }

    public function set_menu($data, $parent_data, $id, $parent, $level = 0) {
        $result = array();
        foreach ($parent_data as $key => $row) {
            $row->tree_level = $level;
            $row->childs = array();
            if (isset($data[$row->$id])) {
                $row->childs = $this->set_menu($data, $data[$row->$id], $id, $parent_data, $level + 1);
                unset($data[$row->$id]);
            }
            $result[] = $row;
        }
        return $result;
    }
}
