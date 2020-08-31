<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_transaksi extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('jenis_transaksi_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Jenis Transaksi";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->jenis_transaksi_m)
                ->add_column('tipe', function ($model) {
                    return $this->jenis_transaksi_m->enum('tipe', $model->tipe);
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/jenis_transaksi/index', $data);
    }

    public function view($id)
    {
        $model = $this->jenis_transaksi_m->find_or_fail($id);
        $this->load->view('master/jenis_transaksi/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/jenis_transaksi/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode_jenis_transaksi' => 'required|is_unique[jenis_transaksi.jenis_transaksi]',
            'jenis_transaksi' => 'required',
            'tipe' => 'required'
        ));
        $result = $this->jenis_transaksi_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->jenis_transaksi_m->find_or_fail($id);
        $this->load->view('master/jenis_transaksi/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode_jenis_transaksi' => 'required|is_unique[jenis_transaksi.jenis_transaksi.' . $id . ']',
            'jenis_transaksi' => 'required',
            'tipe' => 'required'
        ));
        $result = $this->jenis_transaksi_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->jenis_transaksi_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jenis_transaksi')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
