<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Stock_opname extends BaseController {

        public function __construct() {
            parent::__construct();
            $this->load->model('users_m');
            $this->load->model('barang_stok_m');
            $this->load->model('cabang_gudang_m');
            $this->load->model('stock_opname_m');
            $this->load->model('stock_opname_detail_m');
            $this->load->library('form_validation');
        }

        public function get() {
            $stock_opname = $this->stock_opname_m->view('stock_opname')->scope('active')->get();
            $response = array(
                'success' => true,
                'data' => $stock_opname
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }

        public function barang() {
            $post = $this->input->post();
            $stock_opname = $this->stock_opname_m->scope('active')->where('id_gudang', $post['id_gudang'])->first();
            if ($stock_opname) {
                $barang_stok = $this->barang_stok_m->view('barang_stok')
	                ->where('id_gudang', $post['id_gudang'])
                    ->where('barcode', $post['barcode'])
                    ->first();
                if ($barang_stok) {
                    $response = array(
                        'success' => true,
                        'data' => $barang_stok
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => $this->localization->lang('barang_tidak_sesuai')
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' => $this->localization->lang('stock_opname_tidak_diizinkan')
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }

        public function store() {
            $post = $this->input->post();
            $stock_opname = $this->stock_opname_m->scope('active')->where('id_gudang', $post['id_gudang'])->first();
            if ($stock_opname) {
                    $stock_opname_detail = $this->stock_opname_detail_m->view('stock_opname_detail')
                        ->where('id_stock_opname', $stock_opname->id)
                        ->where('barcode', $post['barcode'])
                        ->order_by('id', 'desc')
                        ->first();
                    if ($stock_opname_detail) {
                        $this->transaction->start();
                        $record = array(
                            'id_stock_opname' => $stock_opname->id,
                            'id_obat' => $stock_opname_detail->id_obat,
                            'selisih' => $post['jumlah'] - $stock_opname_detail->stok_awal,
	                        'hna' => (isset($post['hna']) ? $post['hna'] : NULL),
	                        'expired' => (isset($post['expired']) ? $post['expired'] : NULL),
                            'so_by' => $this->auth->username
                        );
	                    $this->stock_opname_detail_m->update($stock_opname_detail->id, $record);
                        if ($this->transaction->complete()) {
                            $response = array(
                                'success' => true,
                                'message' => $this->localization->lang('stock_opname_berhasil_disimpan')
                            );
                        } else {
                            $response = array(
                                'success' => false,
                                'message' => $this->localization->lang('stock_opname_gagal_disimpan')
                            );
                        }
                } else {
                    $response = array(
                        'success' => false,
                        'message' => $this->localization->lang('barang_tidak_sesuai')
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' => $this->localization->lang('stock_opname_tidak_diizinkan')
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }