<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fungsi_obat extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('fungsi_obat_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->fungsi_obat_m)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('master/fungsi_obat/index');
    }
    
    public function view($id) {
        $model = $this->fungsi_obat_m->find_or_fail($id);
        $this->load->view('master/fungsi_obat/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/fungsi_obat/create');
    }

    public function store() {
        $post = $this->input->post();
        //$this->form_validation->validate(array());
        $result = $this->fungsi_obat_m->insert($post);        
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_store_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->fungsi_obat_m->find_or_fail($id);
        $this->load->view('master/fungsi_obat/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        //$this->form_validation->validate(array());
        $result = $this->fungsi_obat_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_update_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->fungsi_obat_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_delete_message', array('name' => $this->localization->lang('fungsi_obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}