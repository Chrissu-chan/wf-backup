<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Stock_opname extends BaseController {

        public function __construct() {
            parent::__construct();
            $this->load->model('barang_stok_m');
            $this->load->model('cabang_gudang_m');
            $this->load->model('stock_opname_m');
            $this->load->model('stock_opname_detail_m');
            $this->load->library('form_validation');
        }

        public function index() {
            $this->load->view('inventory/stock_opname/index');
        }

        public function start() {
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
                    $stock_opname_detail = $this->stock_opname_detail_m->view('stock_opname_detail')->where('id_stock_opname', $stock_opname->id)->get();
                if ($this->transaction->complete()) {
                    $this->load->view('inventory/stock_opname/start', array(
                        'id_stock_opname' => $stock_opname->id,
                        'stock_opname_detail' => $stock_opname_detail
                    ));
                } else {
                    $this->redirect->with('error_message', $this->localization->lang('error_stock_opname'))->back();
                }
            } else {
                $this->redirect->with('error_message', $this->localization->lang('error_get_barang'))->back();
            }
        }

        public function finish($id) {
            $this->stock_opname_m->scope('active')->find_or_fail($id);
            $this->transaction->start();
                $barang_so = $this->stock_opname_detail_m->view('barang_so')->where('id_stock_opname', $id)->get();
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