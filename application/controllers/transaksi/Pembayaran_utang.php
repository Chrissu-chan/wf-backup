<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran_utang extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('utang_m');
        $this->load->model('pembayaran_utang_m');
        $this->load->model('kas_bank_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Pembayaran Utang";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->pembayaran_utang_m)
                ->view('utang')
                ->edit_column('tanggal_bayar', function ($model) {
                    return $this->localization->human_date($model->tanggal_bayar);
                })
                ->edit_column('jumlah_bayar', function ($model) {
                    return $this->localization->number($model->jumlah_bayar);
                })
                ->edit_column('batal', function ($model) {
                    return $this->localization->boolean($model->batal, '<span class="label label-danger">' . ($model->jenis_batal ? $this->pembayaran_utang_m->enum('jenis_batal', $model->jenis_batal) : '') . '</span>', '<span class="label label-success">' . $this->localization->lang('approved') . '</span>');
                })
                ->add_action('{upload_file} {view} {delete}', array(
                    'upload_file' => function ($model) {
                        return $this->action->button('create', 'class="btn btn-primary btn-sm" onclick="upload(' . $model->id . ')"', $this->localization->lang('upload'));
                    },
                    'delete' => function ($model) {
                        $html = '';
                        if ($model->proses_jurnal == 'false' && $model->batal == 0) {
                            $html = $this->action->button('delete', 'onclick="remove(\'' . $model->id . '\')" class="btn btn-danger btn-sm"', $this->localization->lang('delete'));
                        }
                        return $html;
                    }
                ))
                ->generate();
        }
        $this->load->view('transaksi/pembayaran_utang/index', $data);
    }

    public function view($id)
    {
        $model = $this->pembayaran_utang_m->view('kas_bank')->find_or_fail($id);
        $this->load->view('transaksi/pembayaran_utang/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $title = "Pembayaran Utang";
        $id_utang = $this->input->get('id');
        $this->load->view('transaksi/pembayaran_utang/create', array(
            'id_utang' => $id_utang, 'title' => $title
        ));
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_utang' => 'required',
            'tanggal_bayar' => 'required',
            'jumlah_bayar' => 'required',
        ));
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
        }
        $post['id_kas_bank'] = $post['dibayarkan_dari'];
        $result = $this->pembayaran_utang_m->insert($post);
        $utang = $this->utang_m->find_or_fail($post['id_utang']);
        $post['sisa_utang'] = $utang->sisa_utang - $result->jumlah_bayar;
        if ($post['sisa_utang'] == 0) {
            $post['lunas'] = 1;
        }
        $post['jumlah_bayar'] = $utang->jumlah_bayar + $result->jumlah_bayar;
        $this->utang_m->update($post['id_utang'], $post);
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_save_message', array('name' => $this->localization->lang('pembayaran_utang'))))->route('transaksi.pembayaran_utang');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('failed_save_message', array('name' => $this->localization->lang('pembayaran_utang'))))->back();
        }
    }

    public function upload($id)
    {
        $this->load->view('transaksi/pembayaran_utang/upload', array(
            'id' => $id
        ));
    }

    public function upload_store()
    {
        $post = $this->input->post();
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
            $result = $this->pembayaran_utang_m->update($post['id'], $post);
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
        $result = $this->pembayaran_utang_m->find_or_fail($id);
        $path = './' . $this->config->item('document_upload_path') . '/' . $result->file;
        $data = file_get_contents($path);
        $name = 'document-' . $result->no_ref_pembayaran . '.' . end(explode('.', $result->file));
        return force_download($name, $data);
    }

    public function delete($id)
    {
        $this->transaction->start();
        $pembayaran = $this->pembayaran_utang_m->find_or_fail($id);
        $utang = $this->utang_m->find_or_fail($pembayaran->id_utang);
        $data['sisa_utang'] = $utang->sisa_utang + $pembayaran->jumlah_bayar;
        $data['jumlah_bayar'] = $utang->jumlah_bayar - $pembayaran->jumlah_bayar;
        if ($data['jumlah_bayar'] == $utang->jumlah_utang && $data['sisa_utang'] == 0) {
            $data['lunas'] = 1;
        } else {
            $data['lunas'] = 0;
        }
        $this->utang_m->update($pembayaran->id_utang, $data);
        $this->pembayaran_utang_m->update($id, array(
            'status' => 'deleted',
            'batal' => 1,
            'jenis_batal' => 'cancel',
            'deleted_by' => $this->auth->username,
            'deleted_at' => date('Y-m-d H:i:s')
        ));
        //$this->pembayaran_utang_m->delete($id);
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('pembayaran_utang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('pembayaran_utang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
