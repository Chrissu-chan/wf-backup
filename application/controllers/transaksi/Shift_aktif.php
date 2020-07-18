<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_aktif extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('shift_aktif_m');
        $this->load->model('shift_aktif_kasir_m');
        $this->load->model('shift_aktif_stok_m');
        $this->load->model('shift_waktu_m');
        $this->load->model('barang_stok_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('kas_bank_cabang_m');
        $this->load->model('shift_m');
        $this->load->library('form_validation');
    }

    public function open() {
        $rs_shift_waktu = $this->shift_waktu_m->where('id_shift', $this->config->item('shift_kasir'))
            ->order_by('urutan')
            ->get();
        $r_shift_aktif = $this->shift_waktu_m->where('id_shift', $this->config->item('shift_kasir'))
            ->where("'".date('H:i:s')."' BETWEEN jam_mulai AND jam_selesai")
            ->first();
        $r_cabang_gudang = $this->cabang_gudang_m->scope('aktif_cabang')
            ->scope('utama')
            ->first();
        $rs_barang_stok = $this->barang_stok_m->view('barang_stok')
            ->where('id_gudang', $r_cabang_gudang->id_gudang)
            ->get();
        $this->load->view('transaksi/shift_aktif/open', array(
            'rs_shift_waktu' => $rs_shift_waktu,
            'r_shift_aktif' => $r_shift_aktif,
            'rs_barang_stok' => $rs_barang_stok
        ));
    }

    public function open_save() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_shift_waktu' => 'required',
            'uang_awal' => 'required|numeric'
        ));
        $this->transaction->start();
            $result = $this->shift_aktif_m->insert(array(
                'id_cabang' => $this->session->userdata('cabang')->id,
                'id_shift' => $this->config->item('shift_kasir'),
                'id_shift_waktu' => $post['id_shift_waktu'],
                'active' => 1
            ));
	        $kas_bank_cabang = $this->kas_bank_cabang_m->scope('utama')->first_or_fail();
            $this->shift_aktif_kasir_m->insert(array(
                'id_shift_aktif' => $result->id,
	            'id_kas_bank' => $kas_bank_cabang->id_kas_bank,
                'id_user' => $this->auth->id,
                'uang_awal' => $post['uang_awal']
            ));
            $r_cabang_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first();
		    $rs_barang_stok = $this->barang_stok_m->view('barang_stok')
			    ->where('id_gudang', $r_cabang_gudang->id_gudang)
			    ->get();
            if ($rs_barang_stok) {
                $record_barang_stok = array();
                foreach ($rs_barang_stok as $r_barang_stok) {
                    $record_barang_stok[] = array(
                        'id_shift_aktif' => $result->id,
                        'id_gudang' => $r_cabang_gudang->id_gudang,
                        'id_barang' => $r_barang_stok->id_barang,
                        'stok_awal' => $r_barang_stok->jumlah
                    );
                }
                if ($record_barang_stok) {
                    $this->shift_aktif_stok_m->insert_batch($record_barang_stok);
                }
            }
        $result = $this->transaction->complete();
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_open_shift'))->route('dashboard');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_open_shift'))->back();
        }
    }

    public function close() {
        $model = $this->shift_aktif_m->view('shift_aktif')
	        ->scope('cabang')
	        ->scope('aktif')
	        ->first();
	    $r_cabang_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first();
        $model->barang_stok = $this->barang_stok_m->barang_stok_shift($model->id)
	        ->where('barang_stok.id_gudang', $r_cabang_gudang->id_gudang)
	        ->get();
        $this->load->view('transaksi/shift_aktif/close', array(
            'model' => $model
        ));
    }

    public function close_save($id) {
        $post = $this->input->post();
        $this->transaction->start();
            $this->shift_aktif_m->update($id, array(
                'active' => 0
            ));
            $r_shift_aktif_kasir = $this->shift_aktif_kasir_m->where('id_shift_aktif', $id)->first();
            $this->shift_aktif_kasir_m->update($r_shift_aktif_kasir->id, array(
                'uang_akhir' => $post['uang_akhir']
            ));

            $result_barang_stok = array();
            $rs_shift_aktif_stok = $this->shift_aktif_stok_m->where('id_shift_aktif', $id)->get();
            if ($rs_shift_aktif_stok) {
                $this->shift_aktif_stok_m->where('id_shift_aktif', $id)->delete();
                foreach ($rs_shift_aktif_stok as $r_shift_aktif_stok) {
                    $result_barang_stok[$id][$r_shift_aktif_stok->id_barang] = $r_shift_aktif_stok;
                }
            }

            $record_barang_stok = array();
            $r_cabang_gudang = $this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->first();
		    $rs_barang_stok = $this->barang_stok_m->view('barang_stok')
			    ->where('id_gudang', $r_cabang_gudang->id_gudang)
			    ->get();
            foreach ($rs_barang_stok as $r_barang_stok) {
                if (isset($result_barang_stok[$id][$r_barang_stok->id_barang])) {
                    $record_barang_stok[] = array(
                        'id_shift_aktif' => $id,
                        'id_gudang' => $r_barang_stok->id_gudang,
                        'id_barang' => $r_barang_stok->id_barang,
                        'stok_awal' => $result_barang_stok[$id][$r_barang_stok->id_barang]->stok_awal,
                        'stok_akhir' => $r_barang_stok->jumlah
                    );
                } else {
                    $record_barang_stok[] = array(
                        'id_shift_aktif' => $id,
                        'id_gudang' => $r_cabang_gudang->id_gudang,
                        'id_barang' => $r_barang_stok->id_barang,
                        'stok_awal' => 0,
                        'stok_akhir' => $r_barang_stok->jumlah
                    );
                }
            }

            if ($record_barang_stok) {
                $this->shift_aktif_stok_m->insert_batch($record_barang_stok);
            }
        $result = $this->transaction->complete();
        if ($result) {
            $this->redirect->with('success_message', $this->localization->lang('success_close_shift'))->route('dashboard');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_close_shift'))->back();
        }
    }

    public function check() {
        $post = $this->input->post();
        $result = $this->shift_aktif_m->where('password', md5($post['password']))
	        ->view('shift_aktif')
	        ->scope('cabang')
	        ->scope('aktif')
	        ->first();
        if ($result) {
            $response = array(
                'success' => true,
                'message' => null
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('password_is_incorrect')
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}