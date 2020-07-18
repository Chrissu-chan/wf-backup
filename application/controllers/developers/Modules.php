<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('modules_m');
        $this->load->model('module_features_m');
        $this->load->model('module_feature_actions_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->modules_m)
            ->add_action('{features} {view} {edit} {delete}', array(
                'features' => function($model) {
                    return $this->action->link('module_features.view.features', $this->url_generator->current_url().'/features/'.$model->id, 'class="btn btn-primary btn-sm"');
                }
            ))
            ->generate();
        }
        $this->load->view('developers/modules/index');
    }

    public function view($id) {
        $model = $this->modules_m->find_or_fail($id);
        $this->load->view('developers/modules/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('developers/modules/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'module' => 'required|is_unique[modules.module]'
        ));
        $result = $this->modules_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->modules_m->find_or_fail($id);
        $this->load->view('developers/modules/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'module' => 'required|is_unique[modules.module.'.$id.']'
        ));
        $result = $this->modules_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->modules_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('modules')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('modules')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}