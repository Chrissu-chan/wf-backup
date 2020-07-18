<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konversi_satuan extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('satuan_m');
        $this->load->model('konversi_satuan_m');
        $this->load->library('form_validation');
    }

    public function index($id_satuan) {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->konversi_satuan_m)
                ->view('konversi_satuan')
                ->where('id_satuan_tujuan', $id_satuan)
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $satuan = $this->satuan_m->find_or_fail($id_satuan);
        $this->load->view('master/konversi_satuan/index', array(
            'satuan' => $satuan
        ));
    }

    public function view($id_satuan, $id) {
        $model = $this->konversi_satuan_m->view('konversi_satuan')->find_or_fail($id);
        $this->load->view('master/konversi_satuan/view', array(
            'model' => $model
        ));
    }

    public function create($id_satuan) {
        $satuan_asal = $this->satuan_m->find_or_fail($id_satuan);
        $this->load->view('master/konversi_satuan/create', array(
            'satuan_asal' => $satuan_asal
        ));
    }

    public function store($id_satuan) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_satuan_asal' => 'required',
            'id_satuan_tujuan' => 'required',
            'konversi' => 'required'
        ));
        $model = $this->konversi_satuan_m->where('id_satuan_asal', $id_satuan)
            ->where('id_satuan_tujuan', $post['id_satuan_tujuan'])
            ->first();
        if ($model) {
            $result = $this->konversi_satuan_m->update($model->id, $post);
        } else {
            $result = $this->konversi_satuan_m->insert($post);
        }
        if (isset($post['invers'])) {
            $rs_invers = array(
                'id_satuan_asal' => $post['id_satuan_tujuan'],
                'id_satuan_tujuan' => $post['id_satuan_asal'],
                'konversi' => 1 / $post['konversi']
            );
            $invers = $this->konversi_satuan_m->where('id_satuan_asal', $post['id_satuan_tujuan'])
                ->where('id_satuan_tujuan', $id_satuan)
                ->first();
            if ($model) {
                $result = $this->konversi_satuan_m->update($invers->id, $rs_invers);
            } else {
                $result = $this->konversi_satuan_m->insert($rs_invers);
            }
        }
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('konversi_satuan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('konversi_satuan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id_satuan, $id) {
        $model = $this->konversi_satuan_m->find_or_fail($id);
        $satuan_asal = $this->satuan_m->find_or_fail($id_satuan);

        $this->load->view('master/konversi_satuan/edit', array(
            'model' => $model,
            'satuan_asal' => $satuan_asal
        ));
    }

    public function delete($id_satuan, $id) {
        $result = $this->konversi_satuan_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('konversi_satuan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('konversi_satuan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}