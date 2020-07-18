<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applications extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->applications_m)
            ->add_action('{modules} {menus} {config} {view} {edit} {delete}', array(
                'modules' => function($model) {
                    return $this->action->link('application_modules.view.modules', $this->url_generator->current_url().'/modules/'.$model->id, 'class="btn btn-primary btn-sm"');
                },
                'menus' => function($model) {
                    return $this->action->link('application_menus.view.menus', $this->url_generator->current_url().'/menus/'.$model->id, 'class="btn btn-primary btn-sm"');
                },
                'config' => function($model) {
                    return $this->action->link('application_configsview.config', $this->url_generator->current_url().'/config/'.$model->id, 'class="btn btn-primary btn-sm"');
                }
            ))
            ->generate();
        }
        $this->load->view('developers/applications/index');
    }

    public function view($id) {
        $model = $this->applications_m->find_or_fail($id);
        $this->load->view('developers/applications/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('developers/applications/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'application' => 'required|is_unique[applications.application]'
        ));
        $result = $this->applications_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('applications')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('applications')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->applications_m->find_or_fail($id);
        $this->load->view('developers/applications/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'application' => 'required|is_unique[applications.application.'.$id.']'
        ));
        $result = $this->applications_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('applications')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('applications')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->applications_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('applications')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('applications')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}