<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('produk_m');
        $this->load->model('produk_cabang_m');
        $this->load->model('barang_m');
        $this->load->model('obat_m');
        $this->load->model('jasa_m');
        $this->load->model('satuan_m');
        $this->load->model('cabang_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('petugas_m');
        $this->load->model('produk_harga_m');
        $this->load->model('produk_jasa_komisi_m');
        $this->load->model('produk_paket_m');
        $this->load->model('perubahan_harga_m');
        $this->load->model('perubahan_harga_cabang_m');
        $this->load->model('perubahan_harga_kondisi_m');
        $this->load->model('broadcast_harga_produk_m');
        $this->load->model('views/view_hpp_m');
        $this->load->model('views/view_produk_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Produk";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->produk_m)
                ->edit_column('jenis', function ($model) {
                    return $this->produk_m->enum('jenis', $model->jenis);
                })
                ->add_action('{view} {edit} {delete}', array(
                    'edit' => function ($model) {
                        return $this->action->link('edit', $this->route->name('produk.produk.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
                    }
                ))
                ->generate();
        }
        $this->load->view('produk/produk/index', $data);
    }

    public function view($id)
    {
        $result = $this->produk_m->find_or_fail($id);
        switch ($result->jenis) {
            case 'barang':
                $model = $this->produk_m->view('produk_barang')->find_or_fail($id);
                $model->barang = $model->kode_barang . ' - ' . $model->nama_barang;
                $model->harga_satuan_utama = $this->produk_harga_m->view('harga_satuan')
                    ->where('id_produk', $model->id)
                    ->where('id_satuan', $model->id_satuan)
                    ->get();
                $model->satuan_konversi = $this->satuan_m->view('satuan')
                    ->where('id_satuan_tujuan', $model->id_satuan)
                    ->get();
                if ($model->satuan_konversi) {
                    foreach ($model->satuan_konversi as $satuan_konversi) {
                        $model->harga_satuan[$satuan_konversi->id] = $this->produk_harga_m->view('harga_satuan')
                            ->where('id_produk', $model->id)
                            ->where('id_satuan', $satuan_konversi->id)
                            ->get();
                    }
                }
                break;
            case 'jasa':
                $model = $this->produk_m->view('produk_jasa')->find_or_fail($id);;
                $model->harga = $this->produk_harga_m->where('id_produk', $id)->get();
                foreach ($this->cabang_m->scope('auth')->get() as $cabang) {
                    $rs_komisi_petugas = $this->produk_jasa_komisi_m->view('jasa_komisi')
                        ->where('id_produk', $id)
                        ->where('id_cabang', $cabang->id)
                        ->get();
                    foreach ($rs_komisi_petugas as $komisi_petugas) {
                        $model->komisi_petugas[$cabang->id][$komisi_petugas->id_petugas] = $komisi_petugas;
                    }
                }
                break;

            case 'paket':
                $model = $result;
                $model->harga = $this->produk_harga_m->where('id_produk', $id)->get();
                $rs_produk_detail = $this->produk_paket_m->view('produk_detail')->where('id_produk', $id)->get();
                foreach ($rs_produk_detail as $produk_detail) {
                    $model->produk_detail[$produk_detail->id_produk_detail] = $produk_detail;
                }
                break;
        }
        $this->load->view('produk/produk/view', array(
            'model' => $model
        ));
    }

    public function create($jenis)
    {
        $title = "Produk";
        if (!$this->produk_m->enum('jenis', $jenis)) {
            show_404();
        }
        $this->load->view('produk/produk/create', array(
            'jenis' => $jenis, 'title' => $title
        ));
    }

    public function store()
    {
        $post = $this->input->post();
        $validate = array(
            'kode' => 'required|is_unique[produk.kode]',
            'barcode' => 'callback_validate_barcode',
            'produk' => 'required'
        );

        switch ($post['jenis']) {
            case 'barang':
                $validate['id_ref'] = 'required';
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
                break;

            case 'jasa':
                $validate['id_ref'] = 'required';
                $validate['komisi'] = 'required|numeric';
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

                if (isset($post['komisi_petugas'])) {
                    foreach ($post['komisi_petugas'] as $index => $data) {
                        foreach ($data as $key => $komisi_petugas) {
                            $validate['komisi_petugas[' . $index . '][' . $key . '][id_petugas]'] = array(
                                'field' => $this->localization->lang('komisi_petugas_petugas', array('name' => $post['komisi_petugas'][$index][$key]['petugas'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['komisi_petugas[' . $index . '][' . $key . '][komisi]'] = array(
                                'field' => $this->localization->lang('komisi_petugas_komisi', array('name' => $post['komisi_petugas'][$index][$key]['petugas'])),
                                'rules' => 'required|numeric'
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

                $validate['produk_detail[]'] = 'required';
                foreach ($post['produk_detail'] as $key => $val) {
                    $validate['produk_detail[' . $key . '][id_produk_detail]'] = array(
                        'field' => $this->localization->lang('produk_detail_produk'),
                        'rules' => 'required'
                    );
                    if ($post['produk_detail'][$key]['jenis'] == 'barang') {
                        $validate['produk_detail[' . $key . '][id_satuan]'] = array(
                            'field' => $this->localization->lang('produk_detail_satuan', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                            'rules' => 'required'
                        );
                    }
                    $validate['produk_detail[' . $key . '][jumlah]'] = array(
                        'field' => $this->localization->lang('produk_detail_jumlah', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                    $validate['produk_detail[' . $key . '][nilai]'] = array(
                        'field' => $this->localization->lang('produk_detail_nilai', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                }
                break;
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
        if (!isset($post['produk_cabang'])) {
            $post['produk_cabang'][] = 0;
        }
        $result = $this->produk_m->insert($post);
        $rs_produk_cabang = array();
        foreach ($post['produk_cabang'] as $id_cabang) {
            $rs_produk_cabang[] = array(
                'id_produk' => $result->id,
                'id_cabang' => $id_cabang
            );
        }
        $this->produk_cabang_m->insert_batch($rs_produk_cabang);
        $post['ppn_persen'] = 0;
        switch ($post['jenis']) {
            case 'barang':
                $rs_produk_harga_satuan_utama = array();
                foreach ($post['harga_satuan_utama'] as $index => $row) {
                    $row['id_produk'] = $result->id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga_satuan_utama[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga_satuan_utama);
                if (isset($post['harga_satuan'])) {
                    $rs_produk_harga_satuan = array();
                    foreach ($post['harga_satuan'] as $index => $harga_satuan) {
                        foreach ($harga_satuan as $row) {
                            $row['id_produk'] = $result->id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_produk_harga_satuan[] = $row;
                        }
                    }
                    $this->produk_harga_m->insert_batch($rs_produk_harga_satuan);
                }
                break;
            case 'jasa':
                $rs_produk_harga = array();
                foreach ($post['harga'] as $index => $row) {
                    $row['id_produk'] = $result->id;
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga);
                $rs_produk_jasa_komisi = array();
                $rs_produk_jasa_komisi[] = array(
                    'id_cabang' => 0,
                    'id_petugas' => 0,
                    'petugas' => 'Semua Petugas',
                    'komisi' => $post['komisi'],
                    'id_produk' => $result->id
                );
                if (isset($post['komisi_petugas'])) {
                    foreach ($post['komisi_petugas'] as $index => $komisi_petugas) {
                        foreach ($komisi_petugas as $row) {
                            $row['id_produk'] = $result->id;
                            $rs_produk_jasa_komisi[] = $row;
                        }
                    }
                }
                $this->produk_jasa_komisi_m->insert_batch($rs_produk_jasa_komisi);
                break;
            case 'paket':
                $rs_produk_harga = array();
                foreach ($post['harga'] as $index => $row) {
                    $row['id_produk'] = $result->id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga);
                $rs_produk_detail = array();
                foreach ($post['produk_detail'] as $produk_detail) {
                    $produk_detail['id_produk'] = $result->id;
                    $rs_produk_detail[] = $produk_detail;
                }
                $this->produk_paket_m->insert_batch($rs_produk_detail);
                break;
        }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produk'))))->route('produk.produk');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('produk'))))->back();
        }
    }

    public function edit($id)
    {
        $title = "Produk";
        $result = $this->produk_m->find_or_fail($id);
        $model = array();
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

                break;
            case 'jasa':
                $model = $this->produk_m->view('produk_jasa')->find_or_fail($id);;
                $model->harga = $this->produk_harga_m->scope('general')
                    ->where('id_produk', $id)
                    ->get();
                foreach ($this->cabang_m->scope('auth')->get() as $cabang) {
                    $rs_komisi_petugas = $this->produk_jasa_komisi_m->view('jasa_komisi')
                        ->where('id_produk', $id)
                        ->where('id_cabang', $cabang->id)
                        ->get();
                    foreach ($rs_komisi_petugas as $komisi_petugas) {
                        $model->komisi_petugas[$cabang->id][$komisi_petugas->id_petugas] = $komisi_petugas;
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
                break;
        }

        $rs_produk_cabang = $this->produk_cabang_m->where('id_produk', $id)->get();
        foreach ($rs_produk_cabang as $r_produk_cabang) {
            $model->produk_cabang[] = $r_produk_cabang->id_cabang;
        }

        $this->load->view('produk/produk/edit', array(
            'model' => $model, 'title' => $title
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $validate = array(
            'kode' => 'required|is_unique[produk.kode.' . $id . ']',
            'barcode' => 'callback_validate_barcode[' . $id . ']',
            'produk' => 'required',
            'produk_cabang' => 'callback_validate_produk_cabang[' . $id . ']'
        );

        switch ($post['jenis']) {
            case 'barang':
                $validate['id_ref'] = 'required';
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
                break;

            case 'jasa':
                $validate['id_ref'] = 'required';
                $validate['komisi'] = 'required|numeric';
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

                if (isset($post['komisi_petugas'])) {
                    foreach ($post['komisi_petugas'] as $index => $data) {
                        foreach ($data as $key => $komisi_petugas) {
                            $validate['komisi_petugas[' . $index . '][' . $key . '][id_petugas]'] = array(
                                'field' => $this->localization->lang('komisi_petugas_petugas', array('name' => $post['komisi_petugas'][$index][$key]['petugas'])),
                                'rules' => 'required|numeric'
                            );
                            $validate['komisi_petugas[' . $index . '][' . $key . '][komisi]'] = array(
                                'field' => $this->localization->lang('komisi_petugas_komisi', array('name' => $post['komisi_petugas'][$index][$key]['petugas'])),
                                'rules' => 'required|numeric'
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

                $validate['produk_detail[]'] = 'required';
                foreach ($post['produk_detail'] as $key => $val) {
                    $validate['produk_detail[' . $key . '][id_produk_detail]'] = array(
                        'field' => $this->localization->lang('produk_detail_produk'),
                        'rules' => 'required'
                    );
                    if ($post['produk_detail'][$key]['jenis'] == 'barang') {
                        $validate['produk_detail[' . $key . '][id_satuan]'] = array(
                            'field' => $this->localization->lang('produk_detail_satuan', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                            'rules' => 'required'
                        );
                    }
                    $validate['produk_detail[' . $key . '][jumlah]'] = array(
                        'field' => $this->localization->lang('produk_detail_jumlah', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                    $validate['produk_detail[' . $key . '][nilai]'] = array(
                        'field' => $this->localization->lang('produk_detail_nilai', array('name' => $post['produk_detail'][$key]['nama_produk'])),
                        'rules' => 'required|numeric|greater_than[0]'
                    );
                }
                break;
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
        $this->produk_m->update($id, $post);
        $post['ppn_persen'] = 0;
        $this->produk_cabang_m->where('id_produk', $id)->delete();
        if (!isset($post['produk_cabang'])) {
            $post['produk_cabang'][] = 0;
        }
        $rs_produk_cabang = array();
        foreach ($post['produk_cabang'] as $id_cabang) {
            $rs_produk_cabang[] = array(
                'id_produk' => $id,
                'id_cabang' => $id_cabang
            );
        }
        $this->produk_cabang_m->insert_batch($rs_produk_cabang);
        $result_produk_harga = array();
        foreach ($this->produk_harga_m->scope('general')->where('id_produk', $id)->get() as $produk_harga) {
            $result_produk_harga[$produk_harga->id_satuan][$produk_harga->jumlah] = $produk_harga;
        }
        $this->produk_harga_m->scope('general')->where('id_produk', $id)->delete();
        $record_produk_harga = array();
        switch ($post['jenis']) {
            case 'barang':
                $rs_produk_harga_satuan_utama = array();
                foreach ($post['harga_satuan_utama'] as $index => $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $rs_produk_harga_satuan_utama[] = $row;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($rs_produk_harga_satuan_utama);
                if (isset($post['harga_satuan'])) {
                    $rs_produk_harga_satuan = array();
                    foreach ($post['harga_satuan'] as $index => $harga_satuan) {
                        foreach ($harga_satuan as $row) {
                            $row['id_produk'] = $id;
                            $row['ppn_persen'] = $post['ppn_persen'];
                            $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                            $rs_produk_harga_satuan[] = $row;
                            $record_produk_harga[] = $row;
                        }
                    }
                    $this->produk_harga_m->insert_batch($rs_produk_harga_satuan);
                }
                break;
            case 'jasa':
                $this->produk_jasa_komisi_m->where('id_produk', $id)->delete();
                foreach ($post['harga'] as $index => $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($record_produk_harga);
                $rs_produk_jasa_komisi = array();
                $rs_produk_jasa_komisi[] = array(
                    'id_cabang' => 0,
                    'id_petugas' => 0,
                    'petugas' => null,
                    'komisi' => $post['komisi'],
                    'id_produk' => $id
                );
                $this->produk_jasa_komisi_m->where('id_produk', $id)->delete();
                if (isset($post['komisi_petugas'])) {

                    foreach ($post['komisi_petugas'] as $index => $komisi_petugas) {
                        foreach ($komisi_petugas as $row) {
                            $row['id_produk'] = $id;
                            $rs_produk_jasa_komisi[] = $row;
                        }
                    }
                }
                $this->produk_jasa_komisi_m->insert_batch($rs_produk_jasa_komisi);
                break;
            case 'paket':
                foreach ($post['harga'] as $index => $row) {
                    $row['id_produk'] = $id;
                    $row['ppn_persen'] = $post['ppn_persen'];
                    $row['ppn'] = ($this->localization->number_value($row['harga']) * $this->localization->number_value($post['ppn_persen'])) / 100;
                    $record_produk_harga[] = $row;
                }
                $this->produk_harga_m->insert_batch($record_produk_harga);
                $this->produk_paket_m->where('id_produk', $id)->delete();
                $rs_produk_detail = array();
                foreach ($post['produk_detail'] as $produk_detail) {
                    $produk_detail['id_produk'] = $id;
                    $rs_produk_detail[] = $produk_detail;
                }
                $this->produk_paket_m->insert_batch($rs_produk_detail);
                break;
        }
        $this->_broadcast_harga_produk($result_produk_harga, $record_produk_harga);
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('produk'))))->route('produk.produk');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_update_message', array('name' => $this->localization->lang('produk'))))->back();
        }
    }

    private function _broadcast_harga_produk($produk_harga_awal, $produk_harga_akhir)
    {
        if ($produk_harga_akhir) {
            $record_broadcast_harga_produk = array();
            foreach ($produk_harga_akhir as $produk_harga) {
                $produk_harga = (object)$produk_harga;
                if (isset($produk_harga_awal[$produk_harga->id_satuan][$produk_harga->jumlah])) {
                    $harga_awal = $produk_harga_awal[$produk_harga->id_satuan][$produk_harga->jumlah];
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

    public function delete($id)
    {
        $this->transaction->start();
        $old = $this->produk_m->find_or_fail($id);
        $this->produk_m->delete($id);
        $this->produk_harga_m->where('id_produk', $id)->delete();
        switch ($old->jenis) {
            case 'barang':
                break;
            case 'jasa':
                $this->produk_jasa_komisi_m->where('id_produk', $id)->delete();
                break;
            case 'paket':
                $this->produk_paket_m->where('id_produk', $id)->delete();
                break;
        }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('produk')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('produk')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function browse()
    {
        if ($this->input->get('load')) {
            if ($this->input->get('tanggal_mutasi')) {
                $tanggal_mutasi = $this->input->get('tanggal_mutasi');
            }
            if ($jenis = $this->input->get('jenis')) {
                $jenis = explode('|', $jenis);
                $this->db->where_in('jenis_produk', $jenis);
            }

            $keyword = $this->input->get('keyword');
            if ($filters = $this->input->get('filters')){
                $filters = explode('|',$filters);
                $this->db->group_start();
                foreach($filters as $index => $filter) {
                    if ($index == 0) {
                        $this->db->like($filter, $keyword);
                    } else {
                        $this->db->or_like($filter, $keyword);
                    }
                }
                $this->db->group_end();
            }
            $this->load->library('datatable');
            return $this->datatable->resource($this->produk_harga_m)
                ->view('produk_harga_browse')
                ->edit_column('stok', function ($model) {
                    return $this->localization->number($model->stok);
                })
                ->edit_column('harga', function ($model) {
                    return $this->localization->number($model->harga);
                })
                ->generate();
        }

        $tanggal_mutasi = date('Y-m-d');
        if ($this->input->get('tanggal_mutasi')) {
            $tanggal_mutasi = $this->input->get('tanggal_mutasi');
        }
        $this->load->view('produk/produk/browse', array(
            'tanggal_mutasi' => $tanggal_mutasi
        ));
    }

    public function get_json()
    {
        $key = $this->input->get('key');
        if ($key) {
            $this->db->group_start();
            $this->db->like('kode', $key)
                ->or_like('barcode', $key)
                ->or_like('produk', $key);
            $this->db->group_end();
        }
        $result = $this->produk_cabang_m->view('produk')
            ->scope('cabang_aktif')
            ->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_by_barcode_json()
    {
        $post = $this->input->post();
        $key = explode(' - ', $post['key']);
        $result = $this->produk_cabang_m->view('produk')
            ->scope('cabang_aktif')
            ->group_start()
            ->like('barcode', $key[0])
            ->or_like('kode', $key[0])
            ->or_like('produk', end($key))
            ->group_end()
            ->first();
        if ($result) {
            $produk = array(
                'id_produk' => $result->id,
                'kode_produk' => $result->kode,
                'produk' => $result->produk
            );
            $response = array(
                'success' => TRUE,
                'data' => $produk
            );
        } else {
            $response = array(
                'success' => FALSE,
                'data' => NULL
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function satuan_json($id_produk)
    {
        $result = $this->produk_m->view('satuan')
            ->where('id_produk', $id_produk)
            ->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function validate_barcode($str, $attr)
    {
        if ($this->input->post('barcode')) {
            if ($attr) {
                $this->produk_m->where('id != ', $attr);
            }
            $r_produk = $this->produk_m->where('barcode', $str)->first();
            if ($r_produk) {
                $this->form_validation->set_message('validate_barcode', 'The {field} field must contain a unique value.');
                return FALSE;
            }
        }
    }

    public function validate_produk_cabang($str, $attr)
    {
        if ($this->input->post('produk_cabang')) {
            $produk_cabang = $this->input->post('produk_cabang');
            $rs_produk_harga = $this->produk_harga_m->where('id_produk', $attr)
                ->where('id_cabang <>', 0)
                ->get();
            foreach ($rs_produk_harga as $r_produk_harga) {
                if (!in_array($r_produk_harga->id_cabang, $produk_cabang)) {
                    $this->form_validation->set_message('validate_produk_cabang', $this->localization->lang('failed_remove_cabang_related_produk_harga'));
                    return FALSE;
                }
            }
        }
    }
}
