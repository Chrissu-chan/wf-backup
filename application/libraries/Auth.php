<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    protected $CI;

    protected $username_field = 'username';

    protected $user;

    protected $model = 'users_m';

    protected $user_roles_m = 'user_roles_m';

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model($this->model);
        $this->CI->load->model($this->user_roles_m);
        $this->model = $this->CI->{$this->model};
        $this->user_roles_m = $this->CI->{$this->user_roles_m};
        $this->user = $this->CI->session->userdata('auth');
    }

    public function __get($name) {
        if (!isset($this->{$name})) {
            if ($this->user) {
                return $this->user->{$name};
            } else {
                return null;
            }
        } else {
            return $this->{$name};
        }
    }

    public function attempt_api($device_id, $attributes = null) {
        if ($attributes) {
            $this->model->where($attributes);
        }
        $user = $this->model->where('device_id', $device_id)->first();
        if ($user) {
            $user->roles = $this->get_roles($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }

    public function attempt($username, $password, $attributes = null) {
        if ($attributes) {
            $this->model->where($attributes);
        }
        $user = $this->model->where($this->username_field, $username)
        ->where('password', $this->model->set_password($password))
        ->first();
        if ($user) {
            $user->roles = $this->get_roles($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }

    public function user() {
        return $this->user;
    }

    public function reload() {
        $user = $this->model->where($this->username_field, $this->user->username)
        ->first();
        if ($user) {
            $user->roles = $this->get_roles($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }

    public function get_roles($id) {
        $rs_user_roles = $this->user_roles_m->where('user_id', $id)
        ->get();
        $roles = array();
        foreach ($rs_user_roles as $r_user_role) {
            $roles[] = $r_user_role->role_id;
        }
        return $roles;
    }

    public function authenticated() {
        if ($this->user()) {
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $this->user = null;
        $this->CI->session->unset_userdata('auth');
        return true;
    }
}