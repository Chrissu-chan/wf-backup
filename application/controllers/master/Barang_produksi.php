<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_produksi extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('barang_m');
        $this->load->model('barang_produksi_m');
        $this->load->model('barang_produksi_bahan_baku_m');
        $this->load->model('satuan_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->barang_produksi_m)
                ->view('barang_produksi')
                ->add_action('{view} {edit} {delete}', array(
                    'edit' => function($model) {
                        return $this->action->link('edit', $this->route->name('master.barang_produksi.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
                    }
                ))
                ->generate();
        }
        $this->load->view('master/barang_produksi/index');
    }

    public function view($id) {
        $model = $this->barang_produksi_m->view('barang_produksi')->find_or_fail($id);
        $model->bahan_baku = $this->barang_produksi_bahan_baku_m->view('bahan_baku')->where('id_barang_produksi', $id)->get();
        $this->load->view('master/barang_produksi/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/barang_produksi/create');
    }

    public function store() {
        $post = $this->input->post();
        $validate = array(
            'id_barang' => 'required',
            'nama' => 'required',
            'id_satuan' => 'required',
            'bahan_baku[]' => 'required'
        );

        foreach ($post['bahan_baku'] as $key => $val) {
            $validate['bahan_baku['.$key.'][id_satuan]'] = array(
                'field' => $this->localization->lang('bahan_baku_satuan', array('name' => $post['bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required'
            );
            $validate['bahan_baku['.$key.'][jumlah]'] = array(
                'field' => $this->localization->lang('bahan_baku_jumlah', array('name' => $post['bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required|numeric|greater_than[0]'
            );
        }
        $this->form_validation->validate($validate);

        $result = $this->barang_produksi_m->insert($post);
        if ($result) {
            $rs_bahan_baku = array();
            foreach ($post['bahan_baku'] as $bahan_baku) {
                $bahan_baku['id_barang_produksi'] = $result->id;
                $rs_bahan_baku[] = $bahan_baku;
            }
            $this->barang_produksi_bahan_baku_m->insert_batch($rs_bahan_baku);
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('barang_produksi'))))->route('master.barang_produksi');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('barang_produksi'))))->back();
        }
    }

    public function edit($id) {
        $model = $this->barang_produksi_m->view('barang_produksi')->find_or_fail($id);
        $rs_bahan_baku = $this->barang_produksi_bahan_baku_m->view('bahan_baku')->where('id_barang_produksi', $id)->get();
        foreach ($rs_bahan_baku as $bahan_baku) {
            $model->bahan_baku[$bahan_baku->id_barang] = $bahan_baku;
        }
        $this->load->view('master/barang_produksi/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $validate = array(
            'id_barang' => 'required',
            'nama' => 'required',
            'id_satuan' => 'required',
            'bahan_baku[]' => 'required'
        );

        foreach ($post['bahan_baku'] as $key => $val) {
            $validate['bahan_baku['.$key.'][id_satuan]'] = array(
                'field' => $this->localization->lang('bahan_baku_satuan', array('name' => $post['bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required'
            );
            $validate['bahan_baku['.$key.'][jumlah]'] = array(
                'field' => $this->localization->lang('bahan_baku_jumlah', array('name' => $post['bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required|numeric|greater_than[0]'
            );
        }
        $this->form_validation->validate($validate);

        $result = $this->barang_produksi_m->update($id, $post);
        if ($result) {
            $this->barang_produksi_bahan_baku_m->where('id_barang_produksi', $id)->delete();
            $rs_bahan_baku = array();
            foreach ($post['bahan_baku'] as $bahan_baku) {
                $bahan_baku['id_barang_produksi'] = $id;
                $rs_bahan_baku[] = $bahan_baku;
            }
            $this->barang_produksi_bahan_baku_m->insert_batch($rs_bahan_baku);
            $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('barang_produksi'))))->route('master.barang_produksi');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('barang_produksi'))))->back();
        }
    }

    public function delete($id) {
        $result = $this->barang_produksi_m->delete($id);
        if ($result) {
            $this->barang_produksi_bahan_baku_m->where('id_barang_produksi', $id)->delete();
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('barang_produksi')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('barang_produksi')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function browse() {
        if ($this->input->get('load')) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->barang_produksi_m)
                ->view('barang_produksi')
                ->generate();
        }
        $this->load->view('master/barang_produksi/browse');
    }

    public function get_bahan_baku_json($id) {
        $result = $this->barang_produksi_bahan_baku_m->view('bahan_baku')->where('id_barang_produksi', $id)->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}