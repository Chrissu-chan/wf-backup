<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('satuan_m');
        $this->load->model('konversi_satuan_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->satuan_m)
                ->add_action('{konversi} {view} {edit} {delete}', array(
                    'konversi' => function($model) {
                        return $this->action->link('konversi_satuan.view.konversi', $this->url_generator->current_url().'/konversi/'.$model->id, 'class="btn btn-primary btn-sm"');
                    }
                ))
                ->generate();
        }
        $this->load->view('master/satuan/index');
    }

    public function view($id) {
        $model = $this->satuan_m->find_or_fail($id);
        $this->load->view('master/satuan/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/satuan/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'satuan' => 'required',
	        'grup' => 'required'
        ));
        $result = $this->satuan_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('satuan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('satuan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->satuan_m->find_or_fail($id);
        $this->load->view('master/satuan/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'satuan' => 'required',
	        'grup' => 'required'
        ));
        $result = $this->satuan_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('satuan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('satuan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $this->transaction->start();
            $this->satuan_m->delete($id);
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('satuan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('satuan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json() {
        if ($this->input->get()) {
            $this->satuan_m->where('id_satuan_tujuan', $this->input->get('id'));
        }
        $result = $this->satuan_m->view('satuan')->get();

        $response = array(
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}