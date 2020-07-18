<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('supplier_m');
        $this->load->model('supplier_cabang_m');
        $this->load->model('pembelian_m');
        $this->load->model('pembelian_barang_m');
        $this->load->model('kas_bank_m');
        $this->load->model('kas_bank_cabang_m');
        $this->load->model('barang_m');
        $this->load->model('barang_obat_m');
        $this->load->model('obat_m');
        $this->load->model('satuan_m');
        $this->load->model('konversi_satuan_m');
        $this->load->model('utang_m');
        $this->load->model('pembayaran_utang_m');
        $this->load->model('fifo_m');
        $this->load->model('shift_aktif_m');
        $this->load->model('kategori_supplier_m');
        $this->load->model('jenis_supplier_m');
        $this->load->model('kota_m');
        $this->load->model('bank_m');
	    $this->load->library('autonumber');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->pembelian_m)
                ->view('pembelian')
                ->scope('cabang')
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
		            return $this->localization->boolean($model->batal, '<span class="label label-danger">'.($model->jenis_batal ? $this->pembelian_m->enum('jenis_batal', $model->jenis_batal) : '').'</span>', '<span class="label label-success">'.$this->localization->lang('approved').'</span>');
	            })
                ->add_action('{nota} {view} {edit} {delete}', array(
	                'nota' => function($model) {
		                return $this->action->button('view', 'onclick="nota(\''.$model->id.'\')" class="btn btn-info btn-sm"', $this->localization->lang('nota'));
	                },
	                'edit' => function($model) {
		                $html = '';
		                if ($model->proses_jurnal == 'false' && $model->batal == 0) {
			                $html = $this->action->link('edit', $this->route->name('transaksi.pembelian.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
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
        $this->load->view('transaksi/pembelian/index');
    }

    public function view($id) {
        $model = $this->pembelian_m->view('pembelian')->find_or_fail($id);
        $model->pembelian_barang = $this->pembelian_barang_m->view('pembelian_barang')->where('id_pembelian', $id)->get();
        $this->load->view('transaksi/pembelian/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('transaksi/pembelian/create');
    }

	public function create_supplier() {
		$this->load->view('transaksi/pembelian/supplier/supplier');
	}

    public function store() {
        $post = $this->input->post();
        $validate = array(
            'id_supplier' => 'required',
            'no_pembelian' => 'required|is_unique[pembelian.no_pembelian]',
            'tanggal' => 'required',
            'metode_pembayaran' => 'required',
            'id_kas_bank' => 'required'
        );
	    if (!$post['form_add_barang_id_barang'] && !$post['pembelian_barang']) {
		    $validate['pembelian_barang[]'] = 'required';
	    }
	    if ($post['form_add_barang_id_barang']) {
		    $validate['form_add_barang_id_satuan'] = 'required';
		    $validate['form_add_barang_jumlah'] = 'required|numeric|greater_than[0]';
		    $validate['form_add_barang_harga'] = 'required|numeric|greater_than[0]';
		    $validate['form_add_barang_diskon_persen'] = 'numeric';
		    $validate['form_add_barang_potongan'] = 'numeric';
		    $validate['form_add_barang_subtotal'] = 'numeric';
		    $validate['form_add_barang_ppn_persen'] = 'numeric';
		    $validate['form_add_barang_total'] = 'numeric';

	    }
	    if (isset($post['pembelian_barang'])) {
		    foreach ($post['pembelian_barang'] as $key => $val) {
			    $validate['pembelian_barang['.$key.'][id_satuan]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_satuan', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required'
			    );
			    $validate['pembelian_barang['.$key.'][jumlah]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_jumlah', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required|numeric|greater_than[0]'
			    );
			    $validate['pembelian_barang['.$key.'][harga]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_harga', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required|numeric|greater_than[0]'
			    );
			    $validate['pembelian_barang['.$key.'][diskon_persen]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_diskon', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][potongan]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_potongan', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][subtotal]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_subtotal', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][ppn_persen]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_ppn', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][total]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_total', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
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
            $result = $this->pembelian_m->insert($post);

		    if ($post['form_add_barang_id_barang']) {
			    $post['pembelian_barang'][0] = array(
				    'id_barang' => $post['form_add_barang_id_barang'],
				    'kode_barang' => $post['form_add_barang_kode_barang'],
				    'nama_barang' => $post['form_add_barang_nama_barang'],
				    'barang_id_satuan' => $post['form_add_barang_barang_id_satuan'],
				    'barang_satuan' => $post['form_add_barang_barang_satuan'],
				    'id_satuan' => $post['form_add_barang_id_satuan'],
				    'jumlah' => $post['form_add_barang_jumlah'],
				    'harga' => $post['form_add_barang_harga'],
				    'diskon' => $post['form_add_barang_diskon'],
				    'diskon_persen' => $post['form_add_barang_diskon_persen'],
				    'potongan' => $post['form_add_barang_potongan'],
				    'subtotal' => $post['form_add_barang_subtotal'],
				    'ppn' => $post['form_add_barang_ppn'],
				    'ppn_persen' => $post['form_add_barang_ppn_persen'],
				    'total' => $post['form_add_barang_total'],
				    'expired' => $post['form_add_barang_expired'],
				    'batch_number' => $post['form_add_barang_batch_number']
			    );
		    }

            foreach ($post['pembelian_barang'] as $pembelian_barang) {
                $pembelian_barang['id_pembelian'] = $result->id;
                $result_pembelian_barang = $this->pembelian_barang_m->insert($pembelian_barang);
                if (!$pembelian_barang['expired']) {
                    $pembelian_barang['expired'] = NULL;
                }
	            if (!$pembelian_barang['batch_number']) {
		            $pembelian_barang['batch_number'] = NULL;
	            }
                $this->fifo_m->insert('masuk', array(
                    'jenis_mutasi' => 'pembelian',
                    'id_ref' => $result_pembelian_barang->id,
                    'tanggal_mutasi' => $post['tanggal'],
                    'id_barang' => $pembelian_barang['id_barang'],
                    'id_satuan' => $pembelian_barang['id_satuan'],
                    'jumlah' => $pembelian_barang['jumlah'],
                    'total' => $pembelian_barang['total'],
                    'expired' => $pembelian_barang['expired'],
	                'batch_number' => $pembelian_barang['batch_number']
                ));
	            
	            $barang = $this->barang_obat_m->view('barang')->find_or_fail($pembelian_barang['id_barang']);
	            $konversi = $this->konversi_satuan_m->convert($pembelian_barang['id_satuan'], $barang->id_satuan, 1);
	            $hpp = $this->localization->number_value($pembelian_barang['harga']) / $konversi;
	            $hna = $hpp + (($barang->ppn_persen / 100) * $hpp);
	            $total = $hna - (($barang->diskon_persen / 100) * $hna);
	            $this->obat_m->update($barang->id_obat, array(
		            'hpp' => $hpp,
		            'hna' => $hna,
		            'diskon_persen' => $pembelian_barang['diskon_persen'],
		            'total' => $total
	            ));
            }

            if ($post['metode_pembayaran'] == 'utang') {
                $rs_utang = $this->utang_m->insert(array(
                    'no_utang' => $post['no_pembelian'],
                    'jenis_utang' => 'pembelian',
                    'no_ref' => $post['id_supplier'],
                    'nama' => $this->supplier_m->find($post['id_supplier'])->nama,
                    'tanggal_utang' => $post['tanggal'],
                    'tanggal_jatuh_tempo' => $post['jatuh_tempo'],
                    'jumlah_utang' => $post['total'],
                    'jumlah_bayar' => $post['uang_muka'],
                    'sisa_utang' => $this->localization->number_value($post['total']) - $this->localization->number_value($post['uang_muka']),
                    'keterangan' => 'Utang pembelian',
                    'lunas' => (($this->localization->number_value($post['total']) - $this->localization->number_value($post['uang_muka'])) == 0) ? 1 : 0
                ));

                if ($rs_utang) {
                    $this->pembayaran_utang_m->insert(array(
                        'id_utang' => $rs_utang->id,
                        'tanggal_bayar' => $post['tanggal'],
                        'jumlah_bayar' => $post['uang_muka'],
                        'id_kas_bank' => $post['id_kas_bank'],
                        'no_ref_pembayaran' => $post['no_ref'],
                        'keterangan' => 'Uang muka'
                    ));
                }
            }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('pembelian'))))->route('transaksi.pembelian');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pembelian'))))->back();
        }
    }

    public function edit($id) {
        $model = $this->pembelian_m->view('pembelian')->find_or_fail($id);
	    $model->pembelian_barang = array();
	    foreach ($this->pembelian_barang_m->view('pembelian_barang')->where('id_pembelian', $id)->get() as $pembelian_barang) {
		    $model->pembelian_barang[$pembelian_barang->id] = $pembelian_barang;
	    }
        $this->load->view('transaksi/pembelian/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $validate = array(
            'id_supplier' => 'required',
            'tanggal' => 'required',
            'id_kas_bank' => 'required',
            'pembelian_barang[]' => 'required'
        );
	    if (!$post['form_add_barang_id_barang'] && !$post['pembelian_barang']) {
		    $validate['pembelian_barang[]'] = 'required';
	    }
	    if ($post['form_add_barang_id_barang']) {
		    $validate['form_add_barang_id_satuan'] = 'required';
		    $validate['form_add_barang_jumlah'] = 'required|numeric|greater_than[0]';
		    $validate['form_add_barang_harga'] = 'required|numeric|greater_than[0]';
		    $validate['form_add_barang_diskon_persen'] = 'numeric';
		    $validate['form_add_barang_potongan'] = 'numeric';
		    $validate['form_add_barang_subtotal'] = 'numeric';
		    $validate['form_add_barang_ppn_persen'] = 'numeric';
		    $validate['form_add_barang_total'] = 'numeric';

	    }
	    if (isset($post['pembelian_barang'])) {
		    foreach ($post['pembelian_barang'] as $key => $val) {
			    $validate['pembelian_barang['.$key.'][id_satuan]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_satuan', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required'
			    );
			    $validate['pembelian_barang['.$key.'][jumlah]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_jumlah', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required|numeric|greater_than[0]'
			    );
			    $validate['pembelian_barang['.$key.'][harga]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_harga', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'required|numeric|greater_than[0]'
			    );
			    $validate['pembelian_barang['.$key.'][diskon_persen]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_diskon', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][potongan]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_potongan', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][subtotal]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_subtotal', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][ppn_persen]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_ppn', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
			    $validate['pembelian_barang['.$key.'][total]'] = array(
				    'field' => $this->localization->lang('pembelian_barang_total', array('name' => $post['pembelian_barang'][$key]['nama_barang'])),
				    'rules' => 'numeric'
			    );
		    }
	    }
        $this->form_validation->validate($validate);
        $this->transaction->start();
            $item = array();
		    $post['id_shift_aktif'] = 0;
		    $shift_aktif = $this->shift_aktif_m->scope('cabang')->scope('aktif')->first();
		    if ($shift_aktif) {
			    $post['id_shift_aktif'] = $shift_aktif->id;
		    }
            $this->pembelian_m->update($id, $post);
            $result_pembelian_barang = $this->pembelian_barang_m->where('id_pembelian', $id)->get();
            $this->pembelian_barang_m->where('id_pembelian', $id)->delete();
            foreach ($result_pembelian_barang as $pembelian_barang) {
                $item[$pembelian_barang->id_barang]['old'] = $pembelian_barang;
            };
		    if ($post['form_add_barang_id_barang']) {
			    $post['pembelian_barang'][0] = array(
				    'id_barang' => $post['form_add_barang_id_barang'],
				    'kode_barang' => $post['form_add_barang_kode_barang'],
				    'nama_barang' => $post['form_add_barang_nama_barang'],
				    'barang_id_satuan' => $post['form_add_barang_barang_id_satuan'],
				    'barang_satuan' => $post['form_add_barang_barang_satuan'],
				    'id_satuan' => $post['form_add_barang_id_satuan'],
				    'jumlah' => $post['form_add_barang_jumlah'],
				    'harga' => $post['form_add_barang_harga'],
				    'diskon' => $post['form_add_barang_diskon'],
				    'diskon_persen' => $post['form_add_barang_diskon_persen'],
				    'potongan' => $post['form_add_barang_potongan'],
				    'subtotal' => $post['form_add_barang_subtotal'],
				    'ppn' => $post['form_add_barang_ppn'],
				    'ppn_persen' => $post['form_add_barang_ppn_persen'],
				    'total' => $post['form_add_barang_total'],
				    'expired' => $post['form_add_barang_expired']
			    );
		    }
            foreach ($post['pembelian_barang'] as $pembelian_barang) {
                $pembelian_barang['id_pembelian'] = $id;
                $result_pembelian_barang = $this->pembelian_barang_m->insert($pembelian_barang);
                if (!$pembelian_barang['expired']) {
                    $pembelian_barang['expired'] = NULL;
                }
	            if (!$pembelian_barang['batch_number']) {
		            $pembelian_barang['batch_number'] = NULL;
	            }
                $item[$pembelian_barang['id_barang']]['new'] = array(
                    'jenis_mutasi' => 'pembelian',
                    'id_ref' => $result_pembelian_barang->id,
                    'tanggal_mutasi' => $post['tanggal'],
                    'id_barang' => $pembelian_barang['id_barang'],
                    'id_satuan' => $pembelian_barang['id_satuan'],
                    'jumlah' => $pembelian_barang['jumlah'],
                    'total' => $pembelian_barang['total'],
                    'expired' => $pembelian_barang['expired'],
	                'batch_number' => $pembelian_barang['batch_number']
                );

	            $barang = $this->barang_obat_m->view('barang')->find_or_fail($pembelian_barang['id_barang']);
	            $konversi = $this->konversi_satuan_m->convert($pembelian_barang['id_satuan'], $barang->id_satuan, 1);
	            $hpp = $this->localization->number_value($pembelian_barang['harga']) / $konversi;
	            $hna = $hpp + (($barang->ppn_persen / 100) * $hpp);
	            $total = $hna - (($barang->diskon_persen / 100) * $hna);
	            $this->obat_m->update($barang->id_obat, array(
		            'hpp' => $hpp,
		            'hna' => $hna,
		            'diskon_persen' => $pembelian_barang['diskon_persen'],
		            'total' => $total
	            ));
            }

            foreach ($item as $key => $r_barang) {
                if (isset($item[$key]['new']) && isset($item[$key]['old'])) {
                    $this->fifo_m->edit($item[$key]['old']->id, 'masuk', $item[$key]['new']);
                } else if (isset($item[$key]['new'])) {
                    $this->fifo_m->insert('masuk', $item[$key]['new']);
                } else if (isset($item[$key]['old'])) {
                    $this->fifo_m->delete($item[$key]['old']->id, 'masuk');
                }
            }
        if ($this->transaction->complete()) {
	        $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('pembelian'))))->route('transaksi.pembelian');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pembelian'))))->back();
        }
    }

    public function delete($id) {
	    $post = $this->input->post();
        $this->transaction->start();
            $this->pembelian_m->update($id, array(
	            'batal' => 1,
	            'jenis_batal' => $post['jenis_batal'],
	            'deleted_by' => $this->auth->username,
	            'deleted_at' => date('Y-m-d H:i:s')
            ));
	        $result_pembelian_barang = $this->pembelian_barang_m->where('id_pembelian', $id)->get();
            //$this->pembelian_barang_m->where('id_pembelian', $id)->delete();
	        //$this->pembelian_m->delete($id);
            foreach ($result_pembelian_barang as $pembelian_barang) {
                $this->fifo_m->delete($pembelian_barang->id, 'masuk');
            }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('pembelian')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('pembelian')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function nota($id) {
        $model = $this->pembelian_m->view('pembelian')->find_or_fail($id);
        $model->pembelian_barang = $this->pembelian_barang_m->view('pembelian_barang')->where('id_pembelian', $id)->get();
        $this->load->view('transaksi/pembelian/nota', array(
            'model' => $model
        ));
    }
}