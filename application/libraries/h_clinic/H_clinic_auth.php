<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/Auth.php';

class H_clinic_auth extends Auth {

    protected $cabang_m = 'cabang_m';
    protected $user_cabang_m = 'user_cabang_m';

    public function __construct() {
        parent::__construct();
        $this->CI->load->model($this->cabang_m);
        $this->cabang_m = $this->CI->{$this->cabang_m};
        $this->CI->load->model($this->user_cabang_m);
        $this->user_cabang_m = $this->CI->{$this->user_cabang_m};
    }

    public function attempt_api($device_id, $attributes = NULL) {
        $user = parent::attempt_api($device_id, $attributes);
        if ($user) {
            $user->cabang = $this->get_cabang($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }

    public function attempt($username, $password, $attributes = NULL) {
        $user = parent::attempt($username, $password, $attributes);
        if ($user) {
            $user->cabang = $this->get_cabang($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }

    public function get_cabang($id) {
        $cabang = array();
        $user_cabang = $this->user_cabang_m->where('id_user', $id)
        ->where('id_cabang', 0)
        ->first();
        if ($user_cabang) {
            $rs_cabang = $this->cabang_m->get();
        } else {
            $rs_cabang = $this->user_cabang_m->view('cabang')
            ->where('user_cabang.id_user', $id)
            ->get();
        }
        foreach ($rs_cabang as $r_cabang) {
            $cabang[] = $r_cabang->id;
        }
        return $cabang;
    }

    public function reload() {
        $user = parent::reload();
        if ($user) {
            $user->cabang = $this->get_cabang($user->id);
            $this->CI->session->set_userdata('auth', $user);
            $this->user = $this->CI->session->userdata('auth');
            return $this->user();
        } else {
            return false;
        }
    }
}