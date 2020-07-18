<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Satuan_barang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('satuan_barang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->satuan_barang_m)
            ->scope('parent')
            ->add_action('{konversi} {view} {edit} {delete}', array(
                'konversi' => function($model) {
                    return $this->action->link('view.konversi', $this->route->name('master.satuan_barang.konversi', array('id' => $model->id)), 'class="btn btn-primary btn-sm"');
                }
            ))
            ->generate();
        }
        $this->load->view('master/satuan_barang/index');
    }

    public function konversi($id) {
         if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->satuan_barang_m)
            ->where('parent_id', $id)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $parent = $this->satuan_barang_m->find_or_fail($id);
        $this->load->view('master/satuan_barang/konversi', array(
            'id' => $id,
            'parent' => $parent
        ));
    }

    public function view($id) {
        $model = $this->satuan_barang_m->find_or_fail($id);
        $parent = $this->satuan_barang_m->find($model->parent_id);
        $this->load->view('master/satuan_barang/view', array(
            'model' => $model,
            'parent' => $parent
        ));
    }

    public function create() {
        $parent_id = $this->input->get('parent_id');
        $parent = $this->satuan_barang_m->find($parent_id);
        $this->load->view('master/satuan_barang/create', array(
            'parent_id' => $parent_id,
            'parent' => $parent
        ));
    }

    public function store() {
        $post = $this->input->post();
        if ($post['parent_id'] <> 0) {
            $parent = $this->satuan_barang_m->find($post['parent_id']);
            $this->load->library('autonumber');
            $post['kode'] = $this->autonumber->resource($this->satuan_barang_m, 'kode')
                ->format($parent->kode.'-:2')
                ->generate();
            $this->form_validation->validate(array(
                'satuan_barang' => 'required'
            ));
        } else {
            $this->form_validation->validate(array(
                'kode' => 'required|is_unique[satuan_barang.kode]',
                'satuan_barang' => 'required'
            ));
        }
        $post['pengali'] = (empty($post['pengali'])) ? 1 : $post['pengali'];
        $result = $this->satuan_barang_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->satuan_barang_m->find_or_fail($id);
        $parent = $this->satuan_barang_m->find($model->parent_id);
        $this->load->view('master/satuan_barang/edit', array(
            'model' => $model,
            'parent' => $parent
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'satuan_barang' => 'required'
        ));
        $result = $this->satuan_barang_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->satuan_barang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('satuan_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json() {
        if ($this->input->get()) {
            $this->satuan_barang_m->where($this->input->get());
        }
        $result = $this->satuan_barang_m->get();
        $response = array(
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}