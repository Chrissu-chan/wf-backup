<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapping_rak extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('rak_gudang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Pengaturan Rak";
        $this->load->view('inventory/mapping_rak/index', $data);
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_rak_gudang' => 'required',
            'id_barang[]' => 'required'
        ));
        $this->transaction->start();
        foreach ($post['id_barang'] as $id_barang) {
            $this->barang_m->update($id_barang, array('id_rak_gudang' => $post['id_rak_gudang']));
        }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('mapping_rak')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('mapping_rak')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
