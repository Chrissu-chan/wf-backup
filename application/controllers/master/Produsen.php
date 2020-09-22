<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produsen extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('produsen_m');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data["title"] = "Master Produsen";
		if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->produsen_m)
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/produsen/index',$data);
	}

	public function view($id)
    {
        $model = $this->produsen_m->find_or_fail($id);
        $this->load->view('master/produsen/view', array(
            'model' => $model
        ));
	}
	
	public function create()
    {
        $this->load->view('master/produsen/create');
	}
	
	public function edit($id)
    {
		$model = $this->produsen_m->find_or_fail($id);
        $this->load->view('master/produsen/edit', array(
            'model' => $model
        ));
	}
	
	public function store()
    {
		$post = $this->input->post();
        $this->form_validation->validate(array(
            'nama_produsen' => 'required'
        ));
        $result = $this->produsen_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produsen')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('produsen')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
	
	public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'nama_produsen' => 'required'
        ));
        $result = $this->produsen_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('produsen')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('produsen')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function delete($id)
    {
        $result = $this->produsen_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('produsen')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('produsen')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}