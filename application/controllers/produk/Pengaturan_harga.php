<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Pengaturan_harga extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_m');
        $this->load->model('obat_m');
        $this->load->model('produk_m');
        $this->load->model('produk_cabang_m');
        $this->load->model('barang_m');
        $this->load->model('jasa_m');
        $this->load->model('konversi_satuan_m');
        $this->load->model('satuan_m');
        $this->load->model('cabang_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('petugas_m');
        $this->load->model('produk_harga_m');
        $this->load->model('produk_jasa_komisi_m');
        $this->load->model('produk_paket_m');
        $this->load->model('broadcast_harga_produk_m');
        $this->load->model('views/view_hpp_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Pengaturan Harga";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->produk_m)
                ->edit_column('jenis', function ($model) {
                    return $this->produk_m->enum('jenis', $model->jenis);
                })
                ->add_action('{edit}', array(
                    'edit' => function ($model) {
                        return $this->action->link('edit', $this->route->name('produk.pengaturan_harga.edit', array('id' => $model->id)), 'class="btn btn-primary btn-sm"', $this->localization->lang('pengaturan_harga'));
                    }
                ))
                ->generate();
        }
        $this->load->view('produk/pengaturan_harga/index', $data);
    }

    public function edit($id)
    {
        $title = "Pengaturan Harga";
        $result = $this->produk_m->find_or_fail($id);
        $rs_produk_cabang = $this->produk_cabang_m->view('produk_cabang')
            ->scope('auth')
            ->where('id_produk', $id)
            ->get();
        switch ($result->jenis) {
            case 'barang':
                $model = $this->produk_m->view('produk_barang')->find_or_fail($id);
                $obat = $this->obat_m->where('id_barang', $model->id_ref)->first_or_fail();
                $model->barang = $model->kode_barang . ' - ' . $model->nama_barang;
                $r_hpp = $this->view_hpp_m->view('hpp')
                    ->where('id_barang', $model->id_ref)
                    ->first();
                if ($r_hpp) {
                    $model->harga_min = $r_hpp->harga_min;
                    $model->harga_max = $r_hpp->harga_max;
                    $model->hpp = $r_hpp->hpp;
                    $model->harga_beli_terakhir = $obat->total;
                }
                $rs_harga_satuan_utama = $this->produk_harga_m->view('harga_satuan')
                    ->scope('general')
                    ->where('id_produk', $model->id)
                    ->where('id_satuan', $model->id_satuan)
                    ->order_by('urutan', 'ASC')
                    ->get();
                if ($rs_harga_satuan_utama) {
                    foreach ($rs_harga_satuan_utama as $r_harga_satuan_utama) {
                        $model->harga_satuan_utama[$r_harga_satuan_utama->id] = $r_harga_satuan_utama;
                    }
                }
                $model->satuan_konversi = $this->satuan_m->view('satuan')
                    ->where('id_satuan_tujuan', $model->id_satuan)
                    ->get();
                if ($model->satuan_konversi) {
                    foreach ($model->satuan_konversi as $r_satuan_konversi) {
                        $rs_harga_satuan = $this->produk_harga_m->view('harga_satuan')
                            ->scope('general')
                            ->where('id_produk', $model->id)
                            ->where('id_satuan', $r_satuan_konversi->id)
                            ->order_by('urutan', 'ASC')
                            ->get();
                        if ($rs_harga_satuan) {
                            foreach ($rs_harga_satuan as $r_harga_satuan) {
                                $r_harga_satuan->konversi = $r_satuan_konversi->konversi;
                                $model->harga_satuan[$r_satuan_konversi->id][$r_harga_satuan->id] = $r_harga_satuan;
                            }
                        }
                    }
                }
                foreach ($rs_produk_cabang as $r_produk_cabang) {
                    $model->cabang_hpp[$r_produk_cabang->id_cabang] = $this->view_hpp_m->view('hpp')
                        ->where('id_gudang', $r_produk_cabang->id_gudang)
                        ->where('id_barang', $model->id_ref)
                        ->first();
                    $model->cabang_hpp[$r_produk_cabang->id_cabang]->harga_beli_terakhir = $obat->total;
                    $rs_cabang_harga_satuan_utama = $this->produk_harga_m->view('harga_satuan')
                        ->where('id_cabang', $r_produk_cabang->id_cabang)
                        ->where('id_produk', $model->id)
                        ->where('id_satuan', $model->id_satuan)
                        ->order_by('urutan', 'ASC')
                        ->get();
                    if ($rs_cabang_harga_satuan_utama) {
                        foreach ($rs_cabang_harga_satuan_utama as $r_cabang_harga_satuan_utama) {
                            $model->cabang_harga_satuan_utama[$r_produk_cabang->id_cabang][$r_cabang_harga_satuan_utama->id] = $r_cabang_harga_satuan_utama;
                        }
                    }
                    if (isset($model->cabang_harga_satuan_utama[$r_produk_cabang->id_cabang])) {
                        $model->cabang_harga[$r_produk_cabang->id_cabang] = $r_produk_cabang->id_cabang;
                    }
                    if ($model->satuan_konversi) {
                        foreach ($model->satuan_konversi as $r_satuan_konversi) {
                            $rs_cabang_harga_satuan = $this->produk_harga_m->view('harga_satuan')
                                ->where('id_cabang', $r_produk_cabang->id_cabang)
                                ->where('id_produk', $model->id)
                                ->where('id_satuan', $r_satuan_konversi->id)
                                ->order_by('urutan', 'ASC')
                                ->get();
                            if ($rs_cabang_harga_satuan) {
                                foreach ($rs_cabang_harga_satuan as $r_cabang_harga_satuan) {
                                    $r_cabang_harga_satuan->konversi = $r_satuan_konversi->konversi;
                                    $model->cabang_harga_satuan[$r_produk_cabang->id_cabang][$r_satuan_konversi->id][$r_cabang_harga_satuan->id] = $r_cabang_harga_satuan;
                                }
                            }
                        }
                    }
                }
                break;
            case 'jasa':
                $model = $this->produk_m->view('produk_jasa')->find_or_fail($id);;
                $model->harga = $this->produk_harga_m->scope('general')
                    ->where('id_produk', $id)
                    ->get();
                foreach ($rs_produk_cabang as $r_produk_cabang) {
                    $model->cabang_harga_cabang[$r_produk_cabang->id_cabang] = $this->produk_harga_m->where('id_cabang', $r_produk_cabang->id_cabang)
                        ->where('id_produk', $model->id)
                        ->get();

                    if ($model->cabang_harga_cabang[$r_produk_cabang->id_cabang]) {
                        $model->cabang_harga[$r_produk_cabang->id_cabang] = $r_produk_cabang->id_cabang;
                    }
                }
                break;
            case 'paket':
                $model = $result;
                $model->harga = $this->produk_harga_m->scope('general')
                    ->where('id_produk', $id)
                    ->get();
                $rs_produk_detail = $this->produk_paket_m->view('produk_detail')->where('id_produk', $id)->get();
                foreach ($rs_produk_detail as $produk_detail) {
                    $model->produk_detail[$produk_detail->id_produk_detail] = $produk_detail;
                }
                foreach ($rs_produk_cabang as $r_produk_cabang) {
                    $model->cabang_harga_cabang[$r_produk_cabang->id_cabang] = $this->produk_harga_m->where('id_cabang', $r_produk_cabang->id_cabang)
                        ->where('id_produk', $model->id)
                        ->get();
                    if ($model->cabang_harga_cabang[$r_produk_cabang->id_cabang]) {
                        $model->cabang_harga[$r_produk_cabang->id_cabang] = $r_produk_cabang->id_cabang;
                    }
                }
                break;
        }
        foreach ($rs_produk_cabang as $r_produk_cabang) {
            $model->produk_cabang[$r_produk_cabang->id_cabang] = $r_produk_cabang;
        }
        $this->load->view('produk/pengaturan_harga/edit', array(
            'model' => $model, 'title' => $title
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $validate = array();
        switch ($post['jenis']) {
            case 'barang':
                $validate['harga_satuan_utama[]'] = 'required';
                foreach ($post['harga_satuan_utama'] as $index => $data) {
                    $validate['harga_satuan_utama[' . $index . '][urutan]'] = array(
                        'field' => $this->localization->lang('harga_satuan_utama_urutan', array('name' => $post['harga_satuan_utama'][$index]['satuan'])),
                        'rules' => 'required|numeric'
                    );
                    $validate['harga_satuan_utama[' . $index . '][jumlah]'] = array(
                        'field' => $this->localization->lang('harga_satuan_utama_jumlah', array('name' => $post['harga_satuan_utama'][$index]['satuan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                    $validate['harga_satuan_utama[' . $index . '][harga]'] = array(
                        'field' => $this->localization->lang('harga_satuan_utama_harga', array('name' => $post['harga_satuan_utama'][$index]['satuan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                }
                if (isset($post['harga_satuan'])) {
                    foreach ($post['harga_satuan'] as $index => $data) {
                        foreach ($data as $key => $harga_satuan) {
                            $validate['harga_satuan[' . $index . '][' . $key . '][urutan]'] = array(
                                'field' => $this->localization->lang('harga_satuan_urutan', array('name' => $post['harga_satuan'][$index][$key]['satuan'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['harga_satuan[' . $index . '][' . $key . '][jumlah]'] = array(
                                'field' => $this->localization->lang('harga_satuan_jumlah', array('name' => $post['harga_satuan'][$index][$key]['satuan'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                            $validate['harga_satuan[' . $index . '][' . $key . '][harga]'] = array(
                                'field' => $this->localization->lang('harga_satuan_harga', array('name' => $post['harga_satuan'][$index][$key]['satuan'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                        }
                    }
                }
                if ($post['cabang_harga']) {
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        $validate['cabang_harga_satuan_utama[' . $id_cabang . '][]'] = 'required';
                        foreach ($post['cabang_harga_satuan_utama'][$id_cabang] as $index => $data) {
                            $validate['cabang_harga_satuan_utama[' . $id_cabang . '][' . $index . '][urutan]'] = array(
                                'field' => $this->localization->lang('cabang_harga_satuan_utama_urutan', array('name' => $post['cabang_harga_satuan_utama'][$id_cabang][$index]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['cabang_harga_satuan_utama[' . $id_cabang . '][' . $index . '][jumlah]'] = array(
                                'field' => $this->localization->lang('cabang_harga_satuan_utama_jumlah', array('name' => $post['cabang_harga_satuan_utama'][$id_cabang][$index]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                            $validate['cabang_harga_satuan_utama[' . $id_cabang . '][' . $index . '][harga]'] = array(
                                'field' => $this->localization->lang('cabang_harga_satuan_utama_harga', array('name' => $post['cabang_harga_satuan_utama'][$id_cabang][$index]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                        }
                        if (isset($post['cabang_harga_satuan'][$id_cabang])) {
                            foreach ($post['cabang_harga_satuan'][$id_cabang] as $index => $data) {
                                foreach ($data as $key => $cabang_harga_satuan) {
                                    $validate['cabang_harga_satuan[' . $id_cabang . '][' . $index . '][' . $key . '][urutan]'] = array(
                                        'field' => $this->localization->lang('cabang_harga_satuan_urutan', array('name' => $post['cabang_harga_satuan'][$id_cabang][$index][$key]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                        'rules' => 'required|numeric'
                                    );
                                    $validate['cabang_harga_satuan[' . $id_cabang . '][' . $index . '][' . $key . '][jumlah]'] = array(
                                        'field' => $this->localization->lang('cabang_harga_satuan_jumlah', array('name' => $post['cabang_harga_satuan'][$id_cabang][$index][$key]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                        'rules' => 'required|numeric|greater_than[0]'
                                    );
                                    $validate['cabang_harga_satuan[' . $id_cabang . '][' . $index . '][' . $key . '][harga]'] = array(
                                        'field' => $this->localization->lang('cabang_harga_satuan_harga', array('name' => $post['cabang_harga_satuan'][$id_cabang][$index][$key]['satuan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                        'rules' => 'required|numeric|greater_than[0]'
                                    );
                                }
                            }
                        }
                    }
                }
                break;
            case 'jasa':
                $validate['harga[]'] = 'required';
                foreach ($post['harga'] as $index => $data) {
                    $validate['harga[' . $index . '][urutan]'] = array(
                        'field' => $this->localization->lang('harga_jasa_urutan', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric'
                    );
                    $validate['harga[' . $index . '][jumlah]'] = array(
                        'field' => $this->localization->lang('harga_jasa_jumlah', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                    $validate['harga[' . $index . '][harga]'] = array(
                        'field' => $this->localization->lang('harga_jasa_harga', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                }
                if ($post['cabang_harga']) {
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        $validate['cabang_harga_cabang[' . $id_cabang . '][]'] = 'required';
                        foreach ($post['cabang_harga_cabang'][$id_cabang] as $index => $data) {
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][urutan]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_urutan', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][jumlah]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_jumlah', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][harga]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_harga', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                        }
                    }
                }
                break;
            case 'paket':
                $validate['harga[]'] = 'required';
                foreach ($post['harga'] as $index => $data) {
                    $validate['harga[' . $index . '][urutan]'] = array(
                        'field' => $this->localization->lang('harga_paket_urutan', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric'
                    );
                    $validate['harga[' . $index . '][jumlah]'] = array(
                        'field' => $this->localization->lang('harga_paket_jumlah', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                    $validate['harga[' . $index . '][harga]'] = array(
                        'field' => $this->localization->lang('harga_paket_harga', array('name' => $post['harga'][$index]['urutan'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                }
                if ($post['cabang_harga']) {
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        $validate['cabang_harga_cabang[' . $id_cabang . '][]'] = 'required';
                        foreach ($post['cabang_harga_cabang'][$id_cabang] as $index => $data) {
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][urutan]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_urutan', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][jumlah]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_jumlah', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                            $validate['cabang_harga_cabang[' . $id_cabang . '][' . $index . '][harga]'] = array(
                                'field' => $this->localization->lang('cabang_harga_cabang_harga', array('name' => $post['cabang_harga_cabang'][$id_cabang][$index]['urutan'], 'title' => $post['produk_cabang'][$id_cabang]['nama'])),
                                'rules' => 'required|numeric|greater_than[0]'
                            );
                        }
                    }
                }
                break;
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
        $post['ppn_persen'] = 0;
        $result_produk_harga = array();
        $this->produk_m->update($id, $post);
        foreach ($this->produk_harga_m->where('id_produk', $id)->get() as $produk_harga) {
            $result_produk_harga[$produk_harga->id_cabang][$produk_harga->id_satuan][$produk_harga->jumlah] = $produk_harga;
        }
        $this->produk_harga_m->where('id_produk', $id)->delete();
        $record_produk_harga = array();
        switch ($post['jenis']) {
            case 'barang':
                $rs_produk_harga_satuan_utama = array();
                foreach ($post['harga_satuan_utama'] as $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga_satuan_utama[] = $row;
                    $row['id_cabang'] = 0;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga_satuan_utama);
                if (isset($post['harga_satuan'])) {
                    $rs_produk_harga_satuan = array();
                    foreach ($post['harga_satuan'] as $harga_satuan) {
                        foreach ($harga_satuan as $row) {
                            $row['id_produk'] = $id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_produk_harga_satuan[] = $row;
                            $row['id_cabang'] = 0;
                            $record_produk_harga[] = $row;
                        }
                    }
                    $this->produk_harga_m->insert_batch($rs_produk_harga_satuan);
                }
                if ($post['cabang_harga']) {
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        $rs_cabang_produk_harga_satuan_utama = array();
                        foreach ($post['cabang_harga_satuan_utama'][$id_cabang] as $row) {
                            $row['id_cabang'] = $id_cabang;
                            $row['id_produk'] = $id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_cabang_produk_harga_satuan_utama[] = $row;
                            $record_produk_harga[] = $row;
                        }
                        $this->produk_harga_m->insert_batch($rs_cabang_produk_harga_satuan_utama);
                        if (isset($post['cabang_harga_satuan'][$id_cabang])) {
                            $rs_cabang_produk_harga_satuan = array();
                            foreach ($post['cabang_harga_satuan'][$id_cabang] as $cabang_harga_satuan) {
                                foreach ($cabang_harga_satuan as $row) {
                                    $row['id_cabang'] = $id_cabang;
                                    $row['id_produk'] = $id;
                                    $row['ppn_persen'] = $post['ppn_persen'];
                                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                                    $rs_cabang_produk_harga_satuan[] = $row;
                                    $record_produk_harga[] = $row;
                                }
                            }
                            $this->produk_harga_m->insert_batch($rs_cabang_produk_harga_satuan);
                        }
                    }
                }
                break;
            case 'jasa':
                $rs_produk_harga = array();
                foreach ($post['harga'] as $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga[] = $row;
                    $row['id_cabang'] = 0;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga);
                if ($post['cabang_harga']) {
                    $rs_cabang_harga_cabang = array();
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        foreach ($post['cabang_harga_cabang'][$id_cabang] as $row) {
                            $row['id_cabang'] = $id_cabang;
                            $row['id_produk'] = $id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_cabang_harga_cabang[] = $row;
                            $record_produk_harga[] = $row;
                        }
                    }
                    $this->produk_harga_m->insert_batch($rs_cabang_harga_cabang);
                }
                break;
            case 'paket':
                $rs_produk_harga = array();
                foreach ($post['harga'] as $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga[] = $row;
                    $row['id_cabang'] = 0;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga);
                if ($post['cabang_harga']) {
                    $rs_cabang_harga_cabang = array();
                    foreach ($post['cabang_harga'] as $id_cabang) {
                        foreach ($post['cabang_harga_cabang'][$id_cabang] as $row) {
                            $row['id_cabang'] = $id_cabang;
                            $row['id_produk'] = $id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_cabang_harga_cabang[] = $row;
                            $record_produk_harga[] = $row;
                        }
                    }
                    $this->produk_harga_m->insert_batch($rs_cabang_harga_cabang);
                }
                break;
        }
        $this->_broadcast_harga_produk($result_produk_harga, $record_produk_harga);
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('pengaturan_harga'))))->route('produk.pengaturan_harga');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('pengaturan_harga'))))->back();
        }
    }

    private function _broadcast_harga_produk($produk_harga_awal, $produk_harga_akhir)
    {
        if ($produk_harga_akhir) {
            $record_broadcast_harga_produk = array();
            foreach ($produk_harga_akhir as $produk_harga) {
                $produk_harga = (object)$produk_harga;
                if (isset($produk_harga_awal[$produk_harga->id_cabang][$produk_harga->id_satuan][$produk_harga->jumlah])) {
                    $harga_awal = $produk_harga_awal[$produk_harga->id_cabang][$produk_harga->id_satuan][$produk_harga->jumlah];
                    $record_broadcast_harga_produk[] = array(
                        'id_cabang' => $harga_awal->id_cabang,
                        'tanggal' => date('Y-m-d'),
                        'id_produk' => $produk_harga->id_produk,
                        'id_satuan' => $produk_harga->id_satuan,
                        'jumlah' => $produk_harga->jumlah,
                        'harga_awal' => $harga_awal->harga,
                        'harga_akhir' => $produk_harga->harga
                    );
                }
            }
            if ($record_broadcast_harga_produk) {
                $this->broadcast_harga_produk_m->insert_batch($record_broadcast_harga_produk);
            }
        }
    }

    public function harga_satuan_json()
    {
        $id_produk = $this->input->get('id_produk');
        $result = $this->produk_harga_m->harga_satuan($id_produk);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_harga_satuan_json()
    {
        $id_produk = $this->input->get('id_produk');
        $id_satuan = $this->input->get('id_satuan');
        $jumlah = $this->input->get('jumlah');
        if ($jumlah == 0) {
            $jumlah = 1;
        }
        $result = $this->produk_harga_m->view('harga_satuan')
            ->scope('cabang')
            ->where('id_produk', $id_produk)
            ->where('id_satuan', $id_satuan)
            ->where('jumlah <= ', $jumlah)
            ->order_by('urutan', 'DESC')
            ->first();
        if (!$result) {
            $result = $this->produk_harga_m->view('harga_satuan')
                ->scope('general')
                ->where('id_produk', $id_produk)
                ->where('id_satuan', $id_satuan)
                ->where('jumlah <= ', $jumlah)
                ->order_by('urutan', 'DESC')
                ->first();
        }

        if ($result) {
            $response = array(
                'success' => TRUE,
                'data' => $result
            );
        } else {
            $response = array(
                'success' => FALSE,
                'data' => NULL
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import()
    {
        $this->load->view('produk/pengaturan_harga/import');
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

        $satuan = array('F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        $produk_harga = array('F' => array('P', 'Q'), 'H' => array('R', 'S'), 'J' => array('T', 'U'), 'L' => array('V', 'W'), 'N' => array('X', 'Y'));

        $format = array(
            'A' => 'No',
            'B' => 'Kode',
            'C' => 'Barcode',
            'D' => 'Nama',
            'E' => 'Cabang',
            'F' => 'Satuan',
            'P' => 'Harga Jual'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 7; $i <= count($worksheet); $i++) {
            $this->transaction->start();

            $no = $worksheet[$i]['A'];
            $kode = trim($worksheet[$i]['B']);
            $barcode = trim($worksheet[$i]['C']);
            $nama = trim($worksheet[$i]['D']);
            $cabang = trim($worksheet[$i]['E']);

            $data = array(
                'kode' => $kode,
                'barcode' => $barcode,
                'nama' => $nama,
                'cabang' => $cabang
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kode' => 'required',
                'nama' => 'required',
                'cabang' => 'required'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $produk = $this->produk_m->where('LOWER(kode)', strtolower($kode))->first();
            if ($produk) {
                $id_cabang = NULL;
                if (strtolower($cabang) == 'all') {
                    $id_cabang = 0;
                } else {
                    $cabang = $this->cabang_m->where('LOWER(nama)', strtolower($cabang))->first();
                    if ($cabang) {
                        $id_cabang = $cabang->id;
                    } else {
                        $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', 'cabang_tidak_ditemukan')));
                        continue;
                    }
                }

                for ($j = 0; $j < 10; $j++) {
                    $id_satuan = trim($worksheet[$i][$satuan[$j]]);
                    $margin_persen = trim($worksheet[$i][$produk_harga[$satuan[$j]][0]]);
                    $harga = trim($worksheet[$i][$produk_harga[$satuan[$j]][1]]);
                    if (!$id_satuan) {
                        $id_satuan = 0;
                    }
                    if ($harga > 0) {
                        if ($produk->jenis == 'barang') {
                            $this->produk_harga_m->where('id_satuan', $id_satuan);
                        }
                        $rs_produk_harga = $this->produk_harga_m->where('id_cabang', $id_cabang)
                            ->where('id_produk', $produk->id)
                            ->where('jumlah', 1)
                            ->first();
                        if ($rs_produk_harga) {
                            $this->produk_harga_m->update($rs_produk_harga->id, array(
                                'margin_persen' => $margin_persen,
                                'harga' => $harga
                            ));
                        } else {
                            $this->produk_harga_m->insert(array(
                                'id_cabang' => $id_cabang,
                                'id_produk' => $produk->id,
                                'id_satuan' => $id_satuan,
                                'jumlah' => 1,
                                'margin_persen' => $margin_persen,
                                'harga' => $harga,
                                'urutan' => 1
                            ));
                        }
                    }
                    $j++;
                }
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', 'produk_tidak_ditemukan')));
                continue;
            }

            if ($this->transaction->complete()) {
                $success_count++;
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('barang')))));
            }
        }
        $this->redirect->with('import_error_message', $errors)
            ->with('import_success_message', $success_count)
            ->back();
    }

    public function download_format()
    {
        $this->load->helper('download');
        $path = base_url('public/produk/pengaturan_harga/import_pengaturan_harga.xlsx');
        $data = file_get_contents($path);
        $name = 'import_pengaturan_harga.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $cabang = $this->cabang_gudang_m->view('cabang_gudang')->scope('aktif_cabang')->first_or_fail();
        $spreadsheet = IOFactory::load('public/produk/pengaturan_harga/import_pengaturan_harga.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $konversi_satuan = array('H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        $produk_harga = array('H' => array('R', 'S'), 'J' => array('T', 'U'), 'L' => array('V', 'W'), 'N' => array('X', 'Y'));

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

        $rs_produk = $this->produk_harga_m->view('produk_harga')->get();
        $row = 7;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Harga');
        $worksheet->getCell('A2')->setValue($cabang->nama);
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_produk as $key => $produk) {
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($produk->kode);
            $worksheet->getCell('C' . $row)->setValue($produk->barcode);
            $worksheet->getCell('D' . $row)->setValue($produk->produk);
            if ($produk->cabang) {
                $worksheet->getCell('E' . $row)->setValue($produk->cabang);
            } else {
                $worksheet->getCell('E' . $row)->setValue('All');
            }
            $record_produk_harga = array();
            $rs_produk_harga = $this->produk_harga_m->where('id_cabang', $produk->id_cabang)
                ->where('id_produk', $produk->id_produk)
                ->where('jumlah', 1)
                ->where('urutan', 1)
                ->get();
            if ($rs_produk_harga) {
                foreach ($rs_produk_harga as $r_produk_harga) {
                    $record_produk_harga[$r_produk_harga->id_satuan][0] = $r_produk_harga->margin_persen;
                    $record_produk_harga[$r_produk_harga->id_satuan][1] = $r_produk_harga->harga;
                }
            }
            if ($produk->barang_id_satuan) {
                $worksheet->getCell('F' . $row)->setValue($produk->barang_id_satuan);
                $worksheet->getCell('G' . $row)->setValue($produk->barang_satuan);
                if (isset($record_produk_harga[$produk->barang_id_satuan])) {
                    $margin_persen = $record_produk_harga[$produk->barang_id_satuan][0];
                    $harga = $record_produk_harga[$produk->barang_id_satuan][1];
                    $worksheet->getCell('P' . $row)->setValue($margin_persen);
                    $worksheet->getCell('Q' . $row)->setValue($harga);
                }
                $rs_konversi_satuan = $this->konversi_satuan_m->view('konversi_satuan')
                    ->where('id_satuan_tujuan', $produk->barang_id_satuan)
                    ->get();
                $j = 0;
                if ($rs_konversi_satuan) {
                    foreach ($rs_konversi_satuan as $r_konversi_satuan) {
                        $worksheet->getCell($konversi_satuan[$j] . $row)->setValue($r_konversi_satuan->id_satuan_asal);
                        $worksheet->getCell($konversi_satuan[$j + 1] . $row)->setValue($r_konversi_satuan->satuan_asal);
                        if (isset($record_produk_harga[$r_konversi_satuan->id_satuan_asal])) {
                            $margin_persen = $record_produk_harga[$r_konversi_satuan->id_satuan_asal][0];
                            $harga = $record_produk_harga[$r_konversi_satuan->id_satuan_asal][1];
                            $worksheet->getCell($produk_harga[$konversi_satuan[$j]][0] . $row)->setValue($margin_persen);
                            $worksheet->getCell($produk_harga[$konversi_satuan[$j]][1] . $row)->setValue($harga);
                        }
                        $j += 2;
                    }
                }
            } else {
                $margin_persen = $record_produk_harga[0][0];
                $harga = $record_produk_harga[0][1];
                $worksheet->getCell('P' . $row)->setValue($margin_persen);
                $worksheet->getCell('Q' . $row)->setValue($harga);
            }
            for ($i = 0; $i < 25; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }

        foreach ($worksheet->getColumnDimensions() as $colDim) {
            $colDim->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="pengaturan_harga.xlsx"');
        $writer->save("php://output");
    }
}
