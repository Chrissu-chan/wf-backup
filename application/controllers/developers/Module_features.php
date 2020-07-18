<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_features extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('modules_m');
        $this->load->model('module_features_m');
        $this->load->model('module_feature_actions_m');
        $this->load->model('module_feature_action_methods_m');
        $this->load->library('form_validation');
        $this->load->helper('directory');
    }

    public function index($module_id) {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->module_features_m)
            ->where('module_id', $module_id)
            ->add_action('{actions} {view} {edit} {delete}', array(
                'actions' => function($model) {
                    return $this->action->link('module_feature_actions.view.actions', $this->url_generator->current_url().'/actions/'.$model->id, 'class="btn btn-primary btn-sm"');
                }
            ))
            ->generate();
        }
        $module = $this->modules_m->find_or_fail($module_id);
        $this->load->view('developers/module_features/index', array(
            'module' => $module
        ));
    }

    public function view($module_id, $id) {
        $model = $this->module_features_m->select('module_features.*, modules.module')
        ->join('modules', 'modules.id = module_features.module_id')
        ->find_or_fail($id);
        $this->load->view('developers/module_features/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('developers/module_features/create');
    }

    public function store($module_id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'feature' => 'required',
            'class' => 'required'
        ));
        $post['module_id'] = $module_id;
        $result = $this->module_features_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('module_features')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('module_features')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($module_id, $id) {
        $model = $this->module_features_m->find_or_fail($id);
        $this->load->view('developers/module_features/edit', array(
            'model' => $model
        ));
    }

    public function update($module_id, $id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'feature' => 'required',
            'class' => 'required'
        ));
        $post['module_id'] = $module_id;
        $result = $this->module_features_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('module_features')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('module_features')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($module_id, $id) {
        $result = $this->module_features_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('module_features')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('module_features')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function generate($module_id) {
        $module = $this->modules_m->find($module_id);
        $this->load->view('developers/module_features/generate', array(
            'module' => $module
        ));
    }

    public function generate_store($module_id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'table' => 'required'
        ));
        $target_directory = '.';
        /*$target_directory = trim($post['target_directory'], '\\');
        $target_directory = trim($target_directory, '/');
        if (!is_dir($target_directory)) {
            $this->redirect->with('error_message', 'Can\'t find target directory')->back();
        }
        if (!is_dir($target_directory.'/application')) {
            $this->redirect->with('error_message', 'Can\'t find target directory application')->back();
        }

        if (!is_dir(APPPATH.'controllers')) {
            $this->redirect->with('error_message', 'Can\'t find target directory controllers inside application')->back();
        }

        if (!is_dir(APPPATH.'models')) {
            $this->redirect->with('error_message', 'Can\'t find target directory models inside application')->back();
        }

        if (!is_dir(APPPATH.'views')) {
            $this->redirect->with('error_message', 'Can\'t find target directory views inside application')->back();
        }*/
        if (isset($post['module'])) {
            $module = strtolower($post['module']);
        } else {
            $module = '';
        }
        $table = strtolower($post['table']);
        $model = ucwords($table).'_m';
        $exception_columns = explode(',', $post['exception_columns']);
        $exception_columns = array_map('trim', $exception_columns);
        $controller = ucwords($table);
        $title = strtolower($table);
        $view = strtolower($table);
        $path = '/'.$module;

        if ($module) {
            if (!is_dir(APPPATH.'controllers/'.$module)) {
                mkdir(APPPATH.'controllers/'.$module);
            }
            if (!is_dir(APPPATH.'views/'.$module)) {
                mkdir(APPPATH.'views/'.$module);
            }
            if (!is_dir(APPPATH.'views'.$path.'/'.$view)) {
                mkdir(APPPATH.'views'.$path.'/'.$view);
            }
        }

        $fields = $this->db->field_data($table);
        $primary_key = 'id';
        $fillable = array();
        $columns = '';
        $columns_datable = array();
        $form_script = '<div id="frm-message"></div>
