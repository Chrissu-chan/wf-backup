<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aktif_cabang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_cabang_m');
    }

    public function set($id) {
        $user_cabang = $this->user_cabang_m->view('cabang')
            ->where('user_cabang.id_cabang', $id)
            ->where('user_cabang.id_user', $this->session->auth->id)->first();
        $this->session->set_userdata('cabang', $user_cabang);
        $this->redirect->back();
    }
}