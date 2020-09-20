<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname_barang extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->load->model('barang_m');
		$this->load->model('barang_obat_m');
		$this->load->model('stock_opname_m');
		$this->load->model('stock_opname_barang_m');
		$this->load->model('stock_opname_detail_m');
		$this->load->library('form_validation');
	}

	public function index() {
		$data['title'] = 'Barang Stock Opname';
		if ($this->input->is_ajax_request()) {
			$this->load->library('datatable');
			return $this->datatable->resource($this->barang_m)
				->view('barang_stock_opname')
				->generate();
		}
		$this->load->view('inventory/stock_opname_barang/index', $data);
	}

	public function update($id, $method) {
		$barang = $this->barang_obat_m->view('barang')->find_or_fail($id);
        $this->transaction->start();
            $id_gudang = $this->cabang_gudang_m->scope('utama')
                ->where('id_cabang', $this->session->userdata('cabang')->id)
                ->first()->id_gudang;
            if ($method == 'insert') {
                $this->stock_opname_barang_m->insert(array(
                    'id_cabang' => $this->session->userdata('cabang')->id,
                    'id_barang' => $id
                ));
            } else if ($method == 'delete') {
                $this->stock_opname_barang_m->where('id_cabang', $this->session->userdata('cabang')->id)
                    ->where('id_barang', $id)
                    ->delete();
            }
            $stock_opname = $this->stock_opname_m->scope('active')
                ->where('id_gudang', $id_gudang)
                ->first();
            if ($stock_opname) {
                if ($method == 'insert') {
                    $this->stock_opname_detail_m->insert(array(
                        'id_stock_opname' => $stock_opname->id,
                        'id_obat' => $barang->id_obat
                    ));
                } else if ($method == 'delete') {
                    $this->stock_opname_detail_m->where('id_stock_opname', $stock_opname->id)
                        ->where('id_obat', $barang->id_obat)
                        ->delete();
                }
            }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('stock_opname_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('stock_opname_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}