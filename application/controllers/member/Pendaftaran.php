<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->model('jenis_member_m');
        $this->load->model('jenis_identitas_m');
        $this->load->model('kas_bank_m');
        $this->load->model('pelanggan_m');
        $this->load->model('pendaftaran_member_m');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('member/pendaftaran/index');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode' => 'required|is_unique[member.kode]',
            'id_pelanggan' => 'is_unique[member.id_pelanggan]'
        ));
        $post['id_pelanggan'] = (!$post['id_pelanggan']) ? $this->pelanggan_m->insert($post)->id : $post['id_pelanggan'];
        $result = $this->member_m->insert($post); 

        if ($result) {
            $post['id_cabang'] = $this->session->userdata('cabang')->id;
            $post['id_member'] = $result->id;
            $this->pendaftaran_member_m->insert($post);  
            $this->invoice($post);
            /*$this->redirect->with('success_message', $this->localization->lang('success_store_pendaftaran'))->back();*/
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_pendaftaran'))->back();
        }
    }

    public function invoice($post) {
        $r_member = $this->member_m->view('pelanggan')
        ->where('id_pelanggan', $post['id_pelanggan'])
        ->first();
        $this->load->view('member/pendaftaran/medium_nota', array(
            'r_member' => $r_member,
            'post' => $post
        ));

    }

}