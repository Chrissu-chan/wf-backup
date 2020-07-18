<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_petugas extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('jenis_petugas_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->jenis_petugas_m)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('master/jenis_petugas/index');
    }

    public function view($id) {
        $model = $this->jenis_petugas_m->find_or_fail($id);
        $this->load->view('master/jenis_petugas/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/jenis_petugas/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_petugas' => 'required|is_unique[jenis_petugas.jenis_petugas]'
        ));
        $result = $this->jenis_petugas_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->jenis_petugas_m->find_or_fail($id);
        $this->load->view('master/jenis_petugas/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_petugas' => 'required|is_unique[jenis_petugas.jenis_petugas.'.$id.']'
        ));
        $result = $this->jenis_petugas_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->jenis_petugas_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jenis_petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}