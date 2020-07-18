<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_kasir extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('mutasi_kasir_m');
        $this->load->model('jenis_transaksi_m');
        $this->load->model('shift_aktif_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            if ($tipe = $this->input->get('tipe')) {
                $this->db->where('mutasi_kasir.tipe', $tipe);
            }
            if ($tanggal_awal = $this->input->get('tanggal_awal')) {
                $this->db->where('tanggal_mutasi >= ', date('Y-m-d', strtotime($tanggal_awal)));
            }
            if ($tanggal_akhir = $this->input->get('tanggal_akhir')) {
                $this->db->where('tanggal_mutasi <= ', date('Y-m-d', strtotime($tanggal_akhir)));
            }
            $this->load->library('datatable');
            return $this->datatable->resource($this->mutasi_kasir_m)
                ->view('mutasi_kasir')
                ->scope('shift_aktif_kasir')
                ->edit_column('tipe', function($model) {
                    return $this->mutasi_kasir_m->enum('tipe', $model->tipe);
                })
                ->edit_column('tanggal_mutasi', function($model) {
                    return $this->localization->human_date($model->tanggal_mutasi);
                })
                ->edit_column('nominal', function($model) {
                    return $this->localization->number($model->nominal);
                })
	            ->edit_column('batal', function($model){
		            return $this->localization->boolean($model->batal, '<span class="label label-danger">'.($model->jenis_batal ? $this->mutasi_kasir_m->enum('jenis_batal', $model->jenis_batal) : '').'</span>', '<span class="label label-success">'.$this->localization->lang('approved').'</span>');
	            })
	            ->add_action('{upload} {view} {edit} {delete}', array(
		            'upload' => function($model) {
			            return $this->action->button('upload', 'onclick="upload(\''.$model->id.'\')" class="btn btn-primary btn-sm"', $this->localization->lang('upload'));
		            },
		            'edit' => function($model) {
			            $html = '';
			            if ($model->proses_jurnal == 'false' && $model->batal == 0) {
				            $html = $this->action->button('edit', 'onclick="edit(\''.$model->id.'\')" class="btn btn-warning btn-sm"', $this->localization->lang('edit'));
			            }
			            return $html;
		            },
		            'delete' => function($model) {
			            $html = '';
			            if ($model->proses_jurnal == 'false' && $model->batal == 0) {
				            $html = $this->action->button('delete', 'onclick="remove(\''.$model->id.'\')" class="btn btn-danger btn-sm"', $this->localization->lang('delete'));
			            }
			            return $html;
		            }
	            ))
                ->generate();
        }
        $this->load->view('transaksi/mutasi_kasir/index');
    }

    public function view($id) {
        $model = $this->mutasi_kasir_m->view('mutasi_kasir')->find_or_fail($id);
        $this->load->view('transaksi/mutasi_kasir/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('transaksi/mutasi_kasir/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'tipe' => 'required',
            'no_mutasi' => 'required|is_unique[mutasi_kasir.no_mutasi]',
            'tanggal_mutasi' => 'required|date',
            'id_jenis_transaksi' => 'required',
            'nominal' => 'required'
        ));
	    $this->transaction->start();
	        if(file_exists($_FILES['file_path']['tmp_name'])) {
	            $upload = $this->upload_file();
	            $post['file'] = $upload['file_name'];
	        }
		    $post['id_shift_aktif_kasir'] = $this->shift_aktif_m->view('shift_aktif')->scope('cabang')->scope('aktif')->first()->id_shift_aktif_kasir;
	        $this->mutasi_kasir_m->insert($post);
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->mutasi_kasir_m->find_or_fail($id);
        $this->load->view('transaksi/mutasi_kasir/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'tipe' => 'required',
            'no_mutasi' => 'required|is_unique[mutasi_kasir.no_mutasi.'.$id.']',
            'tanggal_mutasi' => 'required|date',
            'id_jenis_transaksi' => 'required',
            'nominal' => 'required'
        ));
	    $this->transaction->start();
	        if(file_exists($_FILES['file_path']['tmp_name'])) {
	            $upload = $this->upload_file();
	            $post['file'] = $upload['file_name'];
	        }
	        $post['id_shift_aktif_kasir'] = $this->shift_aktif_m->view('shift_aktif')->scope('cabang')->scope('aktif')->first()->id_shift_aktif_kasir;
	        $this->mutasi_kasir_m->update($id, $post);
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
	    $this->transaction->start();
            //$this->mutasi_kasir_m->delete($id);
		    $this->mutasi_kasir_m->update($id, array(
			    'status' => 'deleted',
			    'batal' => 1,
			    'jenis_batal' => 'cancel',
			    'deleted_by' => $this->auth->username,
			    'deleted_at' => date('Y-m-d H:i:s')
		    ));
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('mutasi_kasir')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function upload($id) {
        $this->load->view('transaksi/mutasi_kasir/upload', array(
            'id' => $id
        ));
    }

    public function upload_store() {
        $post = $this->input->post();
        if(file_exists($_FILES['file_path']['tmp_name'])) {
            $upload = $this->upload_file();
            $post['file'] = $upload['file_name'];
            $result = $this->mutasi_kasir_m->update($post['id'], $post);
            if($result) {
                $this->redirect->with('success_message', $this->localization->lang('upload_success'))->back();
            }
        } else {
	        $this->redirect->with('error_message', $this->localization->lang('error_upload_message', array('name' => $this->localization->lang('mutasi_kasir'))))->back();
        }
    }

    private function upload_file() {
        $config['upload_path'] = './'.$this->config->item('document_upload_path');
        $config['allowed_types'] = $this->config->item('document_allowed_file_types');
        $config['encrypt_name'] = true;
        $config['max_size'] = $this->config->item('document_max_size');
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file_path')) {
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

    public function download_file($id) {
        $this->load->helper('download');
        $result = $this->mutasi_kasir_m->find_or_fail($id);
        $path = './'.$this->config->item('document_upload_path').'/'.$result->file;
        $data = file_get_contents($path);
        $name = 'document-'. $result->no_mutasi . '.'.end(explode('.', $result->file));
        return force_download($name, $data);
    }
}