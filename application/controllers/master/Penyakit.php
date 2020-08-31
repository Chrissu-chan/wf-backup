<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyakit extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('penyakit_m');
        $this->load->model('jenis_penyakit_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Penyakit";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->penyakit_m)
                ->view('jenis_penyakit')
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/penyakit/index', $data);
    }

    public function view($id)
    {
        $model = $this->penyakit_m->view('jenis_penyakit')->find_or_fail($id);
        $this->load->view('master/penyakit/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/penyakit/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode_penyakit' => 'required|is_unique[penyakit.kode_penyakit]',
            'id_jenis_penyakit' => 'required',
            'penyakit' => 'required'
        ));
        $result = $this->penyakit_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('penyakit')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_store_message', array('name' => $this->localization->lang('penyakit')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->penyakit_m->find_or_fail($id);
        $this->load->view('master/penyakit/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode_penyakit' => 'required|is_unique[penyakit.kode_penyakit.' . $id . ']',
            'id_jenis_penyakit' => 'required',
            'penyakit' => 'required'
        ));
        $result = $this->penyakit_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('penyakit')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_update_message', array('name' => $this->localization->lang('penyakit')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->penyakit_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('penyakit')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('failed_delete_message', array('name' => $this->localization->lang('penyakit')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function json()
    {
        $q = $this->input->get('q');
        $result = $this->penyakit_m->like('penyakit', $q)->get();
        $response = array(
            'message' => 'success',
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
