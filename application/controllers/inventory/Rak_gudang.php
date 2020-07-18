<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak_gudang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('cabang_gudang_m');
        $this->load->model('rak_gudang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->rak_gudang_m)
                ->view('rak_gudang')
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('inventory/rak_gudang/index');
    }
    
    public function view($id) {
        $model = $this->rak_gudang_m->view('rak_gudang')->find_or_fail($id);
        $this->load->view('inventory/rak_gudang/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('inventory/rak_gudang/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_gudang' => 'required',
            'rak' => 'required'
        ));
        $result = $this->rak_gudang_m->insert($post);        
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_store_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->rak_gudang_m->view('rak_gudang')->find_or_fail($id);
        $this->load->view('inventory/rak_gudang/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_gudang' => 'required',
            'rak' => 'required'
        ));
        $result = $this->rak_gudang_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_update_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->rak_gudang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_delete_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json() {
        $this->db->where('id_gudang', $this->input->get('id_gudang'));
        $result = $this->rak_gudang_m->view('rak_gudang')->get();
        if ($result) {
            $response = array(
                'success' => true,
                'data' => $result
            );
        } else {
            $response = array(
                'success' => false,
                'data' => NULL,
                'message' => $this->localization->lang('error_get_message', array('name' => $this->localization->lang('rak_gudang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}