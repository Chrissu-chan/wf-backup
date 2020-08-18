<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('roles_m');
        $this->load->model('role_permissions_m');
        $this->load->model('application_modules_m');
        $this->load->model('module_feature_actions_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "User Roles";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->roles_m)
                ->add_action('{permissions} {view} {edit} {delete}', array(
                    'permissions' => function ($model) {
                        return $this->action->link('permissions', $this->url_generator->current_url() . '/permissions/' . $model->id, 'class="btn btn-primary btn-sm"');
                    }
                ))
                ->generate();
        }
        $this->load->view('users/roles/index', $data);
    }

    public function view($id)
    {
        $model = $this->roles_m->find_or_fail($id);
        $this->load->view('users/roles/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('users/roles/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'role' => 'required|is_unique[roles.role]'
        ));
        $result = $this->roles_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('roles')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('roles')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->roles_m->find_or_fail($id);
        $this->load->view('users/roles/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'role' => 'required|is_unique[roles.role.' . $id . ']'
        ));
        $result = $this->roles_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('roles')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('roles')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function permissions($id)
    {
        $title = "Roles";
        $role = $this->roles_m->find_or_fail($id);
        $permissions = array();
        $rs_application_actions = $this->application_modules_m->view('application_actions')
            ->get();
        foreach ($rs_application_actions as $r_application_action) {
            $permissions[$r_application_action->application_id]['application_id'] = $r_application_action->application_id;
            $permissions[$r_application_action->application_id]['application'] = $r_application_action->application;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['module_id'] = $r_application_action->module_id;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['module'] = $r_application_action->module;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['features'][$r_application_action->module_feature_id]['module_feature_id'] = $r_application_action->module_feature_id;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['features'][$r_application_action->module_feature_id]['feature'] = $r_application_action->feature;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['features'][$r_application_action->module_feature_id]['actions'][$r_application_action->module_feature_action_id]['module_feature_action_id'] = $r_application_action->module_feature_action_id;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['features'][$r_application_action->module_feature_id]['actions'][$r_application_action->module_feature_action_id]['action'] = $r_application_action->action;
            $permissions[$r_application_action->application_id]['modules'][$r_application_action->module_id]['features'][$r_application_action->module_feature_id]['actions'][$r_application_action->module_feature_action_id]['label'] = $r_application_action->label;
        }
        $rs_role_permissions = $this->role_permissions_m->where('role_id', $id)->get();
        $model = array();
        foreach ($rs_role_permissions as $r_role_permission) {
            $model['permissions'][$r_role_permission->application_id][$r_role_permission->module_feature_action_id] = $r_role_permission->permission;
        }
        $this->load->view('users/roles/permissions', array(
            'role' => $role,
            'permissions' => $permissions,
            'model' => $model, 'title' => $title
        ));
    }

    public function permissions_save($id)
    {
        $post = $this->input->post();
        $this->role_permissions_m->where('role_id', $id)
            ->delete();
        $records = array();
        if (isset($post['permissions'])) {
            foreach ($post['permissions'] as $application_id => $application) {
                foreach ($application as $module_feature_action_id => $action) {
                    $records[] = array(
                        'role_id' => $id,
                        'application_id' => $application_id,
                        'module_feature_action_id' => $module_feature_action_id,
                        'permission' => 1
                    );
                }
            }
        }
        if (count($records) <> 0) {
            $this->role_permissions_m->insert_batch($records);;
        }
        $this->redirect->with('success_message', $this->localization->lang('success_save_message', array('name' => $this->localization->lang('permissions'))))->back();
    }

    public function delete($id)
    {
        $result = $this->roles_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('roles')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('roles')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
