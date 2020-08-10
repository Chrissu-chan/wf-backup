<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Stock_opname extends BaseController {

        public function __construct() {
            parent::__construct();
            $this->load->model('barang_stok_m');
            $this->load->model('cabang_gudang_m');
            $this->load->model('stock_opname_m');
            $this->load->model('stock_opname_detail_m');
	        $this->load->model('fifo_m');
            $this->load->library('form_validation');
        }

        public function index() {
            $this->load->view('inventory/stock_opname/index');
        }

        public function start() {
            if ($this->input->is_ajax_request()) {
                $id_stock_opname = $this->input->get('id_stock_opname');
	            $status = $this->input->get('status');
                $this->load->library('datatable');
	            if ($status == 'waiting') {
		            $this->stock_opname_detail_m->where('so_by', NULL, FALSE);
	            } else if ($status == 'done') {
		            $this->stock_opname_detail_m->where('so_by != ', NULL, FALSE);
	            }
                return $this->datatable->resource($this->stock_opname_detail_m)
                    ->view('stock_opname_detail')
                    ->where('id_stock_opname', $id_stock_opname)
	                ->edit_column('harga_beli', function($model) {
		                return $this->localization->number($model->harga_beli, 2);
	                })
	                ->edit_column('stok_awal', function($model) {
		                return $this->localization->number($model->stok_awal);
	                })
	                ->add_column('stok_akhir', function($model) {
		                return $this->localization->number($model->stok_awal + $model->selisih);
	                })
	                ->edit_column('selisih', function($model) {
		                return $this->localization->number($model->selisih);
	                })
	                ->add_column('total', function($model) {
		                return $this->localization->number($model->selisih * $model->harga_beli, 2);
	                })
                    ->generate();
            }
            $post = $this->input->post();
            $barang_stok = $this->barang_stok_m->where('id_gudang', $post['id_gudang'])->get();
            if ($barang_stok) {
                $post['waktu_mulai'] = date('Y-m-d H:i:s');
                $post['opened_by'] = $this->auth->username;
                $post['total_barang'] = count($barang_stok);
                $this->transaction->start();
                    $stock_opname = $this->stock_opname_m->scope('active')->where('id_gudang', $post['id_gudang'])->first();
                    if (!$stock_opname) {
                        $stock_opname = $this->stock_opname_m->insert($post);
                        $record = array();
                        foreach ($barang_stok as $barang) {
                            $record[] = array(
                                'id_stock_opname' => $stock_opname->id,
                                'id_obat' => $barang->id_barang
                            );
                        }
                        if ($record) {
                            $this->stock_opname_detail_m->insert_batch($record);
                        }
                    }
                    //$stock_opname_detail = $this->stock_opname_detail_m->view('stock_opname_detail')->where('id_stock_opname', $stock_opname->id)->get();
                if ($this->transaction->complete()) {
                    $this->load->view('inventory/stock_opname/start', array(
                        'id_stock_opname' => $stock_opname->id
                    ));
                } else {
                    $this->redirect->with('error_message', $this->localization->lang('error_stock_opname'))->back();
                }
            } else {
                $this->redirect->with('error_message', $this->localization->lang('error_get_barang'))->back();
            }
        }

        public function finish($id) {
            $stock_opname = $this->stock_opname_m->scope('active')->find_or_fail($id);
            $this->transaction->start();
	            $barang_stok = $this->barang_stok_m->where('id_gudang', $stock_opname->id_gudang)->get();
	            $result_barang = array();
	            if ($barang_stok) {
		            foreach ($barang_stok as $barang) {
			            $result_barang[$barang->id_barang] = $barang;
		            }
	            }
                $barang_so = $this->stock_opname_detail_m->view('stock_opname_detail')
	                ->scope('done')
	                ->where('id_stock_opname', $id)
	                ->get();
	            if ($barang_so) {
		            $this->fifo_m->set_gudang($stock_opname->id_gudang);
		            foreach ($barang_so as $so) {
			            if (isset($result_barang[$so->id_barang])) {
				            $barang_stok = $result_barang[$so->id_barang];
				            if ($so->selisih != 0) {
					            if ($so->selisih > 0) {
						            $this->fifo_m->insert('masuk', array(
							            'jenis_mutasi' => 'stock_opname',
							            'id_ref' => $so->id,
							            'tanggal_mutasi' => date('Y-m-d'),
							            'id_barang' => $so->id_obat,
							            'id_satuan' => $barang_stok->id_satuan,
							            'jumlah' => $so->selisih,
							            'total' => $so->hna * $so->selisih,
							            'expired' => $so->expired
						            ));
					            } else {
						            $this->fifo_m->insert('keluar', array(
							            'jenis_mutasi' => 'stock_opname',
							            'id_ref' => $so->id,
							            'tanggal_mutasi' => date('Y-m-d'),
							            'id_barang' => $so->id_obat,
							            'id_satuan' => $barang_stok->id_satuan,
							            'jumlah' => $so->selisih * -1
						            ));
					            }
				            }
			            }
		            }
	            }
                $this->stock_opname_m->update($id, array(
                    'waktu_selesai' => date('Y-m-d H:i:s'),
                    'closed_by' => $this->auth->username,
                    'total_barang_so' => count($barang_so)
                ));
            if ($this->transaction->complete()) {
                $this->redirect->with('success_message', $this->localization->lang('finish_stock_opname'))->route('inventory.stock_opname');
            } else {
                $this->redirect->with('error_message', $this->localization->lang('error_stock_opname'))->back();
            }
        }

	    public function get_json() {
		    $id_stock_opname = $this->input->get('id_stock_opname');
		    $stock_opname_detail = $this->stock_opname_detail_m->view('stock_opname_detail')
			    ->where('id_stock_opname', $id_stock_opname)
			    ->get();
		    if ($stock_opname_detail) {
			    $response = array(
				    'success' => TRUE,
				    'data' => $stock_opname_detail
			    );
		    } else {
			    $response = array(
				    'success' => FALSE,
				    'data' => NULL
			    );
		    }
		    $this->output->set_content_type('application/json')->set_output(json_encode($response));
	    }
    }