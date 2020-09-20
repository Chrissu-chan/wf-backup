<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utang extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('utang_m');
        $this->load->model('supplier_m');
        $this->load->model('kas_bank_m');
        $this->load->model('pembayaran_utang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Utang";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->utang_m)
                ->edit_column('jenis_utang', function ($model) {
                    return $this->utang_m->enum('jenis_utang', $model->jenis_utang);
                })
                ->edit_column('tanggal_utang', function ($model) {
                    return $this->localization->human_date($model->tanggal_utang);
                })
                ->edit_column('tanggal_jatuh_tempo', function ($model) {
                    return $this->localization->human_date($model->tanggal_jatuh_tempo);
                })
                ->edit_column('jumlah_utang', function ($model) {
                    return $this->localization->number($model->jumlah_utang);
                })
                ->edit_column('sisa_utang', function ($model) {
                    return $this->localization->number($model->sisa_utang);
                })
                ->edit_column('lunas', function ($model) {
                    return $this->localization->boolean($model->lunas);
                })
                ->add_action(
                    '
					<div class="btn-group">
		                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $this->localization->lang('action') . ' <span class="caret"></span></button>
		                <ul class="dropdown-menu dropdown-menu-right">
		                    {upload_file}
		                    {pelunasan}
		                    {view}
		                    {bayar}
		                    {delete}
		                </ul>
		            </div>',
                    array(
                        'upload_file' => function ($model) {
                            return '<li>' . $this->action->link('create', 'javascript:void(0)', 'onclick="upload(' . $model->id . ')"', $this->localization->lang('upload_file')) . '</li>';
                        },
                        'pelunasan' => function ($model) {
                            if (!$model->lunas) {
                                return '<li>' . $this->action->link('payment', 'javascript:void(0)', 'onclick="pelunasan(' . $model->id . ')"', $this->localization->lang('pelunasan')) . '</li>';
                            }

                            if ($model->lunas && $model->sisa_utang > 0) {
                                return '<li>' . $this->action->link('payment', 'javascript:void(0)', 'onclick="batal_pelunasan(' . $model->id . ')"', $this->localization->lang('batal_pelunasan')) . '</li>';
                            }
                        },
                        'view' => function ($model) {
                            return '<li>' . $this->action->link('view',  $this->url_generator->current_url() . '/view/' . $model->id) . '</li>';
                        },
                        'bayar' => function ($model) {
                            if (!$model->lunas) {
                                return '<li>' . $this->action->link('payment', $this->route->name('transaksi.pembayaran_utang.create', array('id' => $model->id)), NULL, $this->localization->lang('bayar')) . '</li>';
                            }
                        },
                        'delete' => function ($model) {
                            return '<li>' . $this->action->link('delete', 'javascript:void(0)', 'onclick="remove(' . $model->id . ')"') . '</li>';
                        }
                    )
                )
                ->generate();
        }
        $this->load->view('transaksi/utang/index', $data);
    }

    public function view($id)
    {
        $title = "Utang";
        $pembayaran_utang = $this->pembayaran_utang_m->view('kas_bank')
            ->where('id_utang', $id)->get();
        $model = $this->utang_m->find_or_fail($id);
        $this->load->view('transaksi/utang/view', array(
            'pembayaran_utang' => $pembayaran_utang,
            'model' => $model, 'title' => $title
        ));
    }

    public function create()
    {
        $this->load->view('transaksi/utang/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'no_utang' => 'required',
            'jenis_utang' => 'required',
            'no_refrensi' => 'required',
            'nama' => 'required',
            'tanggal_utang' => 'required',
            'tanggal_jatuh_tempo' => 'required',
            'jumlah_utang' => 'required',
        ));
        $post['jumlah_bayar'] = 0;
        $post['sisa_utang'] = $post['jumlah_utang'];
        $post['lunas'] = 0;
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
        }
        $result = $this->utang_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('utang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('utang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $pembayaran = $this->pembayaran_utang_m->where('id_utang', $id)->delete();
        $result = $this->utang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('utang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('utang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function upload($id)
    {
        $this->load->view('transaksi/utang/upload', array(
            'id' => $id
        ));
    }

    public function upload_store()
    {
        $post = $this->input->post();
        if (file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            if (isset($upload['file_name'])) {
                $post['file'] = $upload['file_name'];
                $result = $this->utang_m->update($post['id'], $post);
                $this->redirect->with('success_message', $this->localization->lang('success_upload_message', array('name' => $this->localization->lang('utang'))))->back();
            } else {
                $this->redirect->with('error_message', $upload)->back();
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
            return $this->upload->display_errors();
        } else {
            return $this->upload->data();
        }
    }

    public function download_file($id)
    {
        $this->load->helper('download');
        $result = $this->utang_m->find_or_fail($id);
        $path = './' . $this->config->item('document_upload_path') . '/' . $result->file;
        $data = file_get_contents($path);
        $name = 'document-' . $result->no_utang . '.' . end(explode('.', $result->file));
        return force_download($name, $data);
    }

    public function find_json($id)
    {
        $result = $this->utang_m->find_or_fail($id);
        $result->tanggal_utang = date('d-m-Y', strtotime($result->tanggal_utang));
        $result->tanggal_jatuh_tempo = date('d-m-Y', strtotime($result->tanggal_jatuh_tempo));
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function pelunasan($id)
    {
        $result = $this->utang_m->where('id', $id)->update(array('lunas' => 1));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_pelunasan_message', array('name' => $this->localization->lang('utang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_pelunasan_message', array('name' => $this->localization->lang('utang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function batal_pelunasan($id)
    {
        $result = $this->utang_m->where('id', $id)->update(array('lunas' => 0));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_batal_pelunasan_message', array('name' => $this->localization->lang('utang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_batal_pelunasan_message', array('name' => $this->localization->lang('utang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
