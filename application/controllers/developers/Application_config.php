<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_config extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_m');
        $this->load->model('config_m');        
        $this->load->library('form_validation');
    }

    public function index($application_id) {
        $application = $this->applications_m->find_or_fail($application_id);
        $model = array();
        foreach ($this->config_m->where('application_id', $application_id)->get() as $config) {
            $model[$config->key] = $config->value;
        }
        $this->load->view('developers/application_config/index', array(
            'application' => $application,
            'model' => $model
        ));
    }

    public function save($application_id) {
        $post = $this->input->post();
        foreach ($post as $key => $value) {
            $config = $this->config_m->where('application_id', $application_id)
            ->where('key', $key)
            ->first();
            if ($config) {
                $this->config_m->where('key', $key  )
                ->update(array('value' => $value));
            } else {
                $this->config_m->insert(array(
                    'application_id' => $application_id,
                    'config' => 'application',
                    'key' => $key,
                    'value' => $value
                ));
            }
        }
        $this->redirect->with('success_message', $this->localization->lang('success_save_message', array('name' => $this->localization->lang('application_config'))))->back();
    }
}