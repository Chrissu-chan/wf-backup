<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Obat extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('barang_m');
        $this->load->model('obat_m');
        $this->load->model('barang_obat_m');
        $this->load->model('kategori_barang_m');
        $this->load->model('jenis_barang_m');
        $this->load->model('satuan_m');
        $this->load->model('konversi_satuan_m');
        $this->load->model('jenis_obat_m');
        $this->load->model('kategori_obat_m');
        $this->load->model('fungsi_obat_m');
        $this->load->model('barang_kategori_obat_m');
        $this->load->model('barang_fungsi_obat_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('produk_m');
        $this->load->model('produk_cabang_m');
        $this->load->model('produk_harga_m');
	    $this->load->model('broadcast_harga_produk_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->barang_obat_m)
                ->view('obat')
                ->edit_column('minus', function($model) {
                    return $this->localization->boolean($model->minus);
                })
                ->filter(function($model) {
                    if ($kategori = $this->input->get('kategori')) {
                        $model->where('barang.id_kategori_barang', $kategori);
                    }
                    if ($jenis = $this->input->get('jenis')) {
                        $model->where('barang.id_jenis_barang', $jenis);
                    }
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/obat/index');
    }

    public function view($id) {
        $model = $this->barang_obat_m->view('obat')->where('id_barang', $id)->first_or_fail();
        $this->load->view('master/obat/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/obat/create');
    }

    public function store() {
        $post = $this->input->post();
        $validate = array(
            'kode' => 'required|is_unique[barang.kode]',
	        'barcode' => 'callback_validate_barcode',
            'nama' => 'required',
            'id_kategori_barang' => 'required',
            'id_jenis_barang' => 'required',
            'id_jenis_obat' => 'required',
            'dosis' => 'required|numeric',
            'hpp' => 'required|numeric',
            'diskon_persen' => 'required|numeric',
            'hna' => 'required|numeric',
            'ppn_persen' => 'required|numeric',
            'total' => 'required|numeric'
        );
        foreach ($post['satuan_barang'] as $key => $val) {
            if ($key == 1) {
                $validate['satuan_barang['.$key.'][satuan]'] = array(
                    'field' => $this->localization->lang('satuan_satuan_utama', array('name' => $post['satuan_barang'][$key]['satuan'])),
                    'rules' => 'required'
                );
            }
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
            $post['minus'] = isset($post['minus']) ? $post['minus'] : 0;
            $result = $this->barang_obat_m->insert($post);
            $post['id_barang'] = $result->id;
            $this->obat_m->insert($post);

            if (isset($post['satuan_barang'])) {
                $id_satuan = '';
                $id_satuan_beli = '';
                foreach ($post['satuan_barang'] as $key => $satuan) {
                    if ($key == 1) {
                        $satuan_utama = $this->satuan_m->insert(array(
                            'satuan' => $satuan['satuan'],
                            'grup' => $result->id
                        ));
                        $id_satuan = $satuan_utama->id;
                        $id_satuan_beli = (isset($satuan['satuan_beli']) ? $id_satuan : $id_satuan_beli);
                    } else {
                        if ($satuan['satuan']) {
                            $satuan_konversi = $this->satuan_m->insert(array(
                                'satuan' => $satuan['satuan'],
                                'grup' => $result->id
                            ));
                            $this->konversi_satuan_m->insert(array(
                                'id_satuan_asal' => $satuan_konversi->id,
                                'id_satuan_tujuan' => $satuan_utama->id,
                                'konversi' => $satuan['konversi']
                            ));
                            $id_satuan_beli = (isset($satuan['satuan_beli']) ? $satuan_konversi->id : $id_satuan_beli);
                        }
                    }
                }
                $this->barang_obat_m->update($result->id, array(
                    'id_satuan' => $id_satuan,
                    'id_satuan_beli' => $id_satuan_beli
                ));
            }

            if (isset($post['kategori_obat'])) {
                $rs_barang_kategori_obat = array();
                foreach ($post['kategori_obat'] as $kategori_obat) {
                    $rs_barang_kategori_obat[] = array(
                        'id_barang' => $result->id,
                        'id_kategori_obat' => $kategori_obat
                    );
                }
                if ($rs_barang_kategori_obat) {
                    $this->barang_kategori_obat_m->insert_batch($rs_barang_kategori_obat);
                }
            }

            if (isset($post['fungsi_obat'])) {
                $rs_barang_fungsi_obat = array();
                foreach ($post['fungsi_obat'] as $fungsi_obat) {
                    $rs_barang_fungsi_obat[] = array(
                        'id_barang' => $result->id,
                        'id_fungsi_obat' => $fungsi_obat
                    );
                }
                if ($rs_barang_fungsi_obat) {
                    $this->barang_fungsi_obat_m->insert_batch($rs_barang_fungsi_obat);
                }
            }

        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->barang_obat_m->view('barang')->find($id);
	    $model->satuan_barang[1] = array(
            'id_satuan' => $model->id_satuan,
            'satuan' => $model->satuan,
            'konversi' => 1,
            'satuan_beli' => ($model->id_satuan == $model->id_satuan_beli ? 1 : 0)
        );
        foreach ($this->satuan_m->view('satuan')->where('id_satuan_tujuan', $model->id_satuan)->get() as $satuan_konversi) {
            $model->satuan_barang[] = array(
                'id_satuan' => $satuan_konversi->id,
                'satuan' => $satuan_konversi->satuan,
                'konversi' => $satuan_konversi->konversi,
                'satuan_beli' => ($model->id_satuan_beli == $satuan_konversi->id ? 1 : 0)
            );
        }
        $rs_kategori_obat = $this->barang_kategori_obat_m->where('id_barang', $id)->get();
        $rs_fungsi_obat = $this->barang_fungsi_obat_m->where('id_barang', $id)->get();
        foreach ($rs_kategori_obat as $r_kategori_obat) {
            $model->kategori_obat[] = $r_kategori_obat->id_kategori_obat;
        }
        foreach ($rs_fungsi_obat as $r_fungsi_obat) {
            $model->fungsi_obat[] = $r_fungsi_obat->id_fungsi_obat;
        }
        $this->load->view('master/obat/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $validate = array(
            //'kode' => 'required|is_unique[barang.kode.'.$id.']',
	        'barcode' => 'callback_validate_barcode['.$id.']',
            'nama' => 'required',
            'id_kategori_barang' => 'required',
            'id_jenis_barang' => 'required',
            'id_jenis_obat' => 'required',
            'dosis' => 'required|numeric',
            'hpp' => 'required|numeric',
            'diskon_persen' => 'required|numeric',
            'hna' => 'required|numeric',
            'ppn_persen' => 'required|numeric',
            'total' => 'required|numeric'
        );
        foreach ($post['satuan_barang'] as $key => $val) {
            if ($key == 1) {
                $validate['satuan_barang['.$key.'][satuan]'] = array(
                    'field' => $this->localization->lang('satuan_satuan_utama', array('name' => $post['satuan_barang'][$key]['satuan'])),
                    'rules' => 'required'
                );
            }
        }
        $this->form_validation->validate($validate);
	    $this->transaction->start();
	        $post['minus'] = isset($post['minus']) ? $post['minus'] : 0;
	        $result = $this->barang_obat_m->update($id, $post);
	        $this->obat_m->where('id_barang', $id)->update($post);

		    if (isset($post['satuan_barang'])) {
			    $id_satuan = '';
			    $id_satuan_beli = '';
			    foreach ($post['satuan_barang'] as $key => $satuan) {
				    if ($key == 1) {
					    $this->satuan_m->update($satuan['id_satuan'], array(
						    'satuan' => $satuan['satuan'],
						    'grup' => $id
					    ));
					    $id_satuan = $satuan['id_satuan'];
					    $id_satuan_beli = (isset($satuan['satuan_beli']) ? $id_satuan : $id_satuan_beli);
				    } else {
					    if ($satuan['satuan']) {
						    if ($satuan['id_satuan']) {
							    $this->satuan_m->update($satuan['id_satuan'], array(
								    'satuan' => $satuan['satuan'],
								    'grup' => $id
							    ));
                                $this->db->set('konversi', $satuan['konversi'])
                                    ->where('id_satuan_asal', $satuan['id_satuan'])
                                    ->where('id_satuan_tujuan', $id_satuan)
                                    ->update('konversi_satuan');
							    $id_satuan_beli = (isset($satuan['satuan_beli']) ? $satuan['id_satuan'] : $id_satuan_beli);
						    } else {
							    $satuan_konversi = $this->satuan_m->insert(array(
								    'satuan' => $satuan['satuan'],
								    'grup' => $id
							    ));
                                $this->konversi_satuan_m->insert(array(
                                    'id_satuan_asal' => $satuan_konversi->id,
                                    'id_satuan_tujuan' => $id_satuan,
                                    'konversi' => $satuan['konversi']
                                ));
							    $id_satuan_beli = (isset($satuan['satuan_beli']) ? $satuan_konversi->id : $id_satuan_beli);
						    }
					    } else {
						    if ($satuan['id_satuan']) {
							    $this->satuan_m->delete($satuan['id_satuan']);
                                $this->konversi_satuan_m->where('id_satuan_tujuan', $satuan['id_satuan'])
                                    ->delete();
						    }
					    }
				    }
			    }
			    $this->barang_obat_m->update($id, array(
				    'id_satuan' => $id_satuan,
				    'id_satuan_beli' => $id_satuan_beli
			    ));
		    }

	        $this->barang_kategori_obat_m->where('id_barang', $id)->delete();
	        if (isset($post['kategori_obat'])) {
	            $rs_barang_kategori_obat = array();
	            foreach ($post['kategori_obat'] as $kategori_obat) {
	                $rs_barang_kategori_obat[] = array(
	                    'id_barang' => $id,
	                    'id_kategori_obat' => $kategori_obat
	                );
	            }
	            if ($rs_barang_kategori_obat) {
	                $this->barang_kategori_obat_m->insert_batch($rs_barang_kategori_obat);
	            }
	        }

	        $this->barang_fungsi_obat_m->where('id_barang', $id)->delete();
	        if (isset($post['fungsi_obat'])) {
	            $rs_barang_fungsi_obat = array();
	            foreach ($post['fungsi_obat'] as $fungsi_obat) {
	                $rs_barang_fungsi_obat[] = array(
	                    'id_barang' => $id,
	                    'id_fungsi_obat' => $fungsi_obat
	                );
	            }
	            if ($rs_barang_fungsi_obat) {
	                $this->barang_fungsi_obat_m->insert_batch($rs_barang_fungsi_obat);
	            }
	        }

        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
	    $this->transaction->start();
	        $this->barang_kategori_obat_m->where('id_barang', $id)->delete();
	        $this->barang_fungsi_obat_m->where('id_barang', $id)->delete();
            $this->barang_obat_m->delete($id);
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('obat')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('obat')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_json() {
        $id_barang = $this->input->get('id_barang');
	    $id_satuan = $this->input->get('id_satuan');
        $result = $this->barang_obat_m->view('barang')->find_or_fail($id_barang);
	    $konversi = $this->konversi_satuan_m->convert($id_satuan, $result->id_satuan, 1);
	    $result->hpp *= $konversi;
	    $result->hna *= $konversi;
	    $result->total *= $konversi;
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import() {
        $this->load->view('master/obat/import');
    }

    public function import_store() {
        $errors = array();
        $success_count = 0;
        $config['upload_path'] = './'.$this->config->item('import_upload_path');
        $config['allowed_types'] = $this->config->item('import_allowed_file_types');
        $config['max_size'] = $this->config->item('document_max_size');
        $this->load->library('upload', $config);
        if (!$this->upload->has('file')) {
            $this->redirect->with('error_message', $this->localization->lang('upload_required'))->back();
        }
        if(!$this->upload->do_upload('file')) {
            $this->redirect->with('error_message', $this->upload->display_errors())->back();
        }
        $file_name = $this->upload->data('file_name');
        try {
            $inputFileName = $config['upload_path'].'/'.$file_name;
            $spreadsheet = IOFactory::load($inputFileName);
        } catch(Exception $e) {
            $this->redirect->with('error_message', $e->getMessage())->back();
        }

        $worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $format = array(
            'A' => 'No',
            'B' => 'Kode',
            'C' => 'Barcode',
            'D' => 'Nama',
            'E' => 'Kategori Barang',
            'F' => 'Jenis Barang',
            'G' => 'Satuan',
            'V' => 'Satuan Beli',
            'W' => 'Jenis Obat',
            'X' => 'Kategori Obat',
            'Y' => 'Fungsi Obat',
            'Z' => 'Kandungan Obat',
            'AA' => 'Dosis',
            'AB' => 'Stok Minus',
            'AC' => 'HNA',
            'AD' => 'PPN (%)',
            'AE' => 'HNA+PPN',
            'AF' => 'DISKON (%)',
            'AG' => 'Total',
            'AH' => 'Jadikan Produk',
            'AI' => 'Harga Jual'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

	    $record_broadcast_harga_produk = array();
        for($i = 7; $i<=count($worksheet); $i++) {
	        $this->transaction->start();

            $no = $worksheet[$i]['A'];
            $kode = trim($worksheet[$i]['B']);
            $barcode = trim($worksheet[$i]['C']);
            $nama = trim($worksheet[$i]['D']);
            $kategori_barang = trim($worksheet[$i]['E']);
            $jenis_barang = trim($worksheet[$i]['F']);
            $id_satuan = trim($worksheet[$i]['G']);
            $satuan = trim($worksheet[$i]['H']);
	        $satuan_beli = trim($worksheet[$i]['V']);
	        $jenis_obat = trim($worksheet[$i]['W']);
	        $kategori_obat = explode(";", trim($worksheet[$i]['X']));
	        $fungsi_obat = explode(";", trim($worksheet[$i]['Y']));
	        $kandungan_obat = trim($worksheet[$i]['Z']);
	        $dosis = trim($worksheet[$i]['AA']);
	        $minus = trim($worksheet[$i]['AB']);
	        $hna = trim($worksheet[$i]['AC']);
	        $ppn_persen = trim($worksheet[$i]['AD']);
	        $hna_ppn = trim($worksheet[$i]['AE']);
	        $diskon_persen = trim($worksheet[$i]['AF']);
	        $total = trim($worksheet[$i]['AG']);
	        $produk = trim($worksheet[$i]['AH']);
	        $margin_persen = trim($worksheet[$i]['AI']);
	        $harga = trim($worksheet[$i]['AJ']);

            $data = array(
                'kode' => $kode,
                'barcode' => $barcode,
                'nama' => $nama,
                'kategori_barang' => $kategori_barang,
                'jenis_barang' =>  $jenis_barang,
                'satuan' => $satuan,
                'satuan_beli' => $satuan_beli,
                'jenis_obat' => $jenis_obat,
                'kandungan_obat' => $kandungan_obat,
	            'dosis' => $dosis,
	            'minus' => $minus,
	            'hpp' => $hna,
	            'ppn_persen' => 10, //$ppn_persen,
	            'hna' => $hna_ppn,
	            'diskon_persen' => $diskon_persen,
	            'total' => $total
            );

	        $barang = $this->barang_obat_m->where('LOWER(kode)', strtolower($kode))->first();
	        if ($barang) {
		        $validation = array(
			        'kode' => 'required',
			        'barcode' => 'callback_validate_barcode['.$barang->id.']',
			        'nama' => 'required',
			        'kategori_barang' => 'required',
			        'jenis_barang' => 'required',
			        'satuan' => 'required',
			        'satuan_beli' => 'required',
			        'jenis_obat' => 'required'
		        );
	        } else {
		        $validation = array(
			        'kode' => 'required',
			        'barcode' => 'callback_validate_barcode',
			        'nama' => 'required',
			        'kategori_barang' => 'required',
			        'jenis_barang' => 'required',
			        'satuan' => 'required',
			        'satuan_beli' => 'required',
			        'jenis_obat' => 'required'
		        );
	        }
            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate($validation, true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }



            $r_kategori_barang = $this->kategori_barang_m->where('LOWER(kategori_barang)', strtolower($kategori_barang))->first();
            if(!$r_kategori_barang) {
                $r_kategori_barang = $this->kategori_barang_m->insert(array(
                    'kategori_barang' => $kategori_barang,
                    'parent_id' => 0
                ));
            }
            $data['id_kategori_barang'] = $r_kategori_barang->id;

            $r_jenis_barang = $this->jenis_barang_m->where('LOWER(jenis_barang)', strtolower($jenis_barang))->first();
            if(!$r_jenis_barang) {
                $r_jenis_barang = $this->jenis_barang_m->insert(array(
                    'jenis_barang' => $jenis_barang
                ));
            }
            $data['id_jenis_barang'] = $r_jenis_barang->id;

	        $r_satuan = $this->satuan_m->find($id_satuan);
	        if ($r_satuan) {
		        $this->satuan_m->update($r_satuan->id, array(
			        'satuan' => $satuan
		        ));
	        } else {
		        $r_satuan = $this->satuan_m->insert(array(
			        'satuan' => $satuan
		        ));
	        }
            $data['id_satuan'] = $r_satuan->id;

            $r_jenis_obat = $this->jenis_obat_m->where('LOWER(jenis_obat)', strtolower($jenis_obat))->first();
            if(!$r_jenis_obat) {
                $r_jenis_obat = $this->jenis_obat_m->insert(array(
                    'jenis_obat' => $jenis_obat
                ));
            }
            $data['id_jenis_obat'] = $r_jenis_obat->id;

	        if ($barang) {
		        $this->barang_obat_m->update($barang->id, $data);
		        $obat = $this->obat_m->where('id_barang', $barang->id)->first();
		        $this->obat_m->update($obat->id, $data);
	        } else {
		        $barang = $this->barang_obat_m->insert($data);
		        $data['id_barang'] = $barang->id;
		        $this->obat_m->insert($data);
	        }

	        $this->satuan_m->update($r_satuan->id, array(
		        'grup' => $barang->id
	        ));

	        $r_produk = $this->produk_m->where('LOWER(kode)', strtolower($kode))->first();
	        if ($produk && $r_satuan) {
		        if ($r_produk) {
			        $this->produk_m->update($r_produk->id, array(
				        'kode' => $kode,
				        'barcode' => $barcode,
				        'produk' => $nama,
				        'jenis' => 'barang',
				        'id_ref' => $barang->id,
				        'ppn_persen' => 0, //$produk_ppn_persen,
				        'laba_persen' => 0, //$produk_laba_persen
			        ));
			        $r_produk_harga = $this->produk_harga_m->where('id_produk', $r_produk->id)
				        ->where('id_satuan', $r_satuan->id)
				        ->where('jumlah', 1)
				        ->where('utama', 1)
				        ->first();

			        $this->produk_harga_m->update($r_produk_harga->id, array(
				        'id_cabang' => 0,
				        'id_produk' => $r_produk->id,
				        'id_satuan' => $r_satuan->id,
				        'jumlah' => 1,
				        'margin_persen' => $margin_persen,
				        'harga' => $harga,
				        'urutan' => 1,
				        'utama' => 1
			        ));

			        $record_broadcast_harga_produk[] = array(
				        'id_cabang' => 0,
				        'tanggal' => date('Y-m-d'),
				        'id_produk' => $r_produk->id,
				        'id_satuan' => $r_satuan->id,
				        'jumlah' => 1,
				        'harga_awal' => $r_produk_harga->harga,
				        'harga_akhir' => $harga
			        );
		        } else {
			        $r_produk = $this->produk_m->insert(array(
				        'kode' => $kode,
				        'barcode' => $barcode,
				        'produk' => $nama,
				        'jenis' => 'barang',
				        'id_ref' => $barang->id,
				        'ppn_persen' => 0, //$produk_ppn_persen,
				        'laba_persen' => 0, //$produk_laba_persen
			        ));

			        $this->produk_cabang_m->insert(array(
				        'id_cabang' => 0,
				        'id_produk' => $r_produk->id
			        ));

			        $this->produk_harga_m->insert(array(
				        'id_cabang' => 0,
				        'id_produk' => $r_produk->id,
				        'id_satuan' => $r_satuan->id,
				        'jumlah' => 1,
				        'margin_persen' => $margin_persen,
				        'harga' => $harga,
				        'urutan' => 1,
				        'utama' => 1
			        ));
		        }
	        }

	        $konversi_satuan = array('J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U');
	        $produk_harga = array('J' => array('AK', 'AL'), 'M' => array('AM', 'AN'), 'P' => array('AO', 'AP'), 'S' => array('AQ', 'AR'));

	        for ($j=0; $j<12; $j++) {
		        $id_satuan = trim($worksheet[$i][$konversi_satuan[$j]]);
		        $satuan = trim($worksheet[$i][$konversi_satuan[$j+1]]);
		        $konversi = trim($worksheet[$i][$konversi_satuan[$j+2]]);
		        if ($satuan) {
			        if ($id_satuan) {
				        $r_satuan_konversi = $this->satuan_m->find($id_satuan);
				        $this->satuan_m->update($r_satuan_konversi->id, array(
					        'satuan' => $satuan,
					        'grup' => $barang->id
				        ));
				        $r_konversi_satuan = $this->konversi_satuan_m->where('id_satuan_asal', $r_satuan_konversi->id)
					        ->where('id_satuan_tujuan', $r_satuan->id)
					        ->first();
				        $this->konversi_satuan_m->update($r_konversi_satuan->id, array(
					        'id_satuan_asal' => $r_satuan_konversi->id,
					        'id_satuan_tujuan' => $r_satuan->id,
					        'konversi' => $konversi
				        ));
			        } else {
				        $r_satuan_konversi = $this->satuan_m->insert(array(
					        'satuan' => $satuan,
					        'grup' => $barang->id
				        ));

				        $this->konversi_satuan_m->insert(array(
					        'id_satuan_asal' => $r_satuan_konversi->id,
					        'id_satuan_tujuan' => $r_satuan->id,
					        'konversi' => $konversi
				        ));
			        }
		        } else {
			        if ($id_satuan) {
				        $this->satuan_m->delete($id_satuan);
				        $this->db->where('id_satuan_asal', $id_satuan)
					        ->or_where('id_satuan_tujuan', $id_satuan)
					        ->delete('konversi_satuan');
				        $this->db->where('id_produk', $r_produk->id)
					        ->where('id_satuan', $id_satuan)
					        ->delete('produk_harga');
			        }
		        }

		        $margin_persen = trim($worksheet[$i][$produk_harga[$konversi_satuan[$j]][0]]);
		        $harga = trim($worksheet[$i][$produk_harga[$konversi_satuan[$j]][1]]);
		        if ($produk && $satuan && $harga > 0) {
			        $r_produk_harga = $this->produk_harga_m->where('id_produk', $r_produk->id)
				        ->where('id_satuan', $r_satuan_konversi->id)
				        ->where('jumlah', 1)
				        ->where('urutan', 1)
				        ->first();
			        if ($r_produk_harga) {
				        $this->produk_harga_m->update($r_produk_harga->id, array(
					        'id_cabang' => 0,
					        'id_produk' => $r_produk->id,
					        'id_satuan' => $r_satuan_konversi->id,
					        'jumlah' => 1,
					        'margin_persen' => $margin_persen,
					        'harga' => $harga,
					        'urutan' => 1,
					        'utama' => 0
				        ));

				        $record_broadcast_harga_produk[] = array(
					        'id_cabang' => 0,
					        'tanggal' => date('Y-m-d'),
					        'id_produk' => $r_produk->id,
					        'id_satuan' => $r_satuan_konversi->id,
					        'jumlah' => 1,
					        'margin_persen' => $margin_persen,
					        'harga_awal' => $r_produk_harga->harga,
					        'harga_akhir' => $harga
				        );
			        } else {
				        $this->produk_harga_m->insert(array(
					        'id_cabang' => 0,
					        'id_produk' => $r_produk->id,
					        'id_satuan' => $r_satuan_konversi->id,
					        'jumlah' => 1,
					        'margin_persen' => $margin_persen,
					        'harga' => $harga,
					        'urutan' => 1,
					        'utama' => 0
				        ));
			        }
		        }
		        $j += 2;
	        }

	        if ($satuan_beli) {
		        $r_satuan_beli = $this->satuan_m->where('LOWER(satuan)', $satuan_beli)
			        ->where('grup', $barang->id)
			        ->first();
		        if (!$r_satuan_beli) {
			        $r_satuan_beli = $this->satuan_m->insert(array(
				        'satuan' => $satuan_beli,
				        'grup' => $barang->id
			        ));

			        $this->konversi_satuan_m->insert(array(
				        'id_satuan_asal' => $r_satuan_beli->id,
				        'id_satuan_tujuan' => $r_satuan->id,
				        'konversi' => 0
			        ));
		        }
		        $this->barang_obat_m->update($barang->id, array(
			        'id_satuan_beli' => $r_satuan_beli->id
		        ));
	        }

	        $this->barang_kategori_obat_m->where('id_barang', $barang->id)->delete();
	        if (count($kategori_obat) > 0 && $kategori_obat[0] != '') {
		        $rs_kategori_obat = array();
		        foreach ($kategori_obat as $val) {
			        $r_kategori_obat = $this->kategori_obat_m->where('LOWER(kategori_obat)', strtolower($val))->first();
			        if (!$r_kategori_obat) {
				        $r_kategori_obat = $this->kategori_obat_m->insert(array(
					        'kategori_obat' => $val
				        ));
			        }
			        $rs_kategori_obat[] = array(
				        'id_barang' => $barang->id,
				        'id_kategori_obat' => $r_kategori_obat->id
			        );
		        }
		        if ($rs_kategori_obat) {
			        $this->barang_kategori_obat_m->insert_batch($rs_kategori_obat);
		        }
	        }

	        $this->barang_fungsi_obat_m->where('id_barang', $barang->id)->delete();
	        if (count($fungsi_obat) > 0 && $fungsi_obat[0] != '') {
		        $rs_fungsi_obat = array();
		        foreach ($fungsi_obat as $val) {
			        $r_fungsi_obat = $this->fungsi_obat_m->where('LOWER(fungsi_obat)', strtolower($val))->first();
			        if (!$r_fungsi_obat) {
				        $r_fungsi_obat = $this->fungsi_obat_m->insert(array(
					        'fungsi_obat' => $val
				        ));
			        }
			        $rs_fungsi_obat[] = array(
				        'id_barang' => $barang->id,
				        'id_fungsi_obat' => $r_fungsi_obat->id
			        );
		        }
		        if ($rs_fungsi_obat) {
			        $this->barang_fungsi_obat_m->insert_batch($rs_fungsi_obat);
		        }
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

    public function download_format() {
        $this->load->helper('download');
        $path = base_url('public/master/obat/import_obat.xlsx');
        $data = file_get_contents($path);
        $name = 'import_obat.xlsx';
        return force_download($name, $data);
    }

    public function export() {
        ini_set("pcre.backtrack_limit", "100000000");
        ini_set("pcre.recursion_limit", "100000000");
        
	    $cabang = $this->cabang_gudang_m->view('cabang_gudang')->scope('aktif_cabang')->first_or_fail();
        $spreadsheet = IOFactory::load('public/master/obat/import_obat.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        $cols = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');

        $style=array(
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

	    $konversi_satuan = array('J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U');
	    $produk_harga = array('J' => array('AK', 'AL'), 'M' => array('AM', 'AN'), 'P' => array('AO', 'AP'), 'S' => array('AQ', 'AR'));

        $rs_barang = $this->barang_obat_m->view('obat_export')->get();        
        $arr_id_satuan = array();
        $arr_id_produk = array();
        foreach ($rs_barang as $barang) {            
            $arr_id_satuan[] = $barang->id_satuan;
            if ($barang->produk) {
                $arr_id_produk[] = $barang->id_produk;
            }
        }        
        $rs_konversi_satuan = $this->konversi_satuan_m->view('konversi_satuan')
		        ->where('id_satuan_tujuan IN (\''.implode('\',\'',$arr_id_satuan).'\')', null, false)
                ->get();            
        $arr_konversi_satuan = array();
        foreach ($rs_konversi_satuan as $r_konversi_satuan) {
            $arr_konversi_satuan[$r_konversi_satuan->id_satuan_tujuan][$r_konversi_satuan->id] = $r_konversi_satuan;
        }                
        $rs_produk_harga = $this->produk_harga_m->where('id_produk IN (\''.implode('\',\'',$arr_id_produk).'\')', null, false)
            ->where('jumlah', 1)
            ->where('urutan', 1)
            ->get();
        $arr_produk_harga = array();
        foreach ($rs_produk_harga as $r_produk_harga) {
            $arr_produk_harga[$r_produk_harga->id_produk][$r_produk_harga->id_satuan] = $r_produk_harga;
        }
        $row = 7;
        $no = 1;        
        $worksheet->getCell('A1')->setValue('Data Obat');
        $worksheet->getCell('A2')->setValue($cabang->nama);
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_barang as $key => $barang) {
            $rs_konversi_satuan = isset($arr_konversi_satuan[$barang->id_satuan]) ? $arr_konversi_satuan[$barang->id_satuan] : null;          
              
            $worksheet->getCell('A'.$row)->setValue($no);
            $worksheet->getCell('B'.$row)->setValue($barang->kode);
            $worksheet->getCell('C'.$row)->setValue($barang->barcode);
            $worksheet->getCell('D'.$row)->setValue($barang->nama);
            $worksheet->getCell('E'.$row)->setValue($barang->kategori_barang);
            $worksheet->getCell('F'.$row)->setValue($barang->jenis_barang);
            $worksheet->getCell('G'.$row)->setValue($barang->id_satuan);
            $worksheet->getCell('H'.$row)->setValue($barang->satuan);
            $worksheet->getCell('I'.$row)->setValue(1);

	        if ($barang->produk) {
		        $worksheet->getCell('AI'.$row)->setValue($barang->margin_persen);
		        $worksheet->getCell('AJ'.$row)->setValue($barang->harga);
	        }

	        $j=0;
	        if ($rs_konversi_satuan) {
		        foreach ($rs_konversi_satuan as $r_konversi_satuan) {
			        $worksheet->getCell($konversi_satuan[$j].$row)->setValue($r_konversi_satuan->id_satuan_asal);
			        $worksheet->getCell($konversi_satuan[$j+1].$row)->setValue($r_konversi_satuan->satuan_asal);
			        $worksheet->getCell($konversi_satuan[$j+2].$row)->setValue($r_konversi_satuan->konversi);

			        if ($barang->produk) {
                        $r_produk_harga = isset($arr_produk_harga[$barang->id_produk][$r_konversi_satuan->id_satuan_asal]) ? $arr_produk_harga[$barang->id_produk][$r_konversi_satuan->id_satuan_asal] : null;                      
				        if ($r_produk_harga) {
					        $worksheet->getCell($produk_harga[$konversi_satuan[$j]][0].$row)->setValue($r_produk_harga->margin_persen);
					        $worksheet->getCell($produk_harga[$konversi_satuan[$j]][1].$row)->setValue($r_produk_harga->harga);
				        }
			        }

			        $j+=3;
		        }
	        }

            $worksheet->getCell('V'.$row)->setValue($barang->satuan_beli);
            $worksheet->getCell('W'.$row)->setValue($barang->jenis_obat);
            $worksheet->getCell('X'.$row)->setValue(str_replace(', ', ';', $barang->kategori_obat));
            $worksheet->getCell('Y'.$row)->setValue(str_replace(', ', ';', $barang->fungsi_obat));
            $worksheet->getCell('Z'.$row)->setValue($barang->kandungan_obat);
            $worksheet->getCell('AA'.$row)->setValue($barang->dosis);
            $worksheet->getCell('AB'.$row)->setValue($barang->minus);
            $worksheet->getCell('AC'.$row)->setValue($barang->hpp);
            $worksheet->getCell('AD'.$row)->setValue($barang->ppn_persen);
            $worksheet->getCell('AE'.$row)->setValue($barang->hna);
            $worksheet->getCell('AF'.$row)->setValue($barang->diskon_persen);
            $worksheet->getCell('AG'.$row)->setValue($barang->total);
	        if ($barang->produk) {
		        $worksheet->getCell('AH'.$row)->setValue(1);
	        } else {
		        $worksheet->getCell('AH'.$row)->setValue(0);
            }                                
            
            $no++;
            $row++;
        }
        
        $spreadsheet->getActiveSheet()->getStyle($cols[0].'7:'.$cols[43].$row)->applyFromArray($style);
        

	    foreach ($worksheet->getColumnDimensions() as $colDim) {
		    $colDim->setAutoSize(true);
	    }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="data_obat.xlsx"');
        $writer->save("php://output");
    }

	public function validate_barcode($str, $attr) {
		if ($this->input->post('barcode')) {
			if ($attr) {
				$this->barang_m->where('id != ', $attr);
			}
			$r_barang = $this->barang_m->where('barcode', $str)->first();
			if ($r_barang) {
				$this->form_validation->set_message('validate_barcode', 'The {field} field must contain a unique value.');
				return FALSE;
			}
		}
	}
}