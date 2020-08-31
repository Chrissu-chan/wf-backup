<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_shift extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('shift_aktif_m');
		$this->load->model('barang_stok_m');
		$this->load->model('cabang_gudang_m');
		$this->load->model('penjualan_m');
		$this->load->model('mutasi_kasir_m');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data["title"] = "Monitoring Shift";
		if ($this->input->is_ajax_request()) {
			$this->load->library('datatable');
			if ($tanggal = $this->input->get('tanggal')) {
				$this->shift_aktif_m->where('LEFT(shift_aktif.created_at, 10) = ', date('Y-m-d', strtotime($tanggal)));
			};
			return $this->datatable->resource($this->shift_aktif_m)
				->view('shift_aktif')
				->scope('cabang')
				->edit_column('created_at', function ($model) {
					return $this->localization->human_datetime($model->created_at);
				})
				->edit_column('uang_awal', function ($model) {
					return $this->localization->number($model->uang_awal);
				})
				->edit_column('uang_akhir', function ($model) {
					return $this->localization->number($model->uang_akhir);
				})
				->edit_column('active', function ($model) {
					return $this->localization->boolean($model->active);
				})
				->add_action('{detail}', array(
					'detail' => function ($model) {
						return $this->action->link('view', $this->route->name('transaksi.monitoring_shift.detail', array('id' => $model->id)), 'class="btn btn-info btn-sm"', $this->localization->lang('detail'));
					}
				))
				->generate();
		}
		$this->load->view('transaksi/monitoring_shift/index', $data);
	}

	public function detail($id)
	{
		$title = "Monitoring Shift";
		$model = $this->shift_aktif_m->view('shift_aktif')->find_or_fail($id);
		$model->total_penjualan = $this->penjualan_m->select_sum('total')
			->scope('cabang')
			->where('id_shift_aktif', $id)
			->first()->total;
		$model->total_pemasukan = $this->mutasi_kasir_m->select_sum('nominal')
			->scope('pemasukan')
			->where('id_shift_aktif_kasir', $model->id_shift_aktif_kasir)
			->first()->nominal;
		$model->total_pengeluaran = $this->mutasi_kasir_m->select_sum('nominal')
			->scope('pengeluaran')
			->where('id_shift_aktif_kasir', $model->id_shift_aktif_kasir)
			->first()->nominal;

		if ($this->input->is_ajax_request()) {
			$gudang = $this->cabang_gudang_m->scope('utama')
				->where('id_cabang', $this->session->userdata('cabang')->id)
				->first_or_fail();
			$this->load->library('datatable');
			return $this->datatable->resource($this->barang_stok_m)
				->monitoring_shift($gudang->id_gudang, $id)
				->edit_column('total_penjualan', function ($model) {
					return $this->localization->number($model->total_penjualan);
				})
				->edit_column('stok_akhir', function ($model) {
					if ($model->shift_aktif) {
						$stok_akhir = $model->stok_awal + $model->mutasi;
					} else {
						$stok_akhir = $model->stok_akhir;
					}
					return $this->localization->number($stok_akhir);
				})
				/*->add_action('{detail}', array(
					'detail' => function($model) {
						return $this->action->link('view', $this->route->name('transaksi.monitoring_shift.detail', array('id' => $model->id)), 'class="btn btn-info btn-sm"', $this->localization->lang('detail'));
					}
				))*/
				->generate();
		}
		$this->load->view('transaksi/monitoring_shift/detail', array(
			'model' => $model, 'title' => $title
		));
	}
}
