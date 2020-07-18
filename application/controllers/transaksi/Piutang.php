<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('piutang_m');
        $this->load->model('supplier_m');
        $this->load->model('kas_bank_m');
        $this->load->model('pembayaran_piutang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->piutang_m)
	            ->edit_column('jenis_piutang', function($model) {
	                return $this->piutang_m->enum('jenis_piutang', $model->jenis_piutang);
	            })
	            ->edit_column('tanggal_piutang', function($model) {
	                return $this->localization->human_date($model->tanggal_piutang);
	            })
	            ->edit_column('tanggal_jatuh_tempo', function($model) {
	                return $this->localization->human_date($model->tanggal_jatuh_tempo);
	            })
	            ->edit_column('jumlah_piutang', function($model) {
	                return $this->localization->number($model->jumlah_piutang);
	            })
	            ->edit_column('sisa_piutang', function($model) {
	                return $this->localization->number($model->sisa_piutang);
	            })
	            ->edit_column('lunas', function($model) {
		            return $this->localization->boolean($model->lunas);
	            })
	            ->add_action('<div class="btn-group">
	                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                '.$this->localization->lang('action').' <span class="caret"></span>
	                </button>
	                <ul class="dropdown-menu dropdown-menu-right">
	                    {upload_file}
	                    {lunas}
	                    {view}
	                    {bayar}
	                    {delete}
	                </ul>
	            </div>', array(
	                'upload_file' => function($model) {
	                    return '<li>' . $this->action->link('create', 'javascript:void(0)', 'onclick="upload('.$model->id.')"', $this->localization->lang('upload_file')) . '</li>';
	                },
	                'lunas' => function($model) {
	                    if(!$model->lunas) {
	                        return '<li>' . $this->action->link('payment', 'javascript:void(0)', 'onclick="pelunasan('.$model->id.')"', $this->localization->lang('pelunasan')) . '</li>';
	                    }

	                    if($model->lunas && $model->sisa_piutang > 0) {
	                        return '<li>' . $this->action->link('payment', 'javascript:void(0)', 'onclick="batal_pelunasan('.$model->id.')"', $this->localization->lang('batal_pelunasan')) . '</li>';
	                    }
	                },
	                'view' => function($model) {
	                    return '<li>' . $this->action->link('view',  $this->url_generator->current_url().'/view/'.$model->id) . '</li>';
	                },
	                'bayar' => function($model) {
	                    if(!$model->lunas) {
	                        return '<li>' . $this->action->link('payment', $this->route->name('transaksi.pembayaran_piutang.create', array('id' => $model->id)), NULL, $this->localization->lang('bayar')) . '</li>';
	                    }
	                },
	                'delete' => function($model) {
	                    return '<li>' . $this->action->link('delete', 'javascript:void(0)', 'onclick="remove('.$model->id.')"') . '</li>';
	                }
	            ))
	            ->generate();
        }
        $this->load->view('transaksi/piutang/index');
    }

    public function view($id) {
        $pembayaran_piutang = $this->pembayaran_piutang_m->view('kas_bank')
        ->where('id_piutang', $id)->get();
        $model = $this->piutang_m->find_or_fail($id);
        $this->load->view('transaksi/piutang/view', array(
            'pembayaran_piutang' => $pembayaran_piutang,
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('transaksi/piutang/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'no_piutang' => 'required',
            'jenis_piutang' => 'required', 
            'no_refrensi' => 'required',
            'nama' => 'required',
            'tanggal_piutang' => 'required',
            'tanggal_jatuh_tempo' => 'required',
            'jumlah_piutang' => 'required',
        ));
        $post['jumlah_bayar'] = 0;
        $post['sisa_piutang'] = $post['jumlah_piutang'];
        $post['lunas'] = 0;
        if(file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
        }
        $result = $this->piutang_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('piutang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('piutang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $pembayaran = $this->pembayaran_piutang_m->where('id_piutang', $id)->delete();
        $result = $this->piutang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('piutang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('piutang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function upload($id) {
        $this->load->view('transaksi/piutang/upload', array(
            'id' => $id
        ));
    }

    public function upload_store() {
        $post = $this->input->post();
        if(file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
	        if (isset($upload['file_name'])) {
		        $post['file'] = $upload['file_name'];
		        $result = $this->piutang_m->update($post['id'], $post);
		        $this->redirect->with('success_message', $this->localization->lang('success_upload_message', array('name' => $this->localization->lang('piutang'))))->back();
	        } else {
		        $this->redirect->with('error_message', $upload)->back();
	        }
        }
    }

    private function upload_file() {
        $config['upload_path'] = './'.$this->config->item('document_upload_path');
        $config['allowed_types'] = $this->config->item('document_allowed_file_types');
        $config['encrypt_name'] = true;
        $config['max_size'] = $this->config->item('document_max_size');
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('file_path')) {
	        return $this->upload->display_errors();
        } else {
            return $this->upload->data();
        }
    }

    public function download_file($id) {
        $this->load->helper('download');
        $result = $this->piutang_m->find_or_fail($id);
        $path = './'.$this->config->item('document_upload_path').'/'.$result->file;
        $data = file_get_contents($path);
        $name = 'document-'. $result->no_piutang . '.'.end(explode('.', $result->file));
        return force_download($name, $data);
    }

    public function find_json($id) {
        $result = $this->piutang_m->find_or_fail($id);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function pelunasan($id) {
        $result = $this->piutang_m->where('id', $id)->update(array('lunas' => 1));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_pelunasan_message', array('name' => $this->localization->lang('piutang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_pelunasan_message', array('name' => $this->localization->lang('piutang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function batal_pelunasan($id) {
        $result = $this->piutang_m->where('id', $id)->update(array('lunas' => 0));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_batal_pelunasan_message', array('name' => $this->localization->lang('piutang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_batal_pelunasan_message', array('name' => $this->localization->lang('piutang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}