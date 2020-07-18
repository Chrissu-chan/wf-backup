<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('cabang_m');
        $this->load->model('diskon_m');
        $this->load->model('diskon_cabang_m');
        $this->load->model('diskon_kondisi_m');
        $this->load->model('views/view_kategori_m');
        $this->load->model('views/view_produk_m');
        $this->load->model('jenis_obat_m');
        $this->load->model('produk_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->diskon_m)
                ->edit_column('diskon', function($model) {
                    return $model->diskon.'%';
                })
                ->edit_column('potongan', function($model) {
                    return $this->localization->number($model->potongan);
                })
                ->edit_column('tanggal_mulai', function($model) {
                    return $this->localization->human_date($model->tanggal_mulai);
                })
                ->edit_column('tanggal_selesai', function($model) {
                    return $this->localization->human_date($model->tanggal_selesai);
                })
                ->edit_column('aktif', function($model) {
                    if ($model->aktif == 0) {
                        $label = '<button type="button" class="btn btn-success btn-xs" onclick="start('.$model->id.')"><i class="fa fa-play"></i></button> <label class="label label-danger">'. $this->diskon_m->enum('aktif', $model->aktif).'</label>';
                    } else {
                        $label = '<button type="button" class="btn btn-danger btn-xs" onclick="stop('.$model->id.')"><i class="fa fa-pause"></i></button> <label class="label label-success">'.$this->diskon_m->enum('aktif', $model->aktif).'</label>';
                    }
                    return $label;
                })
                ->add_action('{view} {edit} {delete}', array(
                    'edit' => function($model) {
                        return $this->action->link('edit', $this->route->name('produk.diskon.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
                    }
                ))
                ->generate();
        }
        $this->load->view('produk/diskon/index');
    }
    public function view($id) {
        $model = $this->diskon_m->find_or_fail($id);
	    $model->diskon_kondisi = array();
	    foreach ($this->diskon_kondisi_m->where('id_diskon', $id)->get() as $diskon_kondisi) {
		    if ($diskon_kondisi->column == 'kode_produk' || $diskon_kondisi->column == 'harga') {
			    $from = $diskon_kondisi->from;
		    } else {
			    $from = array();
			    $keys = explode(',', $diskon_kondisi->from);
		        if ($diskon_kondisi->column == 'jenis_produk') {
			        foreach ($keys as $key) {
				        $from[] = $this->produk_m->enum('jenis', $key);
			        }
			    } else if ($diskon_kondisi->column == 'kategori') {
				    foreach ($keys as $key) {
					    $from[] = $this->view_kategori_m->find_or_fail($key)->kategori;
				    }
			    } else if ($diskon_kondisi->column == 'jenis') {
				    foreach ($keys as $key) {
					    $from[] = $this->jenis_obat_m->find_or_fail($key)->jenis_obat;
				    }
			    }
		    }

		    $model->diskon_kondisi[] = array(
			    'column' => $diskon_kondisi->column,
			    'operation' => $diskon_kondisi->operation,
			    'from' => $from,
			    'to' => $diskon_kondisi->to
		    );
	    }
        $this->load->view('produk/diskon/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('produk/diskon/create');
    }


    public function store() {
        $this->form_validation->validate(array(
            'diskon' => 'required|numeric',
            'potongan' => 'required|numeric',
            'tanggal_mulai' => 'required'
        ));
        $post = $this->input->post();
        if (!isset($post['diskon_cabang'])) {
            $post['diskon_cabang'][] = 0;
        }
        $this->transaction->start();
            $result = $this->diskon_m->insert($post);
            $record_diskon_cabang = array();
            foreach ($post['diskon_cabang'] as $id_cabang) {
                $record_diskon_cabang[] = array(
                    'id_diskon' => $result->id,
                    'id_cabang' => $id_cabang
                );
            }
            if ($record_diskon_cabang) {
                $this->diskon_cabang_m->insert_batch($record_diskon_cabang);
            }

            if (isset($post['diskon_kondisi'])) {
                $record_diskon_kondisi = array();
                foreach ($post['diskon_kondisi'] as $column => $kondisi) {
                    if ($column) {
	                    if ($column == 'harga') {
		                    $kondisi['from'] = $this->localization->number_value($kondisi['from']);
		                    if ($kondisi['to']) {
			                    $kondisi['to'] = $this->localization->number_value($kondisi['to']);
		                    }
	                    }
                        $record_diskon_kondisi[] = array(
                            'id_diskon' => $result->id,
                            'column' => $column,
                            'operation' => $kondisi['operation'],
                            'from' => (is_array($kondisi['from'])) ? implode(',', $kondisi['from']) : $kondisi['from'],
                            'to' => $kondisi['to']
                        );
                    }
                }
                if ($record_diskon_kondisi) {
                    $this->diskon_kondisi_m->insert_batch($record_diskon_kondisi);
                }
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produk'))))->route('produk.diskon');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('diskon'))))->back();
        }
    }

    public function edit($id) {
        $model = $this->diskon_m->find_or_fail($id);
        $model->diskon_cabang = array();
        foreach ($this->diskon_cabang_m->where('id_diskon', $id)->get() as $diskon_cabang) {
            $model->diskon_cabang[] = $diskon_cabang->id_cabang;
        }
        $model->diskon_kondisi = array();
        foreach ($this->diskon_kondisi_m->where('id_diskon', $id)->get() as $diskon_kondisi) {
            if ($diskon_kondisi->column == 'kode_produk' || $diskon_kondisi->column == 'harga') {
                $model->diskon_kondisi[$diskon_kondisi->column]['from'] = $diskon_kondisi->from;
            } else {
                $model->diskon_kondisi[$diskon_kondisi->column]['from'] = explode(',', $diskon_kondisi->from);
            }
            $model->diskon_kondisi[$diskon_kondisi->column]['operation'] = $diskon_kondisi->operation;
            $model->diskon_kondisi[$diskon_kondisi->column]['to'] = $diskon_kondisi->to;
        }
        $this->load->view('produk/diskon/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $this->form_validation->validate(array(
            'diskon' => 'required|numeric',
            'potongan' => 'required|numeric',
            'tanggal_mulai' => 'required'
        ));
        $post = $this->input->post();
        if (!isset($post['diskon_cabang'])) {
            $post['diskon_cabang'][] = 0;
        }
        $this->transaction->start();
            $this->diskon_m->update($id, $post);
            $this->diskon_cabang_m->where('id_diskon', $id)->delete();
            $record_diskon_cabang = array();
            foreach ($post['diskon_cabang'] as $id_cabang) {
                $record_diskon_cabang[] = array(
                    'id_diskon' => $id,
                    'id_cabang' => $id_cabang
                );
            }
            if ($record_diskon_cabang) {
                $this->diskon_cabang_m->insert_batch($record_diskon_cabang);
            }

            $this->diskon_kondisi_m->where('id_diskon', $id)->delete();
            if (isset($post['diskon_kondisi'])) {
                $record_diskon_kondisi = array();
                foreach ($post['diskon_kondisi'] as $column => $kondisi) {
                    if ($column) {
	                    if ($column == 'harga') {
		                    $kondisi['from'] = $this->localization->number_value($kondisi['from']);
		                    if ($kondisi['to']) {
			                    $kondisi['to'] = $this->localization->number_value($kondisi['to']);
		                    }
	                    }
                        $record_diskon_kondisi[] = array(
                            'id_diskon' => $id,
                            'column' => $column,
                            'operation' => $kondisi['operation'],
                            'from' => (is_array($kondisi['from'])) ? implode(',', $kondisi['from']) : $kondisi['from'],
                            'to' => $kondisi['to']
                        );
                    }
                }
                if ($record_diskon_kondisi) {
                    $this->diskon_kondisi_m->insert_batch($record_diskon_kondisi);
                }
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('produk'))))->route('produk.diskon');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('diskon'))))->back();
        }
    }

    public function delete($id) {
        $this->transaction->start();
            $this->diskon_m->delete($id);
            $this->diskon_cabang_m->where('id_diskon', $id)->delete();
            $this->diskon_kondisi_m->where('id_diskon', $id)->delete();
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('diskon')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('diskon')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function start($id) {
        $result = $this->diskon_m->update($id, array('aktif' => 1));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('diskon')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('diskon')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function stop($id) {
        $result = $this->diskon_m->update($id, array('aktif' => 0));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('diskon')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('diskon')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_json() {
        $id_cabang = $this->session->userdata('cabang')->id;
        $id_produk = $this->input->get('id_produk');
        $id_satuan = $this->input->get('id_satuan');
        $result_diskon = $this->diskon_m->scope('active')->get();
        if ($result_diskon) {
            foreach ($result_diskon as $i => $diskon) {
                $result_diskon_kondisi = $this->diskon_kondisi_m->where('id_diskon', $diskon->id)->get();
                $result_diskon[$i]->kondisi = $result_diskon_kondisi;
            }
            foreach ($result_diskon as $diskon) {
                $this->db->group_start();
                foreach ($diskon->kondisi as $diskon_kondisi) {
                    if ($diskon_kondisi->operation == '=' && is_null($diskon_kondisi->to)) {
                        $values = explode(',', $diskon_kondisi->from);
                        if (is_array($values)) {
                            $this->db->group_start();
                            $this->db->like($diskon_kondisi->column, $values[0], 'none', false);
                            unset($values[0]);
                            foreach ($values as $value) {
                                $this->db->or_like($diskon_kondisi->column, $value, 'none', false);
                            }
                            $this->db->group_end();
                        } else {
                            $this->db->like($diskon_kondisi->column, $diskon_kondisi->from, 'none', false);
                        }
                    } elseif ($diskon_kondisi->operation == '=' && $diskon_kondisi->to) {
                        $this->db->group_start()
                            ->where($diskon_kondisi->column.' >= ', $diskon_kondisi->from)
                            ->where($diskon_kondisi->column.' <= ', $diskon_kondisi->to)
                            ->group_end();
                    } else {
                        $this->db->where($diskon_kondisi->column.' '.$diskon_kondisi->operation.' ', $diskon_kondisi->from);
                    }
                }
                $this->db->group_end();
                $this->db->group_start()
                    ->where('id_cabang', $id_cabang)
                    ->or_where('id_cabang', 0)
                    ->group_end();
                $result_produk = $this->view_produk_m->where('id_produk', $id_produk)
                    ->where('id_satuan', $id_satuan)
                    ->order_by('id_cabang', 'DESC')
                    ->first();
                if ($result_produk) {
                    $response = array(
                        'success' => TRUE,
                        'data' => $diskon
                    );
                    break;
                } else {
                    $response = array(
                        'success' => FALSE,
                        'data' => NULL
                    );
                }
            }
        } else {
            $response = array(
                'success' => FALSE,
                'data' => NULL
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
