<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('pelanggan_m');
        $this->load->model('penjualan_m');
        $this->load->model('penjualan_produk_m');
        $this->load->model('produk_m');
        $this->load->model('produk_harga_m');
        $this->load->model('produk_paket_m');
        $this->load->model('kas_bank_m');
        $this->load->model('kas_bank_cabang_m');
        $this->load->model('satuan_m');
        $this->load->model('piutang_m');
        $this->load->model('pembayaran_piutang_m');
        $this->load->model('jasa_pemakaian_barang_m');
        $this->load->model('fifo_m');
	    $this->load->model('shift_aktif_m');
	    $this->load->model('cabang_gudang_m');
        $this->load->library('autonumber');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
	        $jenis_penjualan = $this->input->get('jenis_penjualan');
	        if ($jenis_penjualan == 'cabang') {
		        $this->penjualan_m->scope('cabang');
	        } else {
		        $this->penjualan_m->scope('umum');
	        }
            return $this->datatable->resource($this->penjualan_m)
                ->view('penjualan')
                ->scope('cabang_aktif')
                ->edit_column('tanggal', function($model) {
                    return $this->localization->human_date($model->tanggal);
                })
                ->edit_column('jatuh_tempo', function($model) {
                    return $this->localization->human_date($model->jatuh_tempo);
                })
                ->edit_column('total', function($model) {
                    return $this->localization->number($model->total, 2);
                })
	            ->edit_column('batal', function($model){
		            return $this->localization->boolean($model->batal, '<span class="label label-danger">'.($model->jenis_batal ? $this->penjualan_m->enum('jenis_batal', $model->jenis_batal) : '').'</span>', '<span class="label label-success">'.$this->localization->lang('approved').'</span>');
	            })
                ->add_action('{nota} {view} {edit} {delete}', array(
	                'nota' => function($model) {
		                return $this->action->button('view', 'onclick="nota(\''.$model->id.'\')" class="btn btn-info btn-sm"', $this->localization->lang('nota'));
	                },
                    'edit' => function($model) {
	                    $html = '';
	                    if ($model->proses_jurnal == 'false' && $model->batal == 0) {
		                    return $this->action->link('edit', $this->route->name('transaksi.penjualan.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
	                    }
	                    return $html;
                    },
	                'delete' => function($model) {
		                $html = '';
		                if ($model->proses_jurnal == 'false' && $model->batal == 0) {
			                $html = '<div class="btn-group">
			                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                    '.$this->localization->lang('delete').' <span class="caret"></span>
			                    </button>
			                    <ul class="dropdown-menu dropdown-menu-right">
			                        <li>'.$this->action->link('delete', 'javascript:void(0)', 'onclick="remove('.$model->id.')"', $this->localization->lang('cancel')).'</li>
			                        <li>'.$this->action->link('delete', 'javascript:void(0)', 'onclick="returns('.$model->id.')"', $this->localization->lang('return')).'</li>
			                    </ul>
			                </div>';
		                }
		                return $html;
	                }
                ))
                ->generate();
        }
	    $jenis_penjualan = $this->input->get('jenis_penjualan');
	    $this->load->view('transaksi/penjualan/index', array(
		    'jenis_penjualan' => $jenis_penjualan
	    ));
    }

    public function view($id) {
        $model = $this->penjualan_m->view('penjualan')->find_or_fail($id);
        $model->penjualan_produk = $this->penjualan_produk_m->view('penjualan_produk')->where('id_penjualan', $id)->get();
        $this->load->view('transaksi/penjualan/view', array(
            'model' => $model
        ));
    }

    public function create() {
	    $jenis_penjualan = $this->input->get('jenis_penjualan');
	    if ($jenis_penjualan == 'cabang') {
		    $this->load->view('transaksi/penjualan_cabang/create', array(
			    'jenis_penjualan' => $jenis_penjualan
		    ));
	    } else {
		    $jenis_penjualan = 'umum';
		    $this->load->view('transaksi/penjualan/create', array(
			    'jenis_penjualan' => $jenis_penjualan
		    ));
	    }
    }

    public function store() {
        $post = $this->input->post();
        $validate = array(
            'id_pelanggan' => 'required',
            'tanggal' => 'required',
            'metode_pembayaran' => 'required',
            'id_kas_bank' => 'required'
        );
	    if ($post['metode_pembayaran'] == 'tunai') {
		    $validate['bayar'] = 'required|greater_than_equal_to['.$post['total'].']';
	    }
	    if ($post['jenis_penjualan'] == 'umum') {
		    if (!$post['form_add_produk_id_produk'] && !$post['penjualan_produk']) {
			    $validate['penjualan_produk[]'] = 'required';
		    }
		    if ($post['form_add_produk_id_produk']) {
			    if ($post['form_add_produk_jenis_produk'] == 'barang') {
				    $validate['form_add_produk_id_satuan'] = 'required';
			    }
			    $validate['form_add_produk_jumlah'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_produk_harga'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_produk_diskon_persen'] = 'numeric';
			    $validate['form_add_produk_potongan'] = 'numeric';
			    $validate['form_add_produk_subtotal'] = 'numeric';
			    $validate['form_add_produk_ppn_persen'] = 'numeric';
			    $validate['form_add_produk_total'] = 'numeric';

		    }
		    if (isset($post['penjualan_produk'])) {
			    foreach ($post['penjualan_produk'] as $key => $val) {
				    if ($post['penjualan_produk'][$key]['jenis_produk']=='barang') {
					    $validate['penjualan_produk['.$key.'][id_satuan]'] = array(
						    'field' => $this->localization->lang('penjualan_produk_satuan', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
						    'rules' => 'required'
					    );
				    }
				    $validate['penjualan_produk['.$key.'][jumlah]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_jumlah', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_produk['.$key.'][harga]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_harga', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_produk['.$key.'][diskon_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_diskon', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][potongan]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_potongan', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][subtotal]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_subtotal', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][ppn_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_ppn', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][total]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_total', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
			    }
		    }
	    } else {
		    if (!$post['form_add_barang_id_produk'] && !$post['penjualan_barang']) {
			    $validate['penjualan_barang[]'] = 'required';
		    }
		    if ($post['form_add_barang_id_produk']) {
			    $validate['form_add_barang_id_satuan'] = 'required';
			    $validate['form_add_barang_jumlah'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_barang_harga'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_barang_diskon_persen'] = 'numeric';
			    $validate['form_add_barang_potongan'] = 'numeric';
			    $validate['form_add_barang_subtotal'] = 'numeric';
			    $validate['form_add_barang_ppn_persen'] = 'numeric';
			    $validate['form_add_barang_total'] = 'numeric';

		    }
		    if (isset($post['penjualan_barang'])) {
			    foreach ($post['penjualan_barang'] as $key => $val) {
				    $validate['penjualan_barang['.$key.'][id_satuan]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_satuan', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required'
				    );
				    $validate['penjualan_barang['.$key.'][jumlah]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_jumlah', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_barang['.$key.'][harga]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_harga', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_barang['.$key.'][diskon_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_diskon', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][potongan]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_potongan', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][subtotal]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_subtotal', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][ppn_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_ppn', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][total]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_total', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
			    }
		    }
	    }
        $this->form_validation->validate($validate);
        $this->transaction->start();
	        $post['no_penjualan'] = $this->autonumber->resource($this->penjualan_m, 'no_penjualan')->format('PJ-{Y}{m}:4')->generate();
	        $post['id_shift_aktif'] = $this->shift_aktif_m->scope('cabang')->scope('aktif')->first_or_fail()->id;
            $result = $this->penjualan_m->insert($post);

	        if ($post['jenis_penjualan'] == 'umum') {
		        if ($post['form_add_produk_id_produk']) {
			        $post['penjualan_produk'][0] = array(
				        'id_produk' => $post['form_add_produk_id_produk'],
				        'kode_produk' => $post['form_add_produk_kode_produk'],
				        'nama_produk' => $post['form_add_produk_nama_produk'],
				        'jenis_produk' => $post['form_add_produk_jenis_produk'],
				        'satuan' => $post['form_add_produk_satuan'],
				        'id_satuan' => $post['form_add_produk_id_satuan'],
				        'jumlah' => $post['form_add_produk_jumlah'],
				        'harga' => $post['form_add_produk_harga'],
				        'diskon' => $post['form_add_produk_diskon'],
				        'diskon_persen' => $post['form_add_produk_diskon_persen'],
				        'potongan' => $post['form_add_produk_potongan'],
				        'subtotal' => $post['form_add_produk_subtotal'],
				        'ppn' => $post['form_add_produk_ppn'],
				        'ppn_persen' => $post['form_add_produk_ppn_persen'],
				        'tuslah' => $post['form_add_produk_tuslah'],
				        'total' => $post['form_add_produk_total']
			        );
		        }

		        foreach ($post['penjualan_produk'] as $penjualan_produk) {
			        $penjualan_produk['id_penjualan'] = $result->id;
			        $r_penjualan_produk = $this->penjualan_produk_m->insert($penjualan_produk);
			        $r_produk = $this->produk_m->find_or_fail($r_penjualan_produk->id_produk);
			        if ($r_produk->jenis == 'barang') {
				        $this->fifo_m->insert('keluar', array(
					        'jenis_mutasi' => 'penjualan',
					        'id_ref' => $r_penjualan_produk->id,
					        'tanggal_mutasi' => $post['tanggal'],
					        'id_barang' => $r_produk->id_ref,
					        'id_satuan' => $r_penjualan_produk->id_satuan,
					        'jumlah' => $r_penjualan_produk->jumlah,
					        'total' => $r_penjualan_produk->total
				        ));
			        } else if ($r_produk->jenis == 'jasa') {
				        $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk->id_ref)->get();
				        if ($rs_jasa_pemakaian_barang) {
					        foreach ($rs_jasa_pemakaian_barang as $jasa_pemakaian_barang) {
						        $this->fifo_m->insert('keluar', array(
							        'jenis_mutasi' => 'penjualan',
							        'id_ref' => $r_penjualan_produk->id,
							        'tanggal_mutasi' => $post['tanggal'],
							        'id_barang' => $jasa_pemakaian_barang->id_barang,
							        'id_satuan' => $jasa_pemakaian_barang->id_satuan,
							        'jumlah' => $jasa_pemakaian_barang->jumlah * $r_penjualan_produk->jumlah,
							        'total' => $r_penjualan_produk->total
						        ));
					        }
				        }
			        } else if ($r_produk->jenis == 'paket') {
				        $rs_produk_paket = $this->produk_paket_m->where('id_produk', $r_produk->id)->get();
				        if ($rs_produk_paket) {
					        foreach ($rs_produk_paket as $produk_paket) {
						        $r_produk_detail = $this->produk_m->find_or_fail($produk_paket->id_produk_detail);
						        if ($r_produk_detail->jenis == 'barang') {
							        $this->fifo_m->insert('keluar', array(
								        'jenis_mutasi' => 'penjualan',
								        'id_ref' => $r_penjualan_produk->id,
								        'tanggal_mutasi' => $post['tanggal'],
								        'id_barang' => $r_produk_detail->id_ref,
								        'id_satuan' => $produk_paket->id_satuan,
								        'jumlah' => $r_penjualan_produk->jumlah * $produk_paket->jumlah,
								        'total' => $r_penjualan_produk->total
							        ));
						        } else if ($r_produk_detail->jenis == 'jasa') {
							        $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk_detail->id_ref)->get();
							        if ($rs_jasa_pemakaian_barang) {
								        foreach ($rs_jasa_pemakaian_barang as $jasa_pemakaian_barang) {
									        $this->fifo_m->insert('keluar', array(
										        'jenis_mutasi' => 'penjualan',
										        'id_ref' => $r_penjualan_produk->id,
										        'tanggal_mutasi' => $post['tanggal'],
										        'id_barang' => $jasa_pemakaian_barang->id_barang,
										        'id_satuan' => $jasa_pemakaian_barang->id_satuan,
										        'jumlah' => $jasa_pemakaian_barang->jumlah * $produk_paket->jumlah * $r_penjualan_produk->jumlah,
										        'total' => $r_penjualan_produk->total
									        ));
								        }
							        }
						        }
					        }
				        }
			        }
		        }
	        } else {
		        if ($post['form_add_barang_id_produk']) {
			        $post['penjualan_barang'][0] = array(
				        'id_produk' => $post['form_add_barang_id_produk'],
				        'kode_produk' => $post['form_add_barang_kode_produk'],
				        'nama_produk' => $post['form_add_barang_nama_produk'],
				        'id_satuan' => $post['form_add_barang_id_satuan'],
				        'jumlah' => $post['form_add_barang_jumlah'],
				        'harga' => $post['form_add_barang_harga'],
				        'diskon' => $post['form_add_barang_diskon'],
				        'diskon_persen' => $post['form_add_barang_diskon_persen'],
				        'potongan' => $post['form_add_barang_potongan'],
				        'subtotal' => $post['form_add_barang_subtotal'],
				        'ppn' => $post['form_add_barang_ppn'],
				        'ppn_persen' => $post['form_add_barang_ppn_persen'],
				        'tuslah' => $post['form_add_barang_tuslah'],
				        'total' => $post['form_add_barang_total']
			        );
		        }

		        foreach ($post['penjualan_barang'] as $penjualan_barang) {
			        $penjualan_barang['id_penjualan'] = $result->id;
			        $r_penjualan_barang = $this->penjualan_produk_m->insert($penjualan_barang);
			        $this->fifo_m->insert('keluar', array(
				        'jenis_mutasi' => 'penjualan',
				        'id_ref' => $r_penjualan_barang->id,
				        'tanggal_mutasi' => $post['tanggal'],
				        'id_barang' => $r_penjualan_barang->id_produk,
				        'id_satuan' => $r_penjualan_barang->id_satuan,
				        'jumlah' => $r_penjualan_barang->jumlah,
				        'total' => $r_penjualan_barang->total
			        ));
		        }
	        }

            if ($post['metode_pembayaran'] == 'utang') {
	            if ($post['jenis_penjualan'] == 'umum') {
		            $pelanggan = $this->pelanggan_m->find_or_fail($post['id_pelanggan'])->nama;
		            $jenis_piutang = 'penjualan';
	            } else {
		            $pelanggan = $this->cabang_m->find_or_fail($post['id_pelanggan'])->nama;
		            $jenis_piutang = 'penjualan_cabang';
	            }
                $rs_piutang = $this->piutang_m->insert(array(
                    'no_piutang' => $post['no_penjualan'],
                    'jenis_piutang' => $jenis_piutang,
                    'no_ref' => $post['id_pelanggan'],
                    'nama' => $pelanggan,
                    'tanggal_piutang' => $post['tanggal'],
                    'tanggal_jatuh_tempo' => $post['jatuh_tempo'],
                    'jumlah_piutang' => $post['total'],
                    'jumlah_bayar' => $post['bayar'],
                    'sisa_piutang' => $this->localization->number_value($post['total']) - $this->localization->number_value($post['bayar']),
                    'keterangan' => 'Piutang penjualan',
                    'lunas' => (($this->localization->number_value($post['total']) - $this->localization->number_value($post['bayar'])) == 0) ? 1 : 0
                ));

                if ($rs_piutang) {
                    $this->pembayaran_piutang_m->insert(array(
                        'id_piutang' => $rs_piutang->id,
                        'tanggal_bayar' => $post['tanggal'],
                        'jumlah_bayar' => $post['bayar'],
                        'id_kas_bank' => $post['id_kas_bank'],
                        'no_ref_pembayaran' => $post['no_ref'],
                        'keterangan' => 'Uang muka'
                    ));
                }
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('print', $result->id)->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('penjualan'))))->back();
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('penjualan'))))->back();
        }
    }

    public function edit($id) {
        $model = $this->penjualan_m->view('penjualan')->find($id);
	    if ($model->jenis_penjualan == 'umum') {
		    $model->penjualan_produk = array();
		    foreach ($this->penjualan_produk_m->view('penjualan_produk')->where('id_penjualan', $id)->get() as $penjualan_produk) {
			    $model->penjualan_produk[$penjualan_produk->id] = $penjualan_produk;
		    }
	    } else {
		    $model->penjualan_barang = array();
		    foreach ($this->penjualan_produk_m->view('penjualan_barang')->where('id_penjualan', $id)->get() as $penjualan_barang) {
			    $barang = $this->barang_m->view('barang')->find_or_fail($penjualan_barang->id_produk);
			    $penjualan_barang->barang_id_satuan = $barang->id_satuan;
			    $penjualan_barang->barang_satuan = $barang->satuan;
			    $model->penjualan_barang[$penjualan_barang->id] = $penjualan_barang;
		    }
	    }

	    if ($model->jenis_penjualan == 'umum') {
		    $this->load->view('transaksi/penjualan/edit', array(
			    'model' => $model,
			    'jenis_penjualan' => $model->jenis_penjualan
		    ));
	    } else {
		    $this->load->view('transaksi/penjualan_cabang/edit', array(
			    'model' => $model,
			    'jenis_penjualan' => $model->jenis_penjualan
		    ));
	    }
    }

    public function update($id) {
	    $post = $this->input->post();
	    $validate = array(
		    'id_pelanggan' => 'required',
		    'tanggal' => 'required',
		    'metode_pembayaran' => 'required',
		    'id_kas_bank' => 'required'
	    );
	    if ($post['metode_pembayaran'] == 'tunai') {
		    $validate['bayar'] = 'required|greater_than_equal_to['.$post['total'].']';
	    }
	    if ($post['jenis_penjualan'] == 'umum') {
		    if (!$post['form_add_produk_id_produk'] && !$post['penjualan_produk']) {
			    $validate['penjualan_produk[]'] = 'required';
		    }
		    if ($post['form_add_produk_id_produk']) {
			    if ($post['form_add_produk_jenis_produk'] == 'barang') {
				    $validate['form_add_produk_id_satuan'] = 'required';
			    }
			    $validate['form_add_produk_jumlah'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_produk_harga'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_produk_diskon_persen'] = 'numeric';
			    $validate['form_add_produk_potongan'] = 'numeric';
			    $validate['form_add_produk_subtotal'] = 'numeric';
			    $validate['form_add_produk_ppn_persen'] = 'numeric';
			    $validate['form_add_produk_total'] = 'numeric';

		    }
		    if (isset($post['penjualan_produk'])) {
			    foreach ($post['penjualan_produk'] as $key => $val) {
				    if ($post['penjualan_produk'][$key]['jenis_produk']=='barang') {
					    $validate['penjualan_produk['.$key.'][id_satuan]'] = array(
						    'field' => $this->localization->lang('penjualan_produk_satuan', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
						    'rules' => 'required'
					    );
				    }
				    $validate['penjualan_produk['.$key.'][jumlah]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_jumlah', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_produk['.$key.'][harga]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_harga', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_produk['.$key.'][diskon_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_diskon', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][potongan]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_potongan', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][subtotal]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_subtotal', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][ppn_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_ppn', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_produk['.$key.'][total]'] = array(
					    'field' => $this->localization->lang('penjualan_produk_total', array('name' => $post['penjualan_produk'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
			    }
		    }
	    } else {
		    if (!$post['form_add_barang_id_produk'] && !$post['penjualan_barang']) {
			    $validate['penjualan_barang[]'] = 'required';
		    }
		    if ($post['form_add_barang_id_produk']) {
			    $validate['form_add_barang_id_satuan'] = 'required';
			    $validate['form_add_barang_jumlah'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_barang_harga'] = 'required|numeric|greater_than[0]';
			    $validate['form_add_barang_diskon_persen'] = 'numeric';
			    $validate['form_add_barang_potongan'] = 'numeric';
			    $validate['form_add_barang_subtotal'] = 'numeric';
			    $validate['form_add_barang_ppn_persen'] = 'numeric';
			    $validate['form_add_barang_total'] = 'numeric';

		    }
		    if (isset($post['penjualan_barang'])) {
			    foreach ($post['penjualan_barang'] as $key => $val) {
				    $validate['penjualan_barang['.$key.'][id_satuan]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_satuan', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required'
				    );
				    $validate['penjualan_barang['.$key.'][jumlah]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_jumlah', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_barang['.$key.'][harga]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_harga', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'required|numeric|greater_than[0]'
				    );
				    $validate['penjualan_barang['.$key.'][diskon_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_diskon', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][potongan]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_potongan', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][subtotal]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_subtotal', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][ppn_persen]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_ppn', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
				    $validate['penjualan_barang['.$key.'][total]'] = array(
					    'field' => $this->localization->lang('penjualan_barang_total', array('name' => $post['penjualan_barang'][$key]['nama_produk'])),
					    'rules' => 'numeric'
				    );
			    }
		    }
	    }
	    $this->form_validation->validate($validate);
        $this->transaction->start();
	        $post['id_shift_aktif'] = $this->shift_aktif_m->scope('cabang')->scope('aktif')->first_or_fail()->id;
	        $this->penjualan_m->update($id, $post);
            $rs_penjualan_produk = $this->penjualan_produk_m->where('id_penjualan', $id)->get();
            $this->penjualan_produk_m->where('id_penjualan', $id)->delete();

            foreach ($rs_penjualan_produk as $r_penjualan_produk) {
	            $this->fifo_m->_delete($r_penjualan_produk->id, 'keluar');
            }

		    if ($post['jenis_penjualan'] == 'umum') {
			    if ($post['form_add_produk_id_produk']) {
				    $post['penjualan_produk'][0] = array(
					    'id_produk' => $post['form_add_produk_id_produk'],
					    'kode_produk' => $post['form_add_produk_kode_produk'],
					    'nama_produk' => $post['form_add_produk_nama_produk'],
					    'jenis_produk' => $post['form_add_produk_jenis_produk'],
					    'satuan' => $post['form_add_produk_satuan'],
					    'id_satuan' => $post['form_add_produk_id_satuan'],
					    'jumlah' => $post['form_add_produk_jumlah'],
					    'harga' => $post['form_add_produk_harga'],
					    'diskon' => $post['form_add_produk_diskon'],
					    'diskon_persen' => $post['form_add_produk_diskon_persen'],
					    'potongan' => $post['form_add_produk_potongan'],
					    'subtotal' => $post['form_add_produk_subtotal'],
					    'ppn' => $post['form_add_produk_ppn'],
					    'ppn_persen' => $post['form_add_produk_ppn_persen'],
					    'tuslah' => $post['form_add_produk_tuslah'],
					    'total' => $post['form_add_produk_total']
				    );
			    }

			    foreach ($post['penjualan_produk'] as $penjualan_produk) {
				    $penjualan_produk['id_penjualan'] = $id;
				    $r_penjualan_produk = $this->penjualan_produk_m->insert($penjualan_produk);
				    $r_produk = $this->produk_m->find_or_fail($r_penjualan_produk->id_produk);
				    if ($r_produk->jenis == 'barang') {
					    $this->fifo_m->insert('keluar', array(
						    'jenis_mutasi' => 'penjualan',
						    'id_ref' => $r_penjualan_produk->id,
						    'tanggal_mutasi' => $post['tanggal'],
						    'id_barang' => $r_produk->id_ref,
						    'id_satuan' => $r_penjualan_produk->id_satuan,
						    'jumlah' => $r_penjualan_produk->jumlah,
						    'total' => $r_penjualan_produk->total
					    ));
				    } else if ($r_produk->jenis == 'jasa') {
					    $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk->id_ref)->get();
					    if ($rs_jasa_pemakaian_barang) {
						    foreach ($rs_jasa_pemakaian_barang as $jasa_pemakaian_barang) {
							    $this->fifo_m->insert('keluar', array(
								    'jenis_mutasi' => 'penjualan',
								    'id_ref' => $r_penjualan_produk->id,
								    'tanggal_mutasi' => $post['tanggal'],
								    'id_barang' => $jasa_pemakaian_barang->id_barang,
								    'id_satuan' => $jasa_pemakaian_barang->id_satuan,
								    'jumlah' => $jasa_pemakaian_barang->jumlah * $r_penjualan_produk->jumlah,
								    'total' => $r_penjualan_produk->total
							    ));
						    }
					    }
				    } else if ($r_produk->jenis == 'paket') {
					    $rs_produk_paket = $this->produk_paket_m->where('id_produk', $r_produk->id)->get();
					    if ($rs_produk_paket) {
						    foreach ($rs_produk_paket as $produk_paket) {
							    $r_produk_detail = $this->produk_m->find_or_fail($produk_paket->id_produk_detail);
							    if ($r_produk_detail->jenis == 'barang') {
								    $this->fifo_m->insert('keluar', array(
									    'jenis_mutasi' => 'penjualan',
									    'id_ref' => $r_penjualan_produk->id,
									    'tanggal_mutasi' => $post['tanggal'],
									    'id_barang' => $r_produk_detail->id_ref,
									    'id_satuan' => $produk_paket->id_satuan,
									    'jumlah' => $r_penjualan_produk->jumlah * $produk_paket->jumlah,
									    'total' => $r_penjualan_produk->total
								    ));
							    } else if ($r_produk_detail->jenis == 'jasa') {
								    $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk_detail->id_ref)->get();
								    if ($rs_jasa_pemakaian_barang) {
									    foreach ($rs_jasa_pemakaian_barang as $jasa_pemakaian_barang) {
										    $this->fifo_m->insert('keluar', array(
											    'jenis_mutasi' => 'penjualan',
											    'id_ref' => $r_penjualan_produk->id,
											    'tanggal_mutasi' => $post['tanggal'],
											    'id_barang' => $jasa_pemakaian_barang->id_barang,
											    'id_satuan' => $jasa_pemakaian_barang->id_satuan,
											    'jumlah' => $jasa_pemakaian_barang->jumlah * $produk_paket->jumlah * $r_penjualan_produk->jumlah,
											    'total' => $r_penjualan_produk->total
										    ));
									    }
								    }
							    }
						    }
					    }
				    }
			    }
		    } else {
			    if ($post['form_add_barang_id_produk']) {
				    $post['penjualan_barang'][0] = array(
					    'id_produk' => $post['form_add_barang_id_produk'],
					    'kode_produk' => $post['form_add_barang_kode_produk'],
					    'nama_produk' => $post['form_add_barang_nama_produk'],
					    'satuan' => $post['form_add_barang_satuan'],
					    'id_satuan' => $post['form_add_barang_id_satuan'],
					    'jumlah' => $post['form_add_barang_jumlah'],
					    'harga' => $post['form_add_barang_harga'],
					    'diskon' => $post['form_add_barang_diskon'],
					    'diskon_persen' => $post['form_add_barang_diskon_persen'],
					    'potongan' => $post['form_add_barang_potongan'],
					    'subtotal' => $post['form_add_barang_subtotal'],
					    'ppn' => $post['form_add_barang_ppn'],
					    'ppn_persen' => $post['form_add_barang_ppn_persen'],
					    'tuslah' => $post['form_add_barang_tuslah'],
					    'total' => $post['form_add_barang_total']
				    );
			    }

			    foreach ($post['penjualan_barang'] as $penjualan_barang) {
				    $penjualan_barang['id_penjualan'] = $id;
				    $r_penjualan_barang = $this->penjualan_produk_m->insert($penjualan_barang);
				    $this->fifo_m->insert('keluar', array(
					    'jenis_mutasi' => 'penjualan',
					    'id_ref' => $r_penjualan_barang->id,
					    'tanggal_mutasi' => $post['tanggal'],
					    'id_barang' => $r_penjualan_barang->id_produk,
					    'id_satuan' => $r_penjualan_barang->id_satuan,
					    'jumlah' => $r_penjualan_barang->jumlah,
					    'total' => $r_penjualan_barang->total
				    ));
			    }
		    }
        if ($this->transaction->complete()) {
            $this->redirect->with('print', $id)->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('penjualan'))))->route('transaksi.penjualan');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('penjualan'))))->back();
        }
    }

    public function delete($id) {
	    $post = $this->input->post();
        $this->transaction->start();
		    $this->penjualan_m->update($id, array(
			    'batal' => 1,
			    'jenis_batal' => $post['jenis_batal'],
			    'deleted_by' => $this->auth->username,
			    'deleted_at' => date('Y-m-d H:i:s')
		    ));
	        $r_penjualan = $this->penjualan_m->find_or_fail($id);
            //$this->penjualan_m->delete($id);
	        //$this->penjualan_produk_m->where('id_penjualan', $id)->delete();
	        $rs_penjualan_produk = $this->penjualan_produk_m->where('id_penjualan', $id)->get();
		    foreach ($rs_penjualan_produk as $r_penjualan_produk) {
			    if ($r_penjualan->jenis_penjualan == 'umum') {
				    $r_produk = $this->produk_m->find_or_fail($r_penjualan_produk->id_produk);
				    if ($r_produk->jenis == 'barang') {
					    $this->fifo_m->delete($r_penjualan_produk->id, 'keluar');
				    } else if ($r_produk->jenis == 'jasa') {
					    $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk->id_ref)->get();
					    if ($rs_jasa_pemakaian_barang) {
						    $this->fifo_m->delete($r_penjualan_produk->id, 'keluar');
					    }
				    } else if ($r_produk->jenis == 'paket') {
					    $rs_produk_paket = $this->produk_paket_m->where('id_produk', $r_produk->id)->get();
					    if ($rs_produk_paket) {
						    foreach ($rs_produk_paket as $produk_paket) {
							    $r_produk_detail = $this->produk_m->find_or_fail($produk_paket->id_produk_detail);
							    if ($r_produk_detail->jenis == 'barang') {
								    $this->fifo_m->delete($r_penjualan_produk->id, 'keluar');
							    } else if ($r_produk_detail->jenis == 'jasa') {
								    $rs_jasa_pemakaian_barang = $this->jasa_pemakaian_barang_m->where('id_jasa', $r_produk_detail->id_ref)->get();
								    if ($rs_jasa_pemakaian_barang) {
									    $this->fifo_m->delete($r_penjualan_produk->id, 'keluar');
								    }
							    }
						    }
					    }
				    }
			    } else {
				    $this->fifo_m->delete($r_penjualan_produk->id, 'keluar');
			    }
		    }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('penjualan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('penjualan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

	public function nota($id) {
		$model = $this->penjualan_m->view('penjualan')->find_or_fail($id);
		$model->cabang = $this->cabang_gudang_m->view('cabang_gudang')->scope('aktif_cabang')->first_or_fail();
		$model->penjualan_produk = $this->penjualan_produk_m->view('penjualan_produk')->where('id_penjualan', $id)->get();
		$this->load->view('transaksi/penjualan/nota', array(
			'model' => $model
		));
	}
}