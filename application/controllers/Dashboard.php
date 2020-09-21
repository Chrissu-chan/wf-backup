<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->load->model('broadcast_harga_produk_m');
		$this->load->model('produk_harga_m');
		$this->load->model('cabang_gudang_m');
		$this->load->model('barang_stok_m');
		$this->load->model('barang_ignore_expired_m');
	}

    public function index() {
		if ($this->input->is_ajax_request()) {
			$this->load->library('datatable');
			$type = $this->input->get('type');
			$gudang = $this->cabang_gudang_m->scope('utama')
				->where('id_cabang', $this->session->userdata('cabang')->id)
				->first()->id_gudang;
			$range = 180;
			if ($type == 'expired') {
				return $this->datatable->resource($this->barang_stok_m, false)
					->stok_expired($range, TRUE)
					->where('barang_stok.id_gudang', $gudang)
					->where('barang_stok.jumlah > ', 0)
					->add_column('expired_desc', function($model) {
						return $this->localization->human_date($model->expired);
					})
					->generate();
			} else if ($type == 'ignore') {
				return $this->datatable->resource($this->barang_ignore_expired_m, false)
					->view('barang_ignore_expired')
					->where('barang_ignore_expired.id_gudang', $gudang)
					->add_column('expired_desc', function($model) {
						return $this->localization->human_date($model->expired);
					})
					->generate();
			}
			
		}

	    $broadcast_harga_produk = $this->broadcast_harga_produk_m->view('broadcast_harga_produk')
			->scope('cabang_aktif')
			->order_by('created_at', 'DESC')
		    ->get();
	    $margin_laba = $this->produk_harga_m->view('margin_laba')
		    ->get();
        $this->load->view('dashboard/index', array(
	        'broadcast_harga_produk' => $broadcast_harga_produk,
	        'margin_laba' => $margin_laba
        ));
    }
    
    public function hide_expired() {
		$post = $this->input->post();
		$this->transaction->start();
		if (isset($post['barang'])) {
			$record_barang_expired = array();
			foreach ($post['barang'] as $barang) {
				if (isset($barang['id_barang'])) {
					$record_barang_expired[] = array(
						'id_barang' => $barang['id_barang'],
						'expired' => $barang['expired']
					);
				}
			}
			if ($record_barang_expired) {
				$this->barang_ignore_expired_m->insert_batch($record_barang_expired);
			}
		}
		if ($this->transaction->complete()) {
			$response = array(
				'success' => true,
				'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('expired')))
			);
		} else {
			$response = array(
				'success' => false,
				'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('expired')))
			);
		}
	    $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

	public function show_expired() {
		$post = $this->input->post();
		$id_gudang = $this->cabang_gudang_m->scope('utama')
			->where('id_cabang', $this->session->userdata('cabang')->id)
			->first()->id_gudang;
		$this->transaction->start();
			if (isset($post['id'])) {
				foreach ($post['id'] as $id) {
					$this->barang_ignore_expired_m->delete($id);
				}
			}
		if ($this->transaction->complete()) {
			$response = array(
				'success' => true,
				'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('expired')))
			);
		} else {
			$response = array(
				'success' => false,
				'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('expired')))
			);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}