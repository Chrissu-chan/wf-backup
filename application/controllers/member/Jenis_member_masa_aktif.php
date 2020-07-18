<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_member_masa_aktif extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('jenis_member_masa_aktif_m');
        $this->load->library('form_validation');
    }

    public function index($jenis_member_id) {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->jenis_member_masa_aktif_m)
            ->where('id_jenis_member', $jenis_member_id)
            ->edit_column('biaya', function($model) {
                return $this->localization->number($model->biaya);
            })
            ->edit_column('total', function($model) {
                return $this->localization->number($model->total);
            })
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('member/jenis_member_masa_aktif/index');
    }

    public function view($jenis_member_id, $id) {
        $model = $this->jenis_member_masa_aktif_m->find_or_fail($id);
        $this->load->view('member/jenis_member_masa_aktif/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('member/jenis_member_masa_aktif/create');
    }

    public function store($jenis_member_id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'biaya' => 'required',
            'masa_aktif' => 'required|numeric'
        ));
        $post['id_jenis_member'] = $jenis_member_id;
        $post['id_cabang'] = $this->session->userdata('cabang')->id;
        $result = $this->jenis_member_masa_aktif_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($jenis_member_id, $id) {
        $model = $this->jenis_member_masa_aktif_m->find_or_fail($id);
        $this->load->view('member/jenis_member_masa_aktif/edit', array(
            'model' => $model
        ));
    }

    public function update($jenis_member_id, $id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'biaya' => 'required',
            'masa_aktif' => 'required|numeric'
        ));
        $post['id_jenis_member'] = $jenis_member_id;
        $result = $this->jenis_member_masa_aktif_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($jenis_member_id, $id) {
        $result = $this->jenis_member_masa_aktif_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jenis_member_masa_aktif')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function json($id_jenis_member) {
        $result = $this->jenis_member_masa_aktif_m->where('id_jenis_member', $id_jenis_member)->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}