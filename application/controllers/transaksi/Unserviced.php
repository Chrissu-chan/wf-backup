<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unserviced extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('unserviced_m');
		$this->load->model('unserviced_detail_m');
		$this->load->model('barang_m');
		$this->load->model('barang_obat_m');
		$this->load->model('obat_m');
		$this->load->model('satuan_m');
		$this->load->model('shift_aktif_m');
		$this->load->model('views/view_unserviced_m');
		$this->load->library('autonumber');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data["title"] = "Unserviced";
		if ($this->input->is_ajax_request()) {
			$this->load->library('datatable');
			return $this->datatable->resource($this->unserviced_m)
				->scope('cabang')
				->edit_column('tanggal', function ($model) {
					return $this->localization->human_date($model->tanggal);
				})
				->edit_column('batal', function ($model) {
					return $this->localization->boolean($model->batal, '<span class="label label-danger">' . ($model->jenis_batal ? $this->unserviced_m->enum('jenis_batal', $model->jenis_batal) : '') . '</span>', '<span class="label label-success">' . $this->localization->lang('approved') . '</span>');
				})
				->add_action('{view} {edit} {delete}', array(
					'edit' => function ($model) {
						$html = '';
						if ($model->batal == 0) {
							$html = $this->action->link('edit', $this->route->name('transaksi.unserviced.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
						}
						return $html;
					},
					'delete' => function ($model) {
						$html = '';
						if ($model->batal == 0) {
							$html = '<div class="btn-group">
			                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                    ' . $this->localization->lang('delete') . ' <span class="caret"></span>
			                    </button>
			                    <ul class="dropdown-menu dropdown-menu-right">
			                        <li>' . $this->action->link('delete', 'javascript:void(0)', 'onclick="remove(' . $model->id . ')"', $this->localization->lang('cancel')) . '</li>
			                        <li>' . $this->action->link('delete', 'javascript:void(0)', 'onclick="returns(' . $model->id . ')"', $this->localization->lang('return')) . '</li>
			                    </ul>
			                </div>';
						}
						return $html;
					}
				))
				->generate();
		}
		$this->load->view('transaksi/unserviced/index', $data);
	}

	public function view($id)
	{
		$model = $this->unserviced_m->find_or_fail($id);
		$model->unserviced_detail = $this->unserviced_detail_m->where('id_servis', $id)->get();
		$this->load->view('transaksi/unserviced/view', array(
			'model' => $model
		));
	}

	public function create()
	{
		$data["title"] = "Uncerviced";
		$this->load->view('transaksi/unserviced/create', $data);
	}

	public function store()
	{
		$post = $this->input->post();
		$validate = array(
			'tanggal' => 'required'
		);
		if (!$post['unserviced_detail'] && !$post['form_add_barang_nama_barang']) {
			$validate['unserviced_detail[]'] = 'required';
		}
		if (isset($post['unserviced_detail'])) {
			foreach ($post['unserviced_detail'] as $key => $val) {
				$validate['unserviced_detail[' . $key . '][satuan]'] = array(
					'field' => $this->localization->lang('unserviced_detail_satuan', array('name' => $post['unserviced_detail'][$key]['nama_barang'])),
					'rules' => 'required'
				);
				$validate['unserviced_detail[' . $key . '][jumlah]'] = array(
					'field' => $this->localization->lang('unserviced_detail_jumlah', array('name' => $post['unserviced_detail'][$key]['nama_barang'])),
					'rules' => 'required|numeric|greater_than[0]'
				);
			}
		}
		$this->form_validation->validate($validate);
		$this->transaction->start();
		$post['no_servis'] = $this->autonumber->resource($this->unserviced_m, 'no_servis')->format('US-{Y}{m}:4')->generate();
		$post['id_shift_aktif'] = 0;
		$shift_aktif = $this->shift_aktif_m->scope('cabang')->scope('aktif')->first();
		if ($shift_aktif) {
			$post['id_shift_aktif'] = $shift_aktif->id;
		}
		$result = $this->unserviced_m->insert($post);
		if ($post['form_add_barang_nama_barang']) {
			$post['unserviced_detail'][0] = array(
				'id_barang' => $post['form_add_barang_id_barang'],
				'nama_barang' => $post['form_add_barang_nama_barang'],
				'id_satuan' => $post['form_add_barang_id_satuan'],
				'satuan' => $post['form_add_barang_satuan'],
				'jumlah' => $post['form_add_barang_jumlah']
			);
		}
		$record_unserviced_detail = array();
		foreach ($post['unserviced_detail'] as $unserviced_detail) {
			$unserviced_detail['id_servis'] = $result->id;
			$record_unserviced_detail[] = $unserviced_detail;
		}
		if ($record_unserviced_detail) {
			$this->unserviced_detail_m->insert_batch($record_unserviced_detail);
		}
		if ($this->transaction->complete()) {
			$this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('unserviced'))))->route('transaksi.unserviced');
		} else {
			$this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('unserviced'))))->back();
		}
	}

	public function edit($id)
	{
		$title = "Unserviced";
		$model = $this->unserviced_m->find_or_fail($id);
		$model->unserviced_detail = array();
		foreach ($this->unserviced_detail_m->where('id_servis', $id)->get() as $unserviced_detail) {
			$model->unserviced_detail[$unserviced_detail->id] = $unserviced_detail;
		}
		$this->load->view('transaksi/unserviced/edit', array(
			'model' => $model, 'title' => $title
		));
	}

	public function update($id)
	{
		$post = $this->input->post();
		$validate = array(
			'tanggal' => 'required'
		);
		if (!$post['unserviced_detail']) {
			$validate['unserviced_detail[]'] = 'required';
		}
		if (isset($post['unserviced_detail'])) {
			foreach ($post['unserviced_detail'] as $key => $val) {
				$validate['unserviced_detail[' . $key . '][satuan]'] = array(
					'field' => $this->localization->lang('unserviced_detail_satuan', array('name' => $post['unserviced_detail'][$key]['nama_barang'])),
					'rules' => 'required'
				);
				$validate['unserviced_detail[' . $key . '][jumlah]'] = array(
					'field' => $this->localization->lang('unserviced_detail_jumlah', array('name' => $post['unserviced_detail'][$key]['nama_barang'])),
					'rules' => 'required|numeric|greater_than[0]'
				);
			}
		}
		$this->form_validation->validate($validate);
		$this->transaction->start();
		$post['id_shift_aktif'] = 0;
		$shift_aktif = $this->shift_aktif_m->scope('cabang')->scope('aktif')->first();
		if ($shift_aktif) {
			$post['id_shift_aktif'] = $shift_aktif->id;
		}
		$this->unserviced_m->update($id, $post);
		$this->unserviced_detail_m->where('id_servis', $id)->delete();
		if ($post['form_add_barang_nama_barang']) {
			$post['unserviced_detail'][0] = array(
				'id_barang' => $post['form_add_barang_id_barang'],
				'nama_barang' => $post['form_add_barang_nama_barang'],
				'id_satuan' => $post['form_add_barang_id_satuan'],
				'satuan' => $post['form_add_barang_satuan'],
				'jumlah' => $post['form_add_barang_jumlah']
			);
		}
		$record_unserviced_detail = array();
		foreach ($post['unserviced_detail'] as $unserviced_detail) {
			$unserviced_detail['id_servis'] = $id;
			$record_unserviced_detail[] = $unserviced_detail;
		}
		if ($record_unserviced_detail) {
			$this->unserviced_detail_m->insert_batch($record_unserviced_detail);
		}
		if ($this->transaction->complete()) {
			$this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('unserviced'))))->route('transaksi.unserviced');
		} else {
			$this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('unserviced'))))->back();
		}
	}

	public function get_barang_json()
	{
		$key = $this->input->get('key');
		$result = $this->view_unserviced_m->like('nama_barang', $key)->get();
		$response = array(
			'success' => true,
			'data' => $result
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function get_satuan_json()
	{
		$id_barang = $this->input->get('id_barang');
		$barang = $this->input->get('barang');
		$key = $this->input->get('key');
		if ($id_barang) {
			$result = $this->satuan_m->select('id AS id_satuan, satuan')
				->where('grup', $id_barang)
				->like('satuan', $key)
				->get();
		} else {
			$result = $this->unserviced_detail_m->select('DISTINCT(satuan) AS satuan, id_satuan')
				->where('nama_barang', $barang)
				->like('satuan', $key)
				->get();
		}
		$response = array(
			'success' => true,
			'data' => $result
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function delete($id)
	{
		$post = $this->input->post();
		$this->transaction->start();
		$this->unserviced_m->update($id, array(
			'batal' => 1,
			'jenis_batal' => $post['jenis_batal'],
			'deleted_by' => $this->auth->username,
			'deleted_at' => date('Y-m-d H:i:s')
		));
		if ($this->transaction->complete()) {
			$response = array(
				'success' => true,
				'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('unserviced')))
			);
		} else {
			$response = array(
				'success' => false,
				'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('unserviced')))
			);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}
