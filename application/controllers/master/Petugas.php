<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Petugas extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('petugas_m');
        $this->load->model('cabang_m');
        $this->load->model('jenis_petugas_m');
        $this->load->model('petugas_cabang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Petugas";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->petugas_m)
                ->view('petugas')
                ->filter(function ($model) {
                    if ($cabang = $this->input->get('cabang')) {
                        $model->where('id_cabang', $cabang);
                    }
                    if ($jenis = $this->input->get('jenis')) {
                        $model->where('id_jenis_petugas', $jenis);
                    }
                })
                ->edit_column('jenis_petugas', function ($model) {
                    return $model->jenis_petugas;
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/petugas/index', $data);
    }

    public function view($id)
    {
        $model = $this->petugas_m->select('petugas.*')->view('petugas')->find_or_fail($id);
        $this->load->view('master/petugas/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/petugas/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'nama' => 'required',
            'id_jenis_petugas' => 'required'
        ));
        $result = $this->petugas_m->insert($post);
        $petugas_cabang = array(
            'id_petugas' => $result->id,
            'id_cabang' => $post['id_cabang']
        );
        $this->petugas_cabang_m->insert($petugas_cabang);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->petugas_m->view('petugas')->find_or_fail($id);
        $this->load->view('master/petugas/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'nama' => 'required',
            'id_jenis_petugas' => 'required'
        ));
        $result = $this->petugas_m->update($id, $post);
        $petugas_cabang = array(
            'id_petugas' => $id,
            'id_cabang' => $post['id_cabang']
        );
        $this->petugas_cabang_m->where('id_petugas', $id)->update($petugas_cabang);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $this->petugas_cabang_m->where('id_petugas', $id)->delete();
        $result = $this->petugas_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('petugas')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('petugas')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json()
    {
        if ($this->input->get()) {
            $this->petugas_m->where('id_cabang', $this->input->get('id_cabang'));
        }
        $result = $this->petugas_m->view('petugas')->get();

        $response = array(
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
