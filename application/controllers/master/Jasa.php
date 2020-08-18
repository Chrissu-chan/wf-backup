<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Jasa extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('barang_m');
		$this->load->model('konversi_satuan_m');
		$this->load->model('jasa_m');
		$this->load->model('jasa_pemakaian_barang_m');
		$this->load->model('kategori_jasa_m');
		$this->load->model('satuan_m');
		$this->load->model('produk_m');
		$this->load->model('produk_cabang_m');
		$this->load->model('produk_harga_m');
		$this->load->model('produk_jasa_komisi_m');
		$this->load->model('broadcast_harga_produk_m');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data["title"] = "Master Jasa";

		if ($this->input->is_ajax_request()) {
			$this->load->library('datatable');
			return $this->datatable->resource($this->jasa_m)
				->view('jasa')
				->add_action('{view} {edit} {delete}', array(
					'edit' => function ($model) {
						return $this->action->link('edit', $this->route->name('master.jasa.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
					}
				))
				->generate();
		}
		$this->load->view('master/jasa/index', $data);
	}

	public function view($id)
	{
		$model = $this->jasa_m->view('jasa')->find_or_fail($id);
		$model->pemakaian_barang = $this->jasa_pemakaian_barang_m->view('pemakaian_barang')->where('id_jasa', $id)->get();
		$this->load->view('master/jasa/view', array(
			'model' => $model
		));
	}

	public function create()
	{
		$data["title"] = "Master Jasa";
		$this->load->view('master/jasa/create', $data);
	}

	public function store()
	{
		$post = $this->input->post();
		$validate = array(
			'jasa' => 'required',
			'id_kategori_jasa' => 'required'
		);

		if (isset($post['pemakaian_barang'])) {
			foreach ($post['pemakaian_barang'] as $key => $val) {
				$validate['pemakaian_barang[' . $key . '][id_barang]'] = array(
					'field' => $this->localization->lang('pemakaian_barang_barang'),
					'rules' => 'required'
				);
				$validate['pemakaian_barang[' . $key . '][id_satuan]'] = array(
					'field' => $this->localization->lang('pemakaian_barang_satuan', array('name' => $post['pemakaian_barang'][$key]['nama_barang'])),
					'rules' => 'required'
				);
				$validate['pemakaian_barang[' . $key . '][jumlah]'] = array(
					'field' => $this->localization->lang('pemakaian_barang_jumlah', array('name' => $post['pemakaian_barang'][$key]['nama_barang'])),
					'rules' => 'required|numeric|greater_than[0]'
				);
			}
		}
		$this->form_validation->validate($validate);

		$result = $this->jasa_m->insert($post);
		if ($result) {
			if (isset($post['pemakaian_barang'])) {
				$rs_pemakaian_barang = array();
				foreach ($post['pemakaian_barang'] as $pemakaian_barang) {
					$pemakaian_barang['id_jasa'] = $result->id;
					$rs_pemakaian_barang[] = $pemakaian_barang;
				}

				$this->jasa_pemakaian_barang_m->insert_batch($rs_pemakaian_barang);
			}
			$this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('jasa'))))->route('master.jasa');
		} else {
			$this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('jasa'))))->back();
		}
	}

	public function edit($id)
	{
		$title = "Master Jasa";
		$model = $this->jasa_m->find_or_fail($id);
		$rs_pemakaian_barang = $this->jasa_pemakaian_barang_m->view('pemakaian_barang')->where('id_jasa', $id)->get();
		foreach ($rs_pemakaian_barang as $pemakaian_barang) {
			$model->pemakaian_barang[$pemakaian_barang->id_barang] = $pemakaian_barang;
		}
		$this->load->view('master/jasa/edit', array(
			'model' => $model, 'title' => $title
		));
	}

	public function update($id)
	{
		$post = $this->input->post();
		$validate = array(
			'jasa' => 'required',
			'id_kategori_jasa' => 'required'
		);

		if (isset($post['pemakaian_barang'])) {
			foreach ($post['pemakaian_barang'] as $key => $val) {
				$validate['pemakaian_barang[' . $key . '][id_satuan]'] = array(
					'field' => $this->localization->lang('pemakaian_barang_satuan', array('name' => $post['pemakaian_barang'][$key]['nama_barang'])),
					'rules' => 'required'
				);
				$validate['pemakaian_barang[' . $key . '][jumlah]'] = array(
					'field' => $this->localization->lang('pemakaian_barang_jumlah', array('name' => $post['pemakaian_barang'][$key]['nama_barang'])),
					'rules' => 'required|numeric|greater_than[0]'
				);
			}
		}
		$this->form_validation->validate($validate);

		$result = $this->jasa_m->update($id, $post);
		if ($result) {
			$this->jasa_pemakaian_barang_m->where('id_jasa', $id)->delete();
			if (isset($post['pemakaian_barang'])) {
				$rs_pemakaian_barang = array();
				foreach ($post['pemakaian_barang'] as $pemakaian_barang) {
					$pemakaian_barang['id_jasa'] = $id;
					$rs_pemakaian_barang[] = $pemakaian_barang;
				}
				$this->jasa_pemakaian_barang_m->insert_batch($rs_pemakaian_barang);
			}
			$this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('jasa'))))->route('master.jasa');
		} else {
			$this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('jasa'))))->back();
		}
	}

	public function delete($id)
	{
		$this->transaction->start();
		$this->jasa_m->delete($id);
		$this->jasa_pemakaian_barang_m->where('id_jasa', $id)->delete();
		if ($this->transaction->complete()) {
			$response = array(
				'success' => true,
				'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('jasa')))
			);
		} else {
			$response = array(
				'success' => false,
				'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('jasa')))
			);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function import()
	{
		$this->load->view('master/jasa/import');
	}

	public function import_store()
	{
		$errors = array();
		$success_count = 0;
		$config['upload_path'] = './' . $this->config->item('import_upload_path');
		$config['allowed_types'] = $this->config->item('import_allowed_file_types');
		$config['max_size'] = $this->config->item('document_max_size');
		$this->load->library('upload', $config);
		if (!$this->upload->has('file')) {
			$this->redirect->with('error_message', $this->localization->lang('upload_required'))->back();
		}
		if (!$this->upload->do_upload('file')) {
			$this->redirect->with('error_message', $this->upload->display_errors())->back();
		}
		$file_name = $this->upload->data('file_name');
		try {
			$inputFileName = $config['upload_path'] . '/' . $file_name;
			$spreadsheet = IOFactory::load($inputFileName);
		} catch (Exception $e) {
			$this->redirect->with('error_message', $e->getMessage())->back();
		}

		$worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		$format = array(
			'A' => 'No',
			'B' => 'Kode',
			'C' => 'Nama',
			'D' => 'Kategori Jasa',
			'T' => 'Jadikan Produk',
			'U' => 'PPN (%)',
			'V' => 'Harga Jual',
			'W' => 'Komisi (%)'
		);

		foreach ($format as $key => $value) {
			if ($worksheet['5'][$key] != $value) {
				$this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
			}
		}

		$record_broadcast_harga_produk = array();
		for ($i = 7; $i <= count($worksheet); $i++) {
			$this->transaction->start();

			$no = $worksheet[$i]['A'];
			$kode = trim($worksheet[$i]['B']);
			$nama = trim($worksheet[$i]['C']);
			$kategori_jasa = trim($worksheet[$i]['D']);
			$produk = trim($worksheet[$i]['T']);
			$produk_ppn_persen = trim($worksheet[$i]['U']);
			$harga = trim($worksheet[$i]['V']);
			$produk_komisi_persen = trim($worksheet[$i]['W']);

			$data = array(
				'kode' => $kode,
				'jasa' => $nama,
				'kategori_jasa' => $kategori_jasa
			);

			$this->form_validation->set_data($data);
			if (!$this->form_validation->validate(array(
				'kode' => 'required',
				'jasa' => 'required',
				'kategori_jasa' => 'required'
			), true)) {
				$errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
				continue;
			}

			$jasa = $this->jasa_m->where('LOWER(jasa)', strtolower($nama))->first();

			$r_kategori_jasa = $this->kategori_jasa_m->where('LOWER(kategori_jasa)', strtolower($kategori_jasa))->first();
			if (!$r_kategori_jasa) {
				$r_kategori_jasa = $this->kategori_jasa_m->insert(array(
					'kategori_jasa' => $kategori_jasa,
					'parent_id' => 0
				));
			}
			$data['id_kategori_jasa'] = $r_kategori_jasa->id;

			if ($jasa) {
				$this->jasa_m->update($jasa->id, $data);
			} else {
				$jasa = $this->jasa_m->insert($data);
			}

			$r_produk = $this->produk_m->where('LOWER(kode)', strtolower($kode))->first();
			if ($produk) {
				if ($r_produk) {
					$this->produk_m->update($r_produk->id, array(
						'kode' => $kode,
						'barcode' => $kode,
						'produk' => $nama,
						'jenis' => 'jasa',
						'id_ref' => $jasa->id,
						'ppn_persen' => $produk_ppn_persen
					));
					$r_produk_harga = $this->produk_harga_m->where('id_produk', $r_produk->id)
						->where('jumlah', 1)
						->where('utama', 1)
						->first();
					$this->produk_harga_m->update($r_produk_harga->id, array(
						'id_cabang' => 0,
						'id_produk' => $r_produk->id,
						'id_satuan' => 0,
						'jumlah' => 1,
						'harga' => $harga,
						'urutan' => 1,
						'utama' => 1
					));
					$record_broadcast_harga_produk[] = array(
						'id_cabang' => 0,
						'tanggal' => date('Y-m-d'),
						'id_produk' => $r_produk->id,
						'id_satuan' => 0,
						'jumlah' => 1,
						'harga_awal' => $r_produk_harga->harga,
						'harga_akhir' => $harga
					);
					if ($produk_komisi_persen) {
						$r_produk_jasa_komisi = $this->produk_jasa_komisi_m->where('id_produk', $r_produk->id)
							->where('id_cabang', 0)
							->first();
						if ($r_produk_jasa_komisi) {
							$this->produk_jasa_komisi_m->update($r_produk_jasa_komisi->id, array('komisi' => $produk_komisi_persen));
						} else {
							$this->produk_jasa_komisi_m->insert(array(
								'id_cabang' => 0,
								'id_produk' => $r_produk->id,
								'id_petugas' => 0,
								'komisi' => $produk_komisi_persen
							));
						}
					}
				} else {
					$r_produk = $this->produk_m->insert(array(
						'kode' => $kode,
						'barcode' => $kode,
						'produk' => $nama,
						'jenis' => 'jasa',
						'id_ref' => $jasa->id,
						'ppn_persen' => $produk_ppn_persen
					));
					$this->produk_cabang_m->insert(array(
						'id_cabang' => 0,
						'id_produk' => $r_produk->id
					));
					$this->produk_harga_m->insert(array(
						'id_cabang' => 0,
						'id_produk' => $r_produk->id,
						'id_satuan' => 0,
						'jumlah' => 1,
						'harga' => $harga,
						'urutan' => 1,
						'utama' => 1
					));
					if ($produk_komisi_persen > 0) {
						$this->produk_jasa_komisi_m->insert(array(
							'id_cabang' => 0,
							'id_produk' => $r_produk->id,
							'id_petugas' => 0,
							'komisi' => $produk_komisi_persen
						));
					}
				}
			}

			$this->db->where('id_jasa', $jasa->id)->delete('jasa_pemakaian_barang');
			$bahan_baku = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S');

			for ($j = 0; $j < 12; $j++) {
				$kode = trim($worksheet[$i][$bahan_baku[$j]]);
				$satuan = trim($worksheet[$i][$bahan_baku[$j + 1]]);
				$jumlah = trim($worksheet[$i][$bahan_baku[$j + 2]]);
				if ($kode && $satuan && $jumlah > 0) {
					$id_satuan = NULL;
					$barang = $this->barang_m->view('barang')
						->where('LOWER(kode)', strtolower($kode))
						->first();
					if ($barang) {
						if (strtolower($satuan) == strtolower($barang->satuan)) {
							$id_satuan = $barang->id_satuan;
						} else {
							$satuan_konversi = $this->konversi_satuan_m->view('konversi_satuan')
								->where('id_satuan_tujuan', $barang->id_satuan)
								->get();
							if ($satuan_konversi) {
								foreach ($satuan_konversi as $konversi) {
									if (strtolower($satuan) == strtolower($konversi->satuan_asal)) {
										$id_satuan = $konversi->id_satuan_asal;
									}
								}
							}
						}

						if ($id_satuan) {
							$this->jasa_pemakaian_barang_m->insert(array(
								'id_jasa' => $jasa->id,
								'id_barang' => $barang->id,
								'id_satuan' => $id_satuan,
								'jumlah' => $jumlah
							));
						}
					}
				}
				$j += 2;
			}

			if ($this->transaction->complete()) {
				$success_count++;
			} else {
				$errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('barang')))));
			}
		}
		if ($record_broadcast_harga_produk) {
			$this->broadcast_harga_produk_m->insert_batch($record_broadcast_harga_produk);
		}
		$this->redirect->with('import_error_message', $errors)
			->with('import_success_message', $success_count)
			->back();
	}

	public function download_format()
	{
		$this->load->helper('download');
		$path = base_url('public/master/jasa/import_jasa.xlsx');
		$data = file_get_contents($path);
		$name = 'import_jasa.xlsx';
		return force_download($name, $data);
	}

	public function export()
	{
		$cabang = $this->cabang_gudang_m->view('cabang_gudang')->scope('aktif_cabang')->first_or_fail();
		$spreadsheet = IOFactory::load('public/master/jasa/import_jasa.xlsx');
		$worksheet = $spreadsheet->getActiveSheet();

		$cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

		$style = array(
			'borders' => array(
				'bottom' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
				'left' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
				'right' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);

		$jasa_pemakaian_barang = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S');

		$rs_jasa = $this->jasa_m->view('jasa')->get();
		$row = 7;
		$no = 1;
		$worksheet->getCell('A1')->setValue('Data Jasa');
		$worksheet->getCell('A2')->setValue($cabang->nama);
		$worksheet->getCell('A3')->setValue(date('d-m-Y'));
		foreach ($rs_jasa as $key => $jasa) {
			$rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->view('pemakaian_barang')
				->where('id_jasa', $jasa->id)
				->limit(5)
				->get();

			$worksheet->getCell('A' . $row)->setValue($no);
			if ($jasa->produk) {
				$worksheet->getCell('B' . $row)->setValue($jasa->kode);
				$worksheet->getCell('T' . $row)->setValue(1);
				$worksheet->getCell('U' . $row)->setValue($jasa->produk_ppn_persen);
				$worksheet->getCell('V' . $row)->setValue($jasa->harga);
				$worksheet->getCell('W' . $row)->setValue($jasa->komisi);
			}
			$worksheet->getCell('C' . $row)->setValue($jasa->jasa);
			$worksheet->getCell('D' . $row)->setValue($jasa->kategori_jasa);

			$j = 0;
			if ($rs_jasa_pemakaian_barang) {
				foreach ($rs_jasa_pemakaian_barang as $r_jasa_pemakaian_barang) {
					$worksheet->getCell($jasa_pemakaian_barang[$j] . $row)->setValue($r_jasa_pemakaian_barang->kode_barang);
					$worksheet->getCell($jasa_pemakaian_barang[$j + 1] . $row)->setValue($r_jasa_pemakaian_barang->satuan);
					$worksheet->getCell($jasa_pemakaian_barang[$j + 2] . $row)->setValue($r_jasa_pemakaian_barang->jumlah);
					$j += 3;
				}
			}

			for ($i = 0; $i < 23; $i++) {
				$spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
			}
			$no++;
			$row++;
		}

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		header('Content-Disposition: attachment; filename="data_jasa.xlsx"');
		$writer->save("php://output");
	}
}
