<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_m');
        $this->load->model('roles_m');
        $this->load->model('user_roles_m');
        $this->load->model('cabang_m');
        $this->load->model('user_cabang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->users_m)
            ->add_column('roles', function($model) {
                $roles = array();
                $rs_user_roles = $this->user_roles_m->view('roles')
                ->where('user_id', $model->id)
                ->get();
                foreach ($rs_user_roles as $r_user_role) {
                    $roles[] = '<label class="label label-primary">'.$r_user_role->role.'</label>';
                }
                return implode(' ', $roles);
            })
            ->edit_column('active', function($model) {
                return $this->users_m->enum('active', $model->active);
            })
            ->add_action('{reset_password} {view} {edit} {delete}', array(
                'reset_password' => function($model) {
                    return $this->action->button('reset_password', 'class="btn btn-primary btn-sm" onclick="resetPassword(\''.$model->id.'\')"');
                }
            ))
            ->generate();
        }
        $this->load->view('users/users/index');
    }

    public function view($id) {
        $model = $this->users_m->find_or_fail($id);
        $this->load->view('users/users/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('users/users/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'username' => 'required|is_unique[users.username]',
            'password' => 'required',
            'confirm_password' => 'matches[password]',
            'name' => 'required',
            'roles[]' => 'required'
        ));
        if (!isset($post['cabang'])) {
            $post['cabang'][] = 0;
        }
        $post['active'] = 1;
        $result = $this->users_m->insert($post);
        $cabang = array();
        foreach ($post['cabang'] as $cabang_id) {
            $cabang[] = array(
                'id_user' => $result->id,
                'id_cabang' => $cabang_id
            );
        }
        $this->user_cabang_m->insert_batch($cabang);
        $roles = array();
        foreach ($post['roles'] as $role_id) {
            $roles[] = array(
                'user_id' => $result->id,
                'role_id' => $role_id
            );
        }
        $this->user_roles_m->insert_batch($roles);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('users')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('users')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->users_m->find_or_fail($id);
        $rs_user_roles = $this->user_roles_m->where('user_id', $model->id)
        ->get();
        $rs_user_cabang = $this->user_cabang_m->where('id_user', $model->id)->get();
        foreach ($rs_user_roles as $r_user_role) {
            $model->roles[] = $r_user_role->role_id;
        }
        foreach ($rs_user_cabang as $r_user_cabang) {
            $model->cabang[] = $r_user_cabang->id_cabang;
        }
        $this->load->view('users/users/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'username' => 'required|is_unique[users.username.'.$id.']',
            'name' => 'required',
            'roles[]' => 'required'
        ));
        if (!isset($post['cabang'])) {
            $post['cabang'][] = 0;
        }
        $result = $this->users_m->update($id, $post);
        $this->user_cabang_m->where('id_user', $id)->delete();
        $cabang = array();
        foreach ($post['cabang'] as $id_cabang) {
            $cabang[] = array(
                'id_user' => $id,
                'id_cabang' => $id_cabang
            );
        }
        $this->user_cabang_m->insert_batch($cabang);
        $this->user_roles_m->where('user_id', $id)->delete();
        $roles = array();
        foreach ($post['roles'] as $role_id) {
            $roles[] = array(
                'user_id' => $id,
                'role_id' => $role_id
            );
        }
        $this->user_roles_m->insert_batch($roles);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('users')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('users')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function reset_password($id) {
        $model = $this->users_m->find_or_fail($id);
        $this->load->view('users/users/reset_password', array(
            'model' => $model
        ));
    }

    public function reset_password_store($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'password' => 'required',
            'confirm_password' => 'matches[password]',
        ));
        $result = $this->users_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_reset_password_message', array('name' => $this->localization->lang('users')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_reset_password_message', array('name' => $this->localization->lang('users')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->users_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('users')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('users')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}