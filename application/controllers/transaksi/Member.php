<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Member";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->member_m)
                ->add_action('{cetak_nota} {view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('transaksi/member/index', $data);
    }

    public function small_nota_print()
    {
        $this->load->view('transaksi/member/small_nota');
    }

    public function medium_nota_print()
    {
        $this->load->view('transaksi/member/medium_nota');
    }

    public function large_nota_print()
    {
        $this->load->view('transaksi/member/large_nota');
    }

    public function view($id)
    {
        $model = $this->member_m->find_or_fail($id);
        $this->load->view('transaksi/member/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('transaksi/member/create');
    }

    public function store()
    {
        $post = $this->input->post();
        //$this->form_validation->validate(array());
        $result = $this->member_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->member_m->find_or_fail($id);
        $this->load->view('transaksi/member/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        //$this->form_validation->validate(array());
        $result = $this->member_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->member_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
