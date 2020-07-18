<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_menus extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_m');
        $this->load->model('application_menus_m');
        $this->load->model('module_feature_actions_m');
        $this->load->library('form_validation');
    }

    public function index($application_id) {
        $application = $this->applications_m->find_or_fail($application_id);
        $model = $this->application_menus_m->view('menus')
        ->where('application_id', $application_id)
        ->order_by('sequence', 'asc')
        ->get();
        $model = tree($model, 'id', 'parent_id', 0);
        $this->load->view('developers/application_menus/index', array(
            'application' => $application,
            'model' => $model
        ));
    }

    public function view($application_id, $id) {
        $model = $this->application_menus_m->view('menus')
        ->find_or_fail($id);
        $this->load->view('developers/application_menus/view', array(
            'model' => $model
        ));
    }

    public function create($application_id) {
        $parent_id = $this->input->get('parent_id');
        $this->load->view('developers/application_menus/create', array(
            'application_id' => $application_id,
            'parent_id' => $parent_id
        ));
    }

    public function store($application_id) {
        $post = $this->input->post();
        $post['application_id'] = $application_id;
        $post['sequence'] = $this->application_menus_m->sequence($post['parent_id']);
        $result = $this->application_menus_m->insert($post);
        $this->form_validation->validate(array(
            'menu' => 'required'
        ));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('application_menus')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('application_menus')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($application_id, $id) {
        $model = $this->application_menus_m->find_or_fail($id);
        $this->load->view('developers/application_menus/edit', array(
            'model' => $model,
            'parent_id' => $model->parent_id
        ));
    }

    public function update($application_id, $id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'menu' => 'required'
        ));
        $result = $this->application_menus_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('application_menus')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('application_menus')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function update_sequence() {
        $post = $this->input->post();
        foreach ($post['sequence'] as $id => $sequence) {
            $this->application_menus_m->update($id, array(
                'sequence' => $sequence
            ));
        }
        $this->redirect->with('success_message', 'Sequence menu berhasil diperbarui')->back();
    }

    public function delete($application_id, $id) {
        $result = $this->application_menus_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('application_menus')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('application_menus')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}