';
        $view_script = '<table width="100%" class="table table-profile">';
        foreach ($fields as $field) {
            if (!in_array($field->name, $exception_columns)) {
                if ($field->primary_key) {
                    $primary_key = $field->name;
                } else {
                    $fillable[]='\''.$field->name.'\'';
                    $columns .= '
                        <th>{{'.$field->name.'}}</th>';
                    $columns_datable[] = '
                {data: \''.$field->name.'\', name: \''.$table.'.'.$field->name.'\'}';
                    $form_script .='<div class="form-group">
    <label>{{'.$field->name.'}}</label>
    <?= $this->form->text(\''.$field->name.'\', null, \'id="'.$field->name.'" class="form-control"\') ?>
</div>
';
                    $view_script .= '
    <tr>
        <td class="field">{{'.$field->name.'}}</td>
        <td><?= $model->'.$field->name.' ?></td>
    </tr>';
                }
            }
        }
        $view_script .= '
</table>';

        $model_tmp = read_file(APPPATH.'views/developers/module_features/template/model.tmp');
        $model_script = str_replace('{table}', $table, $model_tmp);
        $model_script = str_replace('{class}', $model, $model_script);
        $model_script = str_replace('{primary_key}', $primary_key, $model_script);
        $model_script = str_replace('{fillable}', implode(',', $fillable), $model_script);
        write_file(APPPATH.'models/'.$model.'.php', $model_script);

        $controller_tmp = read_file(APPPATH.'views/developers/module_features/template/controller.tmp');
        $model = strtolower($model);
        $controller_script = str_replace('{class}', $controller, $controller_tmp);
        $controller_script = str_replace('{model}', $model, $controller_script);
        $controller_script = str_replace('{title}', $title  , $controller_script);
        $controller_script = str_replace('{path}', trim($path.'/'.$view, '/'), $controller_script);
        write_file(APPPATH.'controllers'.$path.'/'.$controller.'.php', $controller_script);

        $index_tmp = read_file(APPPATH.'views/developers/module_features/template/views/index.tmp');
        $index_script = str_replace('{title}', $title, $index_tmp);

        $columns .='
                        <td width="1"></td>';
        $columns_datable[] ='
                {data:\'_action\', searchable: false, orderable: false, class: \'text-right nowrap\'}';
        $index_script = str_replace('{columns}', $columns, $index_script);
        $index_script = str_replace('{columns_datatable}', implode(',', $columns_datable), $index_script);

        write_file(APPPATH.'views'.$path.'/'.$view.'/index.php', $index_script);
        write_file(APPPATH.'views'.$path.'/'.$view.'/form.php', trim($form_script));

        $create_tmp = read_file(APPPATH.'views/developers/module_features/template/views/create.tmp');
        $create_script = str_replace('{path}', trim($path.'/'.$view, '/'), $create_tmp);
        write_file(APPPATH.'views'.$path.'/'.$view.'/create.php', $create_script);

        $edit_tmp = read_file(APPPATH.'views/developers/module_features/template/views/edit.tmp');
        $edit_script = str_replace('{path}', trim($path.'/'.$view, '/'), $edit_tmp);
        $edit_script = str_replace('{primary_key}', $primary_key, $edit_script);
        write_file(APPPATH.'views'.$path.'/'.$view.'/edit.php', $edit_script);

        write_file(APPPATH.'views'.$path.'/'.$view.'/view.php', $view_script);

        $r_module_feature = $this->module_features_m->where('module_id', $module_id)
        ->where('class', trim($path.'/'.$controller.'.php', '/'))
        ->first();
        if(!$r_module_feature) {
            $r_module_feature = $this->module_features_m->insert(array(
                'module_id' => $module_id,
                'feature' => ucfirst($table),
                'class' => trim($path.'/'.$controller.'.php', '/')
            ));

            $r_module_feature_action_view = $this->module_feature_actions_m->insert(array(
                'module_feature_id' => $r_module_feature->id,
                'action' => 'view',
                'label' => 'View'
            ));

            $this->module_feature_action_methods_m->insert_batch(array(
                array(
                    'module_feature_action_id' => $r_module_feature_action_view->id,
                    'method' => 'index'
                ),
                array(
                    'module_feature_action_id' => $r_module_feature_action_view->id,
                    'method' => 'view'
                )
            ));

            $r_module_feature_action_create = $this->module_feature_actions_m->insert(array(
                'module_feature_id' => $r_module_feature->id,
                'action' => 'create',
                'label' => 'Create'
            ));

            $this->module_feature_action_methods_m->insert_batch(array(
                array(
                    'module_feature_action_id' => $r_module_feature_action_create->id,
                    'method' => 'create'
                ),
                array(
                    'module_feature_action_id' => $r_module_feature_action_create->id,
                    'method' => 'store'
                )
            ));

            $r_module_feature_action_edit = $this->module_feature_actions_m->insert(array(
                'module_feature_id' => $r_module_feature->id,
                'action' => 'edit',
                'label' => 'Edit'
            ));

            $this->module_feature_action_methods_m->insert_batch(array(
                array(
                    'module_feature_action_id' => $r_module_feature_action_edit->id,
                    'method' => 'edit'
                ),
                array(
                    'module_feature_action_id' => $r_module_feature_action_edit->id,
                    'method' => 'update'
                )
            ));

            $r_module_feature_action_delete = $this->module_feature_actions_m->insert(array(
                'module_feature_id' => $r_module_feature->id,
                'action' => 'delete',
                'label' => 'delete'
            ));

            $this->module_feature_action_methods_m->insert_batch(array(
                array(
                    'module_feature_action_id' => $r_module_feature_action_delete->id,
                    'method' => 'delete'
                )
            ));
        }
        $this->redirect->with('success_message', $table.' successfuly generated')
        ->to('developers/modules/features/'.$module_id);
    }
}