<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_modules extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_m');
        $this->load->model('application_modules_m');
        $this->load->model('modules_m');
        $this->load->library('form_validation');
    }

    public function index($application_id) {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->application_modules_m)
            ->view('modules')
            ->where('application_modules.application_id', $application_id)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $application = $this->applications_m->find_or_fail($application_id);
        $this->load->view('developers/application_modules/index', array(
            'application' => $application
        ));
    }

    public function view($application_id, $id) {
        $model = $this->application_modules_m->view('modules')
        ->find_or_fail($id);
        $this->load->view('developers/application_modules/view', array(
            'model' => $model
        ));
    }

    public function create($application_id) {
        $this->load->view('developers/application_modules/create');
    }

    public function store($application_id) {
        $post = $this->input->post();
        $post['application_id'] = $application_id;
        $result = $this->application_modules_m->insert($post);
        $this->form_validation->validate(array(
            'module_id' => 'required'
        ));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('application_modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('application_modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($application_id, $id) {
        $model = $this->application_modules_m->find_or_fail($id);
        $this->load->view('developers/application_modules/edit', array(
            'model' => $model
        ));
    }

    public function update($application_id, $id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'module_id' => 'required'
        ));
        $result = $this->application_modules_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('application_modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('application_modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($application_id, $id) {
        $result = $this->application_modules_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('application_modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('application_modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}