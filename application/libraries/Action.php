<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action {

    protected $CI;

    protected $role_permissions_m = 'role_permissions_m';

    protected $role_permissions = array();

    public function __construct() {
        $this->CI = &get_instance();
        if ($this->CI->config->item('authorization')) {
            $this->CI->load->model($this->role_permissions_m);
            $this->role_permissions_m = $this->CI->{$this->role_permissions_m};
            $rs_role_permissions = $this->role_permissions_m->view('permissions')
            ->scope('auth')
            ->where('application_id', $this->CI->config->item('application_id'))
            ->get();
            foreach ($rs_role_permissions as $r_role_permission) {
                $key = str_replace(array('.php', '/'), array('', '.'), $r_role_permission->class).'.'.$r_role_permission->action;
                $key = strtolower($key);
                $this->role_permissions[$key] = $r_role_permission->permission;
            }
        }
    }

    public function link($name, $url = null, $attributes = '', $title = null) {
        if (!$this->has_permission($name)) {
            return '';
        }
        $label = $this->fetch_label($name);
        $name = $this->fetch_name($name);
        if (!$url) {
            $url = $name;
        }
        if (!$title) {
            $title = $this->CI->localization->lang($label);
        }
        return '<a href="'.$url.'" id="action-'.$name.'" '.$attributes.' title="'.$title.'">'.$title.'</a>';
    }

    public function button($name, $attributes = '', $title = null) {
        if (!$this->has_permission($name)) {
            return '';
        }
        $name = $this->fetch_name($name) ;
        if (!$title) {
            $title = $this->CI->localization->lang($name);
        }
        return '<button type="button" id="action-'.$name.'" '.$attributes.' title="'.$title.'">'.$title.'</button>';
    }

    public function submit($name, $attributes = '', $title = null) {
        if (!$this->has_permission($name)) {
            return '';
        }
        $name = $this->fetch_name($name) ;
        if (!$title) {
            $title = $this->CI->localization->lang($name);
        }
        return '<button type="submit" id="action-'.$name.'" '.$attributes.' title="'.$title.'">'.$title.'</button>';
    }

    public function render($template, $actions = array()) {
        $html = $template;
        foreach ($actions as $name => $action) {
            if (is_callable($action)) {
                $action = $action();
            }
            $html = str_replace('{'.$name.'}', $action, $html);
        }
        return $html;
    }

    protected function fetch_label($name) {
        $parse = explode('.', $name);
        return end($parse);
    }

    protected function fetch_name($name) {
        $parse = explode('.', $name);
        if (count($parse) == 4) {
            return $parse[2];
        } elseif (count($parse) == 3) {
            return $parse[1];
        } elseif (count($parse) == 2) {
            return $parse[0];
        } else {
            return $name;
        }
    }

    protected function fetch_feature($name) {
        $parse = explode('.', $name);
        if (count($parse) == 4) {
            return $parse[1];
        } elseif (count($parse) == 3) {
            return $parse[0];
        } else {
            return $this->CI->router->fetch_class();
        }
    }

    protected function fetch_module($name) {
        $parse = explode('.', $name);
        if (count($parse) == 4) {
            return $parse[0];
        } else {
            return trim($this->CI->router->directory, '/');
        }
    }

    protected function has_permission($name) {
        $module = $this->fetch_module($name);
        $feature = $this->fetch_feature($name);
        $name = $this->fetch_name($name);
        if (ENVIRONMENT == 'development' && strtolower($module) == 'developers') {
            return true;
        }
        if ($this->CI->config->item('authorization')) {
            if (isset($this->role_permissions[$module.'.'.$feature.'.'.$name])) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}