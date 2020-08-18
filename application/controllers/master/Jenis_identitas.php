<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_identitas extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('jenis_identitas_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Jenis Identitas";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->jenis_identitas_m)
                ->add_action('{edit} {delete}')
                ->generate();
        }
        $this->load->view('master/jenis_identitas/index', $data);
    }

    public function view($id)
    {
        $model = $this->jenis_identitas_m->find_or_fail($id);
        $this->load->view('master/jenis_identitas/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/jenis_identitas/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_identitas' => 'required'
        ));
        $result = $this->jenis_identitas_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->jenis_identitas_m->find_or_fail($id);
        $this->load->view('master/jenis_identitas/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'jenis_identitas' => 'required'
        ));
        $result = $this->jenis_identitas_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->jenis_identitas_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jenis_identitas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
