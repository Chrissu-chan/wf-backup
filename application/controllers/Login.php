<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('cabang_m');
        $this->load->view('login');
    }

    public function authenticate() {
        $this->load->model('user_cabang_m');
        $post = $this->input->post();
        if ($user = $this->auth->attempt($post['username'], $post['password'], array('active' => 1))) {
            $user_cabang = $this->user_cabang_m->group_start()
                    ->where('user_cabang.id_cabang', $post['cabang'])
                    ->or_where('user_cabang.id_cabang', 0)
                ->group_end()
                ->where('user_cabang.id_user', $user->id)
                ->first();
            if($user_cabang) {
                $cabang = $this->cabang_m->find($post['cabang']);
                $this->session->set_userdata('cabang', $cabang);
                $this->redirect->intended('dashboard');
            } else {
                $this->auth->logout();
                $this->redirect->with('error_message', $this->localization->lang('tidak_terdaftar_di_cabang_yang_dipilih'))->back();
            }
        } else {
            $this->redirect->with('error_message', $this->localization->lang('username_or_password_is_incorrect'))->back();
        }
    }

    public function logout() {
        $this->auth->logout();
        $this->redirect->route('login');
    }
}