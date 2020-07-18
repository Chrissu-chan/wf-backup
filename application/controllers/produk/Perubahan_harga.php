<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perubahan_harga extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('cabang_m');
        $this->load->model('perubahan_harga_m');
        $this->load->model('perubahan_harga_cabang_m');
        $this->load->model('perubahan_harga_kondisi_m');
        $this->load->model('views/view_kategori_m');
        $this->load->model('views/view_produk_m');
        $this->load->model('jenis_obat_m');
        $this->load->model('produk_m');
        $this->load->model('broadcast_harga_produk_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->perubahan_harga_m)
                ->edit_column('perubahan_harga', function($model) {
                    return $model->perubahan_harga.'%';
                })
                ->edit_column('tanggal_mulai', function($model) {
                    return $this->localization->human_date($model->tanggal_mulai);
                })
                ->edit_column('tanggal_selesai', function($model) {
                    return $this->localization->human_date($model->tanggal_selesai);
                })
                ->edit_column('aktif', function($model) {
	                $label = '';
                    if ($model->aktif == 0) {
	                    if (!$model->permanen) {
		                    $label = '<button type="button" class="btn btn-success btn-xs" onclick="start('.$model->id.')"><i class="fa fa-play"></i></button> ';
	                    }
	                    $label .= '<label class="label label-danger">'. $this->perubahan_harga_m->enum('aktif', $model->aktif).'</label>';
                    } else {
                        $label .= '<button type="button" class="btn btn-danger btn-xs" onclick="stop('.$model->id.')"><i class="fa fa-pause"></i></button> <label class="label label-success">'.$this->perubahan_harga_m->enum('aktif', $model->aktif).'</label>';
                    }
                    return $label;
                })
                ->add_action('{view} {edit} {delete}', array(
                    'edit' => function($model) {
	                    if (!$model->permanen) {
		                    return $this->action->link('edit', $this->route->name('produk.perubahan_harga.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
	                    }
                    }
                ))
                ->generate();
        }
        $this->load->view('produk/perubahan_harga/index');
    }
    public function view($id) {
        $model = $this->perubahan_harga_m->find_or_fail($id);
	    $model->diskon_kondisi = array();
	    foreach ($this->perubahan_harga_kondisi_m->where('id_perubahan_harga', $id)->get() as $perubahan_harga_kondisi) {
		    if ($perubahan_harga_kondisi->column == 'kode_produk' || $perubahan_harga_kondisi->column == 'harga') {
			    $from = $perubahan_harga_kondisi->from;
		    } else {
			    $from = array();
			    $keys = explode(',', $perubahan_harga_kondisi->from);
			    if ($perubahan_harga_kondisi->column == 'jenis_produk') {
				    foreach ($keys as $key) {
					    $from[] = $this->produk_m->enum('jenis', $key);
				    }
			    } else if ($perubahan_harga_kondisi->column == 'kategori') {
				    foreach ($keys as $key) {
					    $from[] = $this->view_kategori_m->find_or_fail($key)->kategori;
				    }
			    } else if ($perubahan_harga_kondisi->column == 'jenis') {
				    foreach ($keys as $key) {
					    $from[] = $this->jenis_obat_m->find_or_fail($key)->jenis_obat;
				    }
			    }
		    }

		    $model->perubahan_harga_kondisi[] = array(
			    'column' => $perubahan_harga_kondisi->column,
			    'operation' => $perubahan_harga_kondisi->operation,
			    'from' => $from,
			    'to' => $perubahan_harga_kondisi->to
		    );
	    }
        $this->load->view('produk/perubahan_harga/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('produk/perubahan_harga/create');
    }

    public function store() {
        $this->form_validation->validate(array(
            'perubahan_harga' => 'required|numeric',
            'tanggal_mulai' => 'required'
        ));
        $post = $this->input->post();
        if (!isset($post['perubahan_harga_cabang'])) {
            $post['perubahan_harga_cabang'][] = 0;
        }
        $this->transaction->start();
            $result = $this->perubahan_harga_m->insert($post);
            $record_perubahan_harga_cabang = array();
	        $perubahan_harga_cabang = array();
            foreach ($post['perubahan_harga_cabang'] as $id_cabang) {
	            $perubahan_harga_cabang[] = $id_cabang;
                $record_perubahan_harga_cabang[] = array(
                    'id_perubahan_harga' => $result->id,
                    'id_cabang' => $id_cabang
                );
            }
            if ($record_perubahan_harga_cabang) {
                $this->perubahan_harga_cabang_m->insert_batch($record_perubahan_harga_cabang);
            }

            if (isset($post['perubahan_harga_kondisi'])) {
                $record_perubahan_harga_kondisi = array();
                foreach ($post['perubahan_harga_kondisi'] as $column => $kondisi) {
                    if ($column) {
	                    if ($column == 'harga') {
		                    $kondisi['from'] = $this->localization->number_value($kondisi['from']);
		                    if ($kondisi['to']) {
			                    $kondisi['to'] = $this->localization->number_value($kondisi['to']);
		                    }
	                    }
                        $record_perubahan_harga_kondisi[] = array(
                            'id_perubahan_harga' => $result->id,
                            'column' => $column,
                            'operation' => $kondisi['operation'],
                            'from' => (is_array($kondisi['from'])) ? implode(',', $kondisi['from']) : $kondisi['from'],
                            'to' => $kondisi['to']
                        );
                    }
                }
                if ($record_perubahan_harga_kondisi) {
                    $this->perubahan_harga_kondisi_m->insert_batch($record_perubahan_harga_kondisi);
                }
            }

            if (isset($post['permanen'])) {
                $this->_update_produk($result, $perubahan_harga_cabang, $record_perubahan_harga_kondisi);
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produk'))))->route('produk.perubahan_harga');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('perubahan_harga'))))->back();
        }
    }

	private function _update_produk($perubahan_harga, $perubahan_harga_cabang, $rs_perubahan_harga_kondisi) {
		$this->db->group_start();
		foreach ($rs_perubahan_harga_kondisi as $perubahan_harga_kondisi) {
			$perubahan_harga_kondisi = (object)$perubahan_harga_kondisi;
			if ($perubahan_harga_kondisi->operation == '=' && is_null($perubahan_harga_kondisi->to)) {
				$values = explode(',', $perubahan_harga_kondisi->from);
				if (is_array($values)) {
					$this->db->group_start();
					$this->db->like($perubahan_harga_kondisi->column, $values[0], 'none', false);
					unset($values[0]);
					foreach ($values as $value) {
						$this->db->or_like($perubahan_harga_kondisi->column, $value, 'none', false);
					}
					$this->db->group_end();
				} else {
					$this->db->like($perubahan_harga_kondisi->column, $perubahan_harga_kondisi->from, 'none', false);
				}
			} elseif ($perubahan_harga_kondisi->operation == '=' && $perubahan_harga_kondisi->to) {
				$this->db->group_start()
					->where($perubahan_harga_kondisi->column.' >= ', $perubahan_harga_kondisi->from)
					->where($perubahan_harga_kondisi->column.' <= ', $perubahan_harga_kondisi->to)
					->group_end();
			} else {
				$this->db->where($perubahan_harga_kondisi->column.' '.$perubahan_harga_kondisi->operation.' ', $perubahan_harga_kondisi->from);
			}
		}
		$this->db->group_end();
		$this->db->group_start()
			->where_in('id_cabang', $perubahan_harga_cabang)
			->group_end();
		$result_produk = $this->view_produk_m->order_by('id_cabang', 'DESC')->get();
		if ($result_produk) {
			$record_broadcast_harga_produk = array();
			$record_id_produk = array();
			foreach ($result_produk as $produk) {
				$record_id_produk[$produk->id_produk] = $produk->id_produk;
				$record_broadcast_harga_produk[] = array(
					'id_cabang' => $produk->id_cabang,
					'tanggal' => date('Y-m-d'),
					'id_produk' => $produk->id_produk,
					'id_satuan' => $produk->id_satuan,
					'jumlah' => $produk->jumlah,
					'harga_awal' => $produk->harga,
					'harga_akhir' => $produk->harga + (($produk->harga * $perubahan_harga->perubahan_harga) / 100)
				);
			}
			if ($record_broadcast_harga_produk) {
				$this->broadcast_harga_produk_m->insert_batch($record_broadcast_harga_produk);
			}
			$this->db->set('harga', 'harga + (harga * ('.$perubahan_harga->perubahan_harga.') / 100)', FALSE)
				->where_in('id_produk', $record_id_produk)
				->update('produk_harga');
		}
	}

    public function edit($id) {
        $model = $this->perubahan_harga_m->find_or_fail($id);
        $model->perubahan_harga_cabang = array();
        foreach ($this->perubahan_harga_cabang_m->where('id_perubahan_harga', $id)->get() as $perubahan_harga_cabang) {
            $model->perubahan_harga_cabang[] = $perubahan_harga_cabang->id_cabang;
        }
        $model->perubahan_harga_kondisi = array();
        foreach ($this->perubahan_harga_kondisi_m->where('id_perubahan_harga', $id)->get() as $perubahan_harga_kondisi) {
            if ($perubahan_harga_kondisi->column == 'kode_produk' || $perubahan_harga_kondisi->column == 'harga') {
                $model->perubahan_harga_kondisi[$perubahan_harga_kondisi->column]['from'] = $perubahan_harga_kondisi->from;
            } else {
                $model->perubahan_harga_kondisi[$perubahan_harga_kondisi->column]['from'] = explode(',', $perubahan_harga_kondisi->from);
            }
            $model->perubahan_harga_kondisi[$perubahan_harga_kondisi->column]['operation'] = $perubahan_harga_kondisi->operation;
            $model->perubahan_harga_kondisi[$perubahan_harga_kondisi->column]['to'] = $perubahan_harga_kondisi->to;
        }
        $this->load->view('produk/perubahan_harga/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $this->form_validation->validate(array(
            'perubahan_harga' => 'required|numeric',
            'tanggal_mulai' => 'required'
        ));
        $post = $this->input->post();
        if (!isset($post['perubahan_harga_cabang'])) {
            $post['perubahan_harga_cabang'][] = 0;
        }
        $this->transaction->start();
            $this->perubahan_harga_m->update($id, $post);
            $this->perubahan_harga_cabang_m->where('id_perubahan_harga', $id)->delete();
            $record_perubahan_harga_cabang = array();
            foreach ($post['perubahan_harga_cabang'] as $id_cabang) {
                $record_perubahan_harga_cabang[] = array(
                    'id_perubahan_harga' => $id,
                    'id_cabang' => $id_cabang
                );
            }
            if ($record_perubahan_harga_cabang) {
                $this->perubahan_harga_cabang_m->insert_batch($record_perubahan_harga_cabang);
            }

            $this->perubahan_harga_kondisi_m->where('id_perubahan_harga', $id)->delete();
            if (isset($post['perubahan_harga_kondisi'])) {
                $record_perubahan_harga_kondisi = array();
                foreach ($post['perubahan_harga_kondisi'] as $column => $kondisi) {
                    if ($column) {
	                    if ($column == 'harga') {
		                    $kondisi['from'] = $this->localization->number_value($kondisi['from']);
		                    if ($kondisi['to']) {
			                    $kondisi['to'] = $this->localization->number_value($kondisi['to']);
		                    }
	                    }
                        $record_perubahan_harga_kondisi[] = array(
                            'id_perubahan_harga' => $id,
                            'column' => $column,
                            'operation' => $kondisi['operation'],
                            'from' => (is_array($kondisi['from'])) ? implode(',', $kondisi['from']) : $kondisi['from'],
                            'to' => $kondisi['to']
                        );
                    }
                }
                if ($record_perubahan_harga_kondisi) {
                    $this->perubahan_harga_kondisi_m->insert_batch($record_perubahan_harga_kondisi);
                }
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('produk'))))->route('produk.perubahan_harga');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('perubahan_harga'))))->back();
        }
    }

    public function delete($id) {
        $this->transaction->start();
            $this->perubahan_harga_m->delete($id);
            $this->perubahan_harga_cabang_m->where('id_perubahan_harga', $id)->delete();
            $this->perubahan_harga_kondisi_m->where('id_perubahan_harga', $id)->delete();
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function start($id) {
        $result = $this->perubahan_harga_m->update($id, array('aktif' => 1));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function stop($id) {
        $result = $this->perubahan_harga_m->update($id, array('aktif' => 0));
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('perubahan_harga')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_json() {
        $id_cabang = $this->session->userdata('cabang')->id;
        $id_produk = $this->input->get('id_produk');
        $id_satuan = $this->input->get('id_satuan');
        $result_perubahan_harga = $this->perubahan_harga_m->scope('active')->get();
        if ($result_perubahan_harga) {
            foreach ($result_perubahan_harga as $i => $perubahan_harga) {
                $result_perubahan_harga_kondisi = $this->perubahan_harga_kondisi_m->where('id_perubahan_harga', $perubahan_harga->id)->get();
                $result_perubahan_harga[$i]->kondisi = $result_perubahan_harga_kondisi;
            }
            foreach ($result_perubahan_harga as $perubahan_harga) {
                $this->db->group_start();
                foreach ($perubahan_harga->kondisi as $perubahan_harga_kondisi) {
                    if ($perubahan_harga_kondisi->operation == '=' && is_null($perubahan_harga_kondisi->to)) {
                        $values = explode(',', $perubahan_harga_kondisi->from);
                        if (is_array($values)) {
                            $this->db->group_start();
                            $this->db->like($perubahan_harga_kondisi->column, $values[0], 'none', false);
                            unset($values[0]);
                            foreach ($values as $value) {
                                $this->db->or_like($perubahan_harga_kondisi->column, $value, 'none', false);
                            }
                            $this->db->group_end();
                        } else {
                            $this->db->like($perubahan_harga_kondisi->column, $perubahan_harga_kondisi->from, 'none', false);
                        }
                    } elseif ($perubahan_harga_kondisi->operation == '=' && $perubahan_harga_kondisi->to) {
                        $this->db->group_start()
                            ->where($perubahan_harga_kondisi->column.' >= ', $perubahan_harga_kondisi->from)
                            ->where($perubahan_harga_kondisi->column.' <= ', $perubahan_harga_kondisi->to)
                            ->group_end();
                    } else {
                        $this->db->where($perubahan_harga_kondisi->column.' '.$perubahan_harga_kondisi->operation.' ', $perubahan_harga_kondisi->from);
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
                        'data' => $perubahan_harga
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