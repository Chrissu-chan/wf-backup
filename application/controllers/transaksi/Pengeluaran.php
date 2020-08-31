<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mutasi_kas_bank_m');
        $this->load->model('jenis_transaksi_m');
        $this->load->model('kas_bank_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Pengeluaran";
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');
            $tanggal_awal = date('Y-m-d', strtotime($this->input->get('tanggal_awal')));
            $tanggal_akhir = date('Y-m-d', strtotime($this->input->get('tanggal_akhir')));
            $this->load->library('datatable');
            return $this->datatable->resource($this->mutasi_kas_bank_m)
                ->view('pengeluaran')
                ->where('mutasi_kas_bank.tipe', 'pengeluaran')
                ->where('status', $status)
                ->where('tanggal_mutasi >= ', $tanggal_awal)
                ->where('tanggal_mutasi <= ', $tanggal_akhir)
                ->edit_column('tanggal_mutasi', function ($model) {
                    return $this->localization->human_date($model->tanggal_mutasi);
                })
                ->edit_column('status', function ($model) {
                    if ($model->status == 'approved') {
                        return '<span class="label label-success">' . $this->localization->lang('approved') . '</span>';
                    } else {
                        return '<span class="label label-warning">' . $this->localization->lang('waiting_approval') . '</span>';
                    }
                })
                ->add_action('<div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ' . $this->localization->lang('action') . ' <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    {upload_file}
                    {view}
                    {edit}
                    {delete}
                </ul>
            </div>', array(
                    'upload_file' => function ($model) {
                        return '<li>' . $this->action->link('upload_file', 'javascript:void(0)', 'onclick="upload(' . $model->id . ')"') . '</li>';
                    },
                    'view' => function ($model) {
                        return '<li>' . $this->action->link('view', 'javascript:void(0)', 'onclick="view(' . $model->id . ')"') . '</li>';
                    },
                    'edit' => function ($model) {
                        if ($model->status == 'approved') {
                            return '';
                        } else {
                            return '<li>' . $this->action->link('edit', 'javascript:void(0)', 'onclick="edit(' . $model->id . ')"') . '</li>';
                        }
                    },
                    'delete' => function ($model) {
                        if ($model->status == 'approved') {
                            return '';
                        } else {
                            return '<li>' . $this->action->link('delete', 'javascript:void(0)', 'onclick="remove(' . $model->id . ')"') . '</li>';
                        }
                    }
                ))
                ->generate();
        }
        $this->load->view('transaksi/pengeluaran/index', $data);
    }

    public function view($id)
    {
        $model = $this->mutasi_kas_bank_m->view('pengeluaran')->find_or_fail($id);
        $this->load->view('transaksi/pengeluaran/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('transaksi/pengeluaran/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'no_mutasi' => 'required|is_unique[mutasi_kas_bank.no_mutasi]',
            'no_referensi' => 'required',
            'tanggal_mutasi' => 'required|date',
            'dari' => 'required',
            'ke' => 'required',
            'nominal' => 'required'
        ));
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
        }
        $result = $this->mutasi_kas_bank_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->mutasi_kas_bank_m->find_or_fail($id);
        $this->load->view('transaksi/pengeluaran/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'no_mutasi' => 'required|is_unique[mutasi_kas_bank.no_mutasi.' . $id . ']',
            'no_referensi' => 'required',
            'tanggal_mutasi' => 'required|date',
            'dari' => 'required',
            'ke' => 'required',
            'nominal' => 'required'
        ));
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
        }
        $result = $this->mutasi_kas_bank_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->mutasi_kas_bank_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('pengeluaran')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function appr()
    {
        $post = $this->input->post('id');
        foreach ($post as $id) {
            $result = $this->mutasi_kas_bank_m->appr($id);
        }
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_approve_message', array('name' => $this->localization->lang('pengeluaran'))))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_approve_message', array('name' => $this->localization->lang('pengeluaran'))))->back();
        }
    }

    public function cancel_appr()
    {
        $post = $this->input->post('id');
        foreach ($post as $id) {
            $result = $this->mutasi_kas_bank_m->cancel_appr($id);
        }
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_cancel_approve_message', array('name' => $this->localization->lang('pengeluaran'))))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_cancel_approve_message', array('name' => $this->localization->lang('pengeluaran'))))->back();
        }
    }

    public function upload($id)
    {
        $this->load->view('transaksi/pengeluaran/upload', array(
            'id' => $id
        ));
    }

    public function upload_store()
    {
        $post = $this->input->post();
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
            $result = $this->mutasi_kas_bank_m->update($post['id'], $post);
            if ($result) {
                $this->redirect->with('success_message', $this->localization->lang('upload_success'))->back();
            }
        }
    }

    private function upload_file()
    {
        $config['upload_path'] = './' . $this->config->item('document_upload_path');
        $config['allowed_types'] = $this->config->item('document_allowed_file_types');
        $config['encrypt_name'] = true;
        $config['max_size'] = $this->config->item('document_max_size');
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_path')) {
            $response = array(
                'success' => false,
                'message' => $this->upload->display_errors()
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
            exit();
        } else {
            return $this->upload->data();
        }
    }

    public function download_file($id)
    {
        $this->load->helper('download');
        $result = $this->mutasi_kas_bank_m->find_or_fail($id);
        $path = './' . $this->config->item('document_upload_path') . '/' . $result->file;
        $data = file_get_contents($path);
        $name = 'document-' . $result->no_mutasi . '.' . end(explode('.', $result->file));
        return force_download($name, $data);
    }
}
