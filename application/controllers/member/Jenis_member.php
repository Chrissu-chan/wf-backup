<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_member extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('jenis_member_m');
        $this->load->model('jenis_member_aktif_cabang_m');
        $this->load->model('jenis_member_cabang_m');
        $this->load->model('jenis_member_pendaftaran_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->jenis_member_m)
            ->view('jenis_member')
            ->edit_column('biaya', function($model) {
                return $this->localization->number($model->biaya);
            })
            ->edit_column('total', function($model) {
                return $this->localization->number($model->total);
            })
            ->add_action('{masa_aktif} {view} {edit} {delete}', array(
                'masa_aktif' => function($model) {
                    return $this->action->link('jenis_member.index.masa_aktif', $this->url_generator->current_url().'/masa_aktif/'.$model->id, 'class="btn btn-primary btn-sm"');
                }
            ))
            ->generate();
        }
        $this->load->view('member/jenis_member/index');
    }

    public function view($id) {
        $model = $this->jenis_member_m->view('jenis_member')->find_or_fail($id);
        $this->load->view('member/jenis_member/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('member/jenis_member/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_member' => 'required',
            'biaya' => 'required',
            'ppn' => 'required',
            'ppn_persen' => 'required',
            'masa_aktif' => 'required|numeric'
        ));
        $result = $this->jenis_member_m->insert($post);
        $member_cabang = array(
            'id_jenis_member' => $result->id,
            'id_cabang' => $this->session->userdata('cabang')->id
        );
        $this->jenis_member_aktif_cabang_m->insert($member_cabang);
        $this->jenis_member_cabang_m->insert($member_cabang);
        $this->jenis_member_pendaftaran_m->insert(array_merge($post, $member_cabang));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jenis_member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jenis_member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->jenis_member_m->view('jenis_member')->find_or_fail($id);
        $this->load->view('member/jenis_member/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_member' => 'required',
            'biaya' => 'required',
            'ppn' => 'required',
            'ppn_persen' => 'required',
            'masa_aktif' => 'required|numeric'
        ));
        $result = $this->jenis_member_m->update($id, $post);
        $this->jenis_member_pendaftaran_m->where('id_jenis_member', $id)->update($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jenis_member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jenis_member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $this->jenis_member_aktif_cabang_m->where('id_jenis_member', $id)->delete();
        $this->jenis_member_pendaftaran_m->where('id_jenis_member', $id)->delete();
        $this->jenis_member_cabang_m->where('id_jenis_member', $id)->delete();
        $result = $this->jenis_member_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jenis_member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jenis_member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function json($id) {
        $result = $this->jenis_member_m->view('jenis_member')->find_or_fail($id);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}