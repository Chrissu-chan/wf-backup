<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('cabang_m');
        $this->load->model('kota_m');
        $this->load->model('gudang_m');
        $this->load->model('cabang_gudang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $model = tree($this->cabang_m->get(), 'id', 'parent_id', 0);
            return $this->load->view('master/cabang/treetable', array('model' => $model));
        }
        $this->load->view('master/cabang/index');
    }

    public function view($id) {
        $model = $this->cabang_m->find_or_fail($id);
        $this->load->view('master/cabang/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $parent_id = $this->input->get('parent_id');
        $this->load->view('master/cabang/create', array('parent_id' => $parent_id));
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'nama' => 'required|is_unique[cabang.nama]',
            'telepon' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required'
        ));
        $result = $this->cabang_m->insert($post);
        $gudang = $this->gudang_m->insert(array('gudang' => $post['nama']));
        $this->cabang_gudang_m->insert(array(
            'id_cabang' => $result->id,
            'id_gudang' => $gudang->id,
            'utama' => 1
        ));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('cabang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('cabang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->cabang_m->find_or_fail($id);
        $this->load->view('master/cabang/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'nama' => 'required|is_unique[cabang.nama.'.$id.']',
            'telepon' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required'
        ));
        $result = $this->cabang_m->update($id, $post);
        $cabang_gudang = $this->cabang_gudang_m->where('id_cabang', $id)
            ->where('utama', 1)
            ->first();
        $gudang = $this->gudang_m->update($cabang_gudang->id_gudang, array('gudang' => $post['nama']));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('cabang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('cabang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $cabang_gudang = $this->cabang_gudang_m->where('id_cabang', $id)->first();
        $this->cabang_gudang_m->delete($cabang_gudang->id);
        $result = $this->cabang_m->delete($id);
        $gudang = $this->gudang_m->delete($cabang_gudang->id_gudang);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('cabang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('cabang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}