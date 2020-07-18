<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perpanjangan extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->model('kas_bank_m');
        $this->load->model('jenis_member_m');
        $this->load->model('jenis_identitas_m');
        $this->load->model('perpanjangan_masa_aktif_member_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->member_m)
            ->view('member')->view('pelanggan')
            ->edit_column('jenis_kelamin', function($model) {
                return $this->member_m->enum('jenis_kelamin', $model->jenis_kelamin);
            })
            ->add_action('{view}')
            ->generate();
        }
        $this->load->view('member/perpanjangan/index');
    }
    
    public function view($id) {
        $model = $this->member_m->view('pelanggan')->find_or_fail($id);
        $this->load->view('member/perpanjangan/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('member/perpanjangan/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_member' => 'required'
        ));
        $result = $this->perpanjangan_masa_aktif_member_m->insert($post); 
        
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_perpanjangan'))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_perpanjangan'))->back();
        }
    }

}