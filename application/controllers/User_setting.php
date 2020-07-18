<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_setting extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_m');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('user_setting/index');
    }

    public function save_profile() {
        $post = $this->input->post();
        $config['upload_path'] = './'.$this->config->item('photo_upload_path');
        $config['allowed_types'] = $this->config->item('photo_allowed_file_types');
        $config['max_size'] = $this->config->item('photo_max_size');
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        if ($this->upload->has('photo')) {
            if(!$this->upload->do_upload('photo')) {
                $this->redirect->with('error_message', $this->upload->display_errors())->back();
            }
            $file_name = $this->upload->data('file_name');
        }
        $this->form_validation->validate(array(
            'name' => 'required'
        ));
        $result = $this->users_m->where('id', $this->auth->id)
        ->update(array(
            'name' => $post['name'],
            'photo' => isset($file_name) ? $file_name : null
        ));
        if ($result) {
            $this->auth->reload();
            $this->redirect->with('success_message', $this->localization->lang('success_save_profile_message'))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_save_profile_message'))->back();
        }
    }

    public function change_password() {
        $post = $this->input->post();
        if ($this->users_m->set_password($post['old_password']) <> $this->auth->password) {
            $this->redirect->with('error_message', $this->localization->lang('old_password_does_not_match'))->back();
        }
        $this->form_validation->validate(array(
            'old_password' => 'required',
            'new_password' => 'required',
            'retype_new_password' => 'matches[new_password]'
        ));

        $result = $this->users_m->where('id', $this->auth->id)
        ->update(array(
            'password' => $post['new_password']
        ));
        if ($result) {
            $this->auth->reload();
            $this->redirect->with('success_message', $this->localization->lang('success_change_password_message'))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_change_password_message'))->back();
        }
    }
}