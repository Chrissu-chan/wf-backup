<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Localizations extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('localizations_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->localizations_m)
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('developers/localizations/index');
    }

    public function view($id) {
        $model = $this->localizations_m->find_or_fail($id);
        $this->load->view('developers/localizations/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('developers/localizations/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'country' => 'required',
            'timezone' => 'required'
        ));
        $result = $this->localizations_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('localizations')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('localizations')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->localizations_m->find_or_fail($id);
        $this->load->view('developers/localizations/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'country' => 'required',
            'timezone' => 'required'
        ));
        $result = $this->localizations_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('localizations')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('localizations')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->localizations_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('localizations')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('localizations')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}