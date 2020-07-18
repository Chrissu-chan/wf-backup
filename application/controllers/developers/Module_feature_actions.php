<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_feature_actions extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('modules_m');
        $this->load->model('module_features_m');
        $this->load->model('module_feature_actions_m');
        $this->load->model('module_feature_action_methods_m');
        $this->load->library('form_validation');
    }

    public function index($module_id, $feature_id) {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->module_feature_actions_m)
            ->add_column('methods', function($model) {
                $methods = array();
                $rs_module_feature_action_methods = $this->module_feature_action_methods_m->where('module_feature_action_id', $model->id)
                ->get();
                foreach ($rs_module_feature_action_methods as $r_module_feature_action_method) {
                    $methods[] = '<label class="label label-primary">'.$r_module_feature_action_method->method.'</label>';
                }
                return implode(' ', $methods);
            })
            ->where('module_feature_id', $feature_id)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $module = $this->modules_m->find_or_fail($module_id);
        $feature = $this->module_features_m->find_or_fail($feature_id);
        $this->load->view('developers/module_feature_actions/index', array(
            'module' => $module,
            'feature' => $feature
        ));
    }

    public function view($module_id, $feature_id, $id) {
        $model = $this->module_feature_actions_m->find_or_fail($id);
        $rs_methods = $this->module_feature_action_methods_m->where('module_feature_action_id', $model->id)->get();
        $methods = array();
        foreach ($rs_methods as $r_method) {
            $methods[] = '<label class="label label-primary">'.$r_method->method.'</label>';
        }
        $model->method = implode(", ", $methods);
        $this->load->view('developers/module_feature_actions/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('developers/module_feature_actions/create');
    }

    public function store($module_id, $feature_id) {
        $this->transaction->start();
            $post = $this->input->post();
            $this->form_validation->validate(array(
                'action' => 'required',
                'label' => 'required',
                'methods' => 'required'
            ));
            $post['module_feature_id'] = $feature_id;
            $module_feature_action = $this->module_feature_actions_m->insert($post);
            $methods = explode(',', $post['methods']);
            foreach ($methods as $method) {
                $this->module_feature_action_methods_m->insert(array(
                    'module_feature_action_id' => $module_feature_action->id,
                    'method' => $method
                ));
            }
        $result = $this->transaction->complete();
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($module_id, $feature_id, $id) {
        $model = $this->module_feature_actions_m->find_or_fail($id);
        $rs_methods = $this->module_feature_action_methods_m->where('module_feature_action_id', $id)
        ->get();
        $methods = array();
        foreach ($rs_methods as $r_method) {
            $methods[] = $r_method->method;
        }
        $model->methods = implode(', ', $methods);
        $this->load->view('developers/module_feature_actions/edit', array(
            'model' => $model
        ));
    }

    public function update($module_id, $feature_id, $id) {
        $this->transaction->start();
            $post = $this->input->post();
            $this->form_validation->validate(array(
                'action' => 'required',
                'label' => 'required',
                'methods' => 'required'
            ));
            $post['module_feature_id'] = $feature_id;
            $this->module_feature_actions_m->update($id, $post);
            $methods = explode(',', $post['methods']);
            $this->module_feature_action_methods_m->where('module_feature_action_id', $id)
            ->delete();
            foreach ($methods as $method) {
                $this->module_feature_action_methods_m->insert(array(
                    'module_feature_action_id' => $id,
                    'method' => $method
                ));
            }
        $result = $this->transaction->complete();
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($module_id, $feature_id, $id) {
        $result = $this->module_feature_actions_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('module_feature_actions')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function insert_module_feature_actions() {
        $method = array('View', 'Create', 'Edit', 'Delete');
        $sub_method = array('Index', 'View', 'Create', 'Store', 'Edit', 'Update', 'Delete');
        $module = $this->module_features_m->get();
        for ($i=0; $i < count($module); $i++) {
            for ($j=0; $j < count($method); $j++) {
                $mod_feat_act = $this->module_feature_actions_m->insert(array(
                    'module_feature_id' => $module[$i]->id,
                    'action' => strtolower($method[$j]),
                    'label' => $method[$j]
                ));
                if($j == 0) {
                    for ($k=0; $k < 2; $k++) {
                        $this->module_feature_action_methods_m->insert(array(
                            'module_feature_action_id' => $mod_feat_act->id,
                            'method' => $sub_method[$k]
                        ));
                    }
                }

                if($j == 1) {
                    for ($k=2; $k < 4; $k++) {
                        $this->module_feature_action_methods_m->insert(array(
                            'module_feature_action_id' => $mod_feat_act->id,
                            'method' => $sub_method[$k]
                        ));
                    }
                }

                if($j == 2) {
                    for ($k=4; $k < 6; $k++) {
                        $this->module_feature_action_methods_m->insert(array(
                            'module_feature_action_id' => $mod_feat_act->id,
                            'method' => $sub_method[$k]
                        ));
                    }
                }

                if($j == 3) {
                    $this->module_feature_action_methods_m->insert(array(
                        'module_feature_action_id' => $mod_feat_act->id,
                        'method' => $sub_method[6]
                    ));
                }

                echo '<br>';
            }
            echo '<br>';
        }
    }
}