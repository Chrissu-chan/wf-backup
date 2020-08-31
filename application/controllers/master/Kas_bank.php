<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kas_bank extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kas_bank_m');
        $this->load->model('bank_m');
        $this->load->model('cabang_m');
        $this->load->model('kas_bank_cabang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Kas & Bank";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->kas_bank_m)
                ->view('kas_bank')
                ->edit_column('jenis_kas_bank', function ($model) {
                    return $this->kas_bank_m->enum('kas_bank', $model->jenis_kas_bank);
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/kas_bank/index', $data);
    }

    public function view($id)
    {
        $model = $this->kas_bank_m->view('kas_bank')->find_or_fail($id);
        $this->load->view('master/kas_bank/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/kas_bank/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $validation = array(
            'nama' => 'required',
            'jenis_kas_bank' => 'required',
            'cabang[]' => 'required'
        );

        if ($post['jenis_kas_bank'] == 'bank') {
            $bank_validation = array(
                'id_bank' => 'required',
                'nomor_rekening' => 'required',
                'nama_rekening' => 'required'
            );
            $validation = array_merge($validation, $bank_validation);
        }
        $this->form_validation->validate($validation);
        $result = $this->kas_bank_m->insert($post);
        $rs_kas_bank_cabang = array();
        foreach ($post['cabang'] as $cabang) {
            $rs_kas_bank_cabang[] = array(
                'id_kas_bank' => $result->id,
                'id_cabang' => $cabang
            );
        }
        $this->kas_bank_cabang_m->insert_batch($rs_kas_bank_cabang);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('kas_bank')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kas_bank')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->kas_bank_m->find_or_fail($id);
        $rs_cabang = $this->kas_bank_cabang_m->where('id_kas_bank', $model->id)->get();
        foreach ($rs_cabang as $r_cabang) {
            $model->cabang[] = $r_cabang->id_cabang;
        }
        $this->load->view('master/kas_bank/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $validation = array(
            'nama' => 'required',
            'jenis_kas_bank' => 'required',
            'cabang[]' => 'required'
        );

        if ($post['jenis_kas_bank'] == 'bank') {
            $bank_validation = array(
                'id_bank' => 'required',
                'nomor_rekening' => 'required',
                'nama_rekening' => 'required'
            );
            $validation = array_merge($validation, $bank_validation);
        }
        $this->form_validation->validate($validation);
        $result = $this->kas_bank_m->update($id, $post);
        $this->kas_bank_cabang_m->where('id_kas_bank', $id)->delete();
        $rs_kas_bank_cabang = array();
        foreach ($post['cabang'] as $cabang) {
            $rs_kas_bank_cabang[] = array(
                'id_kas_bank' => $id,
                'id_cabang' => $cabang
            );
        }
        $this->kas_bank_cabang_m->insert_batch($rs_kas_bank_cabang);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('kas_bank')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('kas_bank')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $this->kas_bank_cabang_m->where('id_kas_bank', $id)->delete();
        $result = $this->kas_bank_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('kas_bank')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('kas_bank')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
