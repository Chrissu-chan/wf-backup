<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Welcome extends BaseController {

    public function index() {
        //$this->load->view('welcome');
	    $jarak = 10;

	    $tarif3 = 4000;
	    $tarif4 = 3000;
	    $tarif5 = 2000;
	    $tarif_lain = 1750;
	    $total = 0;

	    if ($jarak > 3) {
		    //jarak lebih dari 3, makan jarak yang 3km dihitung dulu
		    //kemudian diambil sisa jarak selebihnya
		    $total += 3 * $tarif3;
		    $jarak -= 3;

		    if ($jarak > 4) {
			    //sisa jarak lebih dari 4, makan jarak yang 4km dihitung dulu
			    //kemudian diambil sisa jarak selebihnya
			    $total += 4 * $tarif4;
			    $jarak -= 4;

			    if ($jarak > 5) {
				    //sisa jarak lebih dari 4, makan jarak yang 5km dihitung dulu
				    //kemudian diambil sisa jarak selebihnya
				    $total += 5 * $tarif5;
				    $jarak -= 5;

				    //sisa jarak dihitung dengan tarif lebihnya
				    $total += $jarak * $tarif_lain;
			    } else {
				    $total += $jarak * $tarif5; //sisa jarak kurang/sama dengan 5
			    }
		    } else {
			    $total += $jarak * $tarif4; //sisa jarak kurang/sama dengan 4
		    }
	    } else {
		    $total += $jarak * $tarif3;  //jarak kurang/sama dengan 3
	    }

	    print_r($total);
    }

    public function migrate_module($module_id) {
        $this->load->model('modules_m');
        $this->load->model('module_features_m');
        $this->load->model('module_feature_actions_m');
        //$this->load->model('module_feature_objects_m');
        $this->load->model('module_feature_action_methods_m');
        $this->transaction->start();
        $db2 = $this->load->database('db2', true);
        $module = $db2->where('id', $module_id)->get('modules')->row();
        $a = $this->modules_m->insert(array(
            'module' => $module->module,
            'description' => $module->description
        ));
        $features = $db2->where('module_id', $module_id)->get('module_features');
        foreach ($features->result() as $feature) {
            $b = $this->module_features_m->insert(array(
                'module_id' => $a->id,
                'feature' => $feature->feature,
                'class' => $feature->class
            ));
            $actions = $db2->where('module_feature_id', $feature->id)->get('module_feature_actions');
            foreach ($actions->result() as $action) {
                $c = $this->module_feature_actions_m->insert(array(
                    'module_feature_id' => $b->id,
                    'action' => $action->action,
                    'label' => $action->label
                ));
                $methods = $db2->where('module_feature_action_id', $action->id)->get('module_feature_action_methods');
                foreach ($methods->result() as $method) {
                    $this->module_feature_action_methods_m->insert(array(
                        'module_feature_action_id' => $c->id,
                        'method' => $method->method
                    ));
                }
            }
            /*$objects = $db2->where('module_feature_id', $feature->id)->get('module_feature_objects');
            foreach ($objects->result() as $object) {
                $this->module_feature_objects_m->insert(array(
                    'module_feature_id' => $feature->id,
                    'module_feature_action_id' => $object->module_feature_action_id,
                    'object_id' => $object->object_id,
                    'required' => 1
                ));
            }*/
        }
        $this->transaction->complete();
    }

    public function migrate_obat() {
        ini_set('max_execution_time', 30000);
        $this->transaction->start();
	        $this->load->model('barang_m');
	        $this->load->model('obat_m');
	        $this->load->model('satuan_m');
	        $this->load->model('konversi_satuan_m');
	        $this->load->model('jasa_m');
	        $this->load->model('fifo_m');
	        $this->load->model('produk_m');
	        $this->load->model('produk_cabang_m');
	        $this->load->model('produk_harga_m');
	        $this->load->model('produk_jasa_komisi_m');

	        $this->load->model('kategori_barang_m');
	        $this->load->model('kategori_obat_m');
	        $this->load->model('jenis_obat_m');
	        $this->load->model('kategori_obat_m');
	        $this->load->model('fungsi_obat_m');

	        $this->load->model('barang_kategori_obat_m');
	        $this->load->model('barang_fungsi_obat_m');

	        try {
			    $inputFileName = './'.$this->config->item('import_upload_path').'/import_master_data.xlsx';
			    $spreadsheet = IOFactory::load($inputFileName);
		    } catch(Exception $e) {
			    $this->redirect->with('error_message', $e->getMessage())->back();
		    }
	        $worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		    $format = array(
			    'A' => 'NO',
			    'B' => 'KODE LAMA',
			    'C' => 'KODE',
			    'D' => 'NAMA OBAT',
			    'E' => 'BARCODE',
			    'F' => 'KATEGORI BARANG',
			    'G' => 'PBF',
			    'H' => 'SATUAN 1',
			    'I' => 'SATUAN 2',
			    'J' => 'SATUAN 3',
			    'K' => 'SATUAN BELI',
			    'L' => 'JENIS OBAT',
			    'M' => 'KATEGORI OBAT',
			    'N' => 'FUNGSI OBAT',
			    'O' => 'KANDUNGAN OBAT',
			    'P' => 'DOSIS',
			    'Q' => 'STOK MINUS',
			    'R' => 'HPP',
			    'S' => 'DISKON (%)',
			    'T' => 'HNA',
			    'U' => 'PPN',
			    'V' => 'TOTAL'
		    );

		    foreach ($format as $key => $value) {
			    if ($worksheet['1'][$key] != $value) {
				    print_r($value).'<br>';
				    //$this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
			    }
		    }

		    for($i=2; $i<= count($worksheet); $i++) {
			    $no = $worksheet[$i]['A'];
			    $kode_lama = trim($worksheet[$i]['B']);
			    $kode = trim($worksheet[$i]['C']);
		        $nama = trim($worksheet[$i]['D']);
			    $barcode = trim($worksheet[$i]['E']);
			    $kategori_barang = trim($worksheet[$i]['F']);

			    //$jenis_barang = trim($worksheet[$i]['G']);

			    $satuan_beli = trim($worksheet[$i]['K']);
			    $jenis_obat = trim($worksheet[$i]['L']);
			    $kategori_obat = trim($worksheet[$i]['M']);
			    $fungsi_obat = trim($worksheet[$i]['N']);
			    $kandungan_obat = trim($worksheet[$i]['O']);
			    $dosis = trim($worksheet[$i]['P']);
			    $minus = trim($worksheet[$i]['Q']);
			    $hna = trim($worksheet[$i]['T']);
			    $ppn_persen = trim($worksheet[$i]['U']);
			    $hna_ppn = trim($worksheet[$i]['V']);
			    $total = trim($worksheet[$i]['V']);

		        $id_cabang = 7;

		        $user_cabang = $this->user_cabang_m->view('cabang')
		            ->where('user_cabang.id_cabang', $id_cabang)
		            ->where('user_cabang.id_user', $this->session->auth->id)->first();

		        $this->session->set_userdata('cabang', $user_cabang);
		        $db3 = $this->load->database('db3', true);

			    $r_barang = $this->barang_m->where('LOWER(nama)', strtolower($nama))->first();
			    if (!$r_barang) {
				    $r_obat = $db3->where('id_obat', $kode_lama)->get('obat')->row();
				    if ($r_obat) {
					    $satuan_utama = $db3->where('id_obat', $r_obat->id_obat)
						    ->where('pengali_pack', 1)
						    ->get('pack')
						    ->row();
					    if ($satuan_utama) {
						    if ($satuan_utama->net<>0) {
							    $laba = ($satuan_utama->harga_pack-$satuan_utama->net)/$satuan_utama->net*100;
						    } else {
							    $laba = 0;
						    }
						    if ($r_obat->obat_ye==1) {
							    $jasa = $this->jasa_m->insert(array(
									'jasa' => $nama,
									'id_kategori_jasa' => 1001
								));
								$produk = $this->produk_m->insert(array(
									'jenis' => 'jasa',
									'id_ref' => $jasa->id,
									'kode' => $kode,
									'barcode' => $barcode,
									'produk' => $jasa->jasa,
									'ppn_persen' => 0,
									'laba_persen' => 0
								));
								$this->produk_cabang_m->insert(array(
									'id_cabang' => $id_cabang,
									'id_produk' => $produk->id
								));
							    $this->produk_harga_m->insert(array(
								    'id_cabang' => 0,
								    'id_produk' => $produk->id,
								    'jumlah' => 1,
								    'margin_persen' => $laba,
								    'harga' => $satuan_utama->harga_pack,
								    'urutan' => 1,
								    'utama' => 1
							    ));
								$this->produk_harga_m->insert(array(
									'id_cabang' => $id_cabang,
									'id_produk' => $produk->id,
									'jumlah' => 1,
									'margin_persen' => $laba,
									'harga' => $satuan_utama->harga_pack,
									'urutan' => 1,
									'utama' => 1
								));
								$this->produk_jasa_komisi_m->insert(array(
									'id_cabang' => 0,
									'id_produk' => $produk->id,
									'id_petugas' => 0,
									'komisi' => 0
								));
						    } else {
							    $r_kategori_barang = $this->kategori_barang_m->where('kategori_barang', strtolower($kategori_barang))->first();
							    if (!$r_kategori_barang) {
								    $r_kategori_barang = $this->kategori_barang_m->insert(array(
									    'kategori_barang' => $kategori_barang,
									    'parent_id' => 0
								    ));
							    }
							    $barang = $this->barang_m->insert(array(
								    'kode' => $kode,
								    'barcode' => $barcode,
								    'nama' => $nama,
								    'id_kategori_barang' => $r_kategori_barang->id,
								    'id_jenis_barang' => 1,
								    'id_rak_gudang' => 0
							    ));
							    $satuan = $this->satuan_m->insert(array(
								    'grup' => $barang->id,
								    'satuan' => $satuan_utama->nama_pack,
								    'keterangan' => $barang->id.' - '.$nama
							    ));

							    if ($satuan_beli==$satuan->satuan) {
								    $r_satuan_beli = $satuan->id;
							    } else {
								    $r_satuan_beli = 0;
							    }

							    if (!$jenis_obat) {
								    $jenis_obat = 'OBAT';
							    }
							    $r_jenis_obat = $this->jenis_obat_m->where('jenis_obat', strtolower($jenis_obat))->first();
							    if (!$r_jenis_obat) {
								    $r_jenis_obat = $this->jenis_obat_m->insert(array(
									    'jenis_obat' => $jenis_obat
								    ));
							    }

							    $obat = $this->obat_m->insert(array(
								    'id_barang' => $barang->id,
								    'id_jenis_obat' => ($jenis_obat ? $r_jenis_obat->id : NULL),
								    'kandungan_obat' => $kandungan_obat,
								    'dosis' => $dosis,
								    'hpp' => $hna,
								    'ppn_persen' => 10,
								    'hna' => $hna_ppn,
								    'diskon_persen' => 0,
								    'total' => $total
							    ));

							    if ($kategori_obat) {
								    $r_kategori_obat = $this->kategori_obat_m->where('kategori_obat', strtolower($kategori_obat))->first();
								    if (!$r_kategori_obat) {
									    $r_kategori_obat = $this->kategori_obat_m->insert(array(
										    'kategori_obat' => $kategori_obat
									    ));
								    }

								    $this->barang_kategori_obat_m->insert(array(
									    'id_barang' => $barang->id,
									    'id_kategori_obat' => $r_kategori_obat->id
								    ));
							    }

							    if ($fungsi_obat) {
								    $r_fungsi_obat = $this->fungsi_obat_m->where('fungsi_obat', strtolower($fungsi_obat))->first();
								    if (!$r_fungsi_obat) {
									    $r_fungsi_obat = $this->fungsi_obat_m->insert(array(
										    'fungsi_obat' => $fungsi_obat
									    ));
								    }

								    $this->barang_fungsi_obat_m->insert(array(
									    'id_barang' => $barang->id,
									    'id_fungsi_obat' => $r_fungsi_obat->id
								    ));
							    }
							    $produk = $this->produk_m->insert(array(
								    'jenis' => 'barang',
								    'id_ref' => $barang->id,
								    'kode' => $barang->kode,
								    'barcode' => $barang->barcode,
								    'produk' => $barang->nama,
								    'ppn_persen' => 0,
								    'laba_persen' => 0
							    ));
							    $this->produk_cabang_m->insert(array(
								    'id_cabang' => $id_cabang,
								    'id_produk' => $produk->id
							    ));
							    $this->produk_harga_m->insert(array(
								    'id_cabang' => 0,
								    'id_produk' => $produk->id,
								    'id_satuan' => $satuan->id,
								    'jumlah' => 1,
								    'margin_persen' => $laba,
								    'harga' => $satuan_utama->harga_pack,
								    'urutan' => 1,
								    'utama' => 1
							    ));
							    $this->produk_harga_m->insert(array(
								    'id_cabang' => $id_cabang,
								    'id_produk' => $produk->id,
								    'id_satuan' => $satuan->id,
								    'jumlah' => 1,
								    'margin_persen' => $laba,
								    'harga' => $satuan_utama->harga_pack,
								    'urutan' => 1,
								    'utama' => 1
							    ));

							    $rs_konversi = $db3->where('id_obat', $r_obat->id_obat)
								    ->where('pengali_pack <>', 1)
								    ->get('pack')
								    ->result();
							    foreach ($rs_konversi as $r_konversi) {
								    if ($r_konversi->pengali_pack<>0) {
									    $konversi = $this->satuan_m->insert(array(
										    'grup' => $r_obat->id_obat,
										    'satuan' => $r_konversi->nama_pack,
										    'keterangan' => $r_obat->id_obat.' - '.$r_obat->nama_obat
									    ));

									    $this->konversi_satuan_m->insert(array(
										    'id_satuan_asal' => $konversi->id,
										    'id_satuan_tujuan' => $satuan->id,
										    'konversi' => $r_konversi->pengali_pack
									    ));

									    if ($satuan_beli==$konversi->satuan) {
										    $r_satuan_beli = $konversi->id;
									    } else {
										    $r_satuan_beli = 0;
									    }

									    $this->produk_harga_m->insert(array(
										    'id_cabang' => $id_cabang,
										    'id_produk' => $produk->id,
										    'id_satuan' => $konversi->id,
										    'jumlah' => 1,
										    'margin_laba' => $laba,
										    'harga' => $r_konversi->harga_pack,
										    'urutan' => 1,
										    'utama' => 1
									    ));
								    }
							    }

							    $this->barang_m->update($barang->id, array(
								    'id_satuan' => $satuan->id,
								    'id_satuan_beli' => $r_satuan_beli
							    ));

							    /*$masuk = $this->fifo_m->insert('masuk', array(
								    'jenis_mutasi' => 'migration',
								    'id_ref' => '0',
								    'tanggal_mutasi' => date('Y-m-d'),
								    'id_barang' => $barang->id,
								    'id_satuan' => $satuan->id,
								    'jumlah' => ($r_obat->stock_obat>=0) ? $r_obat->stock_obat : 0,
								    'total' => ($r_obat->stock_obat>=0) ? ($r_obat->stock_obat*$r_obat->beli) : 0,
								    'expired' => NULL,
								    'batch_number' => NULL
							    ));*/
						    }
					    }
				    } else {
					    echo $kode.'<br>';
				    }
			    }
		    }
        $this->transaction->complete();
    }

	public function migrate_stok() {
		ini_set('max_execution_time', 30000);
		$this->transaction->start();
		$this->load->model('barang_m');
		$this->load->model('obat_m');
		$this->load->model('satuan_m');
		$this->load->model('konversi_satuan_m');
		$this->load->model('jasa_m');
		$this->load->model('fifo_m');
		$this->load->model('produk_m');
		$this->load->model('produk_cabang_m');
		$this->load->model('produk_harga_m');
		$this->load->model('produk_jasa_komisi_m');

		$this->load->model('kategori_barang_m');
		$this->load->model('kategori_obat_m');
		$this->load->model('jenis_obat_m');
		$this->load->model('kategori_obat_m');
		$this->load->model('fungsi_obat_m');

		$this->load->model('barang_kategori_obat_m');
		$this->load->model('barang_fungsi_obat_m');

		try {
			$inputFileName = './'.$this->config->item('import_upload_path').'/import_master_data.xlsx';
			$spreadsheet = IOFactory::load($inputFileName);
		} catch(Exception $e) {
			$this->redirect->with('error_message', $e->getMessage())->back();
		}
		$worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		$format = array(
			'A' => 'NO',
			'B' => 'KODE LAMA',
			'C' => 'KODE',
			'D' => 'NAMA OBAT',
			'E' => 'BARCODE',
			'F' => 'KATEGORI BARANG',
			'G' => 'PBF',
			'H' => 'SATUAN 1',
			'I' => 'SATUAN 2',
			'J' => 'SATUAN 3',
			'K' => 'SATUAN BELI',
			'L' => 'JENIS OBAT',
			'M' => 'KATEGORI OBAT',
			'N' => 'FUNGSI OBAT',
			'O' => 'KANDUNGAN OBAT',
			'P' => 'DOSIS',
			'Q' => 'STOK MINUS',
			'R' => 'HPP',
			'S' => 'DISKON (%)',
			'T' => 'HNA',
			'U' => 'PPN',
			'V' => 'TOTAL'
		);

		foreach ($format as $key => $value) {
			if ($worksheet['1'][$key] != $value) {
				print_r($value).'<br>';
				//$this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
			}
		}

		for($i=2; $i<= count($worksheet); $i++) {
			$kode_lama = trim($worksheet[$i]['B']);
			$kode = trim($worksheet[$i]['C']);

			$id_cabang = 7;

			$user_cabang = $this->user_cabang_m->view('cabang')
				->where('user_cabang.id_cabang', $id_cabang)
				->where('user_cabang.id_user', $this->session->auth->id)->first();

			$this->session->set_userdata('cabang', $user_cabang);
			$db4 = $this->load->database('db4', true);

			$r_barang = $this->barang_m->where('kode', $kode)->first();
			if ($r_barang) {
				$r_obat = $db4->where('id_obat', $kode_lama)->get('obat')->row();
				if ($r_obat) {
					$masuk = $this->fifo_m->insert('masuk', array(
						'jenis_mutasi' => 'migration',
						'id_ref' => '0',
						'tanggal_mutasi' => date('Y-m-d'),
						'id_barang' => $r_barang->id,
						'id_satuan' => $r_barang->id_satuan,
						'jumlah' => ($r_obat->stock_obat>=0) ? $r_obat->stock_obat : 0,
						'total' => ($r_obat->stock_obat>=0) ? ($r_obat->stock_obat*$r_obat->beli) : 0,
						'expired' => NULL,
						'batch_number' => NULL
					));
				} else {
					echo $kode.'<br>';
				}
			}
		}
		$this->transaction->complete();
	}
}