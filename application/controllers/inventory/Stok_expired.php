<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_expired extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_stok_m');
        $this->load->model('barang_stok_mutasi_m');
        $this->load->model('cabang_gudang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Stok Expired";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            $gudang = $this->input->get('gudang');
            $range = $this->input->get('range');
            return $this->datatable->resource($this->barang_stok_m, false)
                ->stok_expired($range)
                ->where('barang_stok.id_gudang', $gudang)
                ->where('barang_stok.jumlah > ', 0)
                ->edit_column('jumlah', function ($model) {
                    return $this->localization->number($model->jumlah);
                })
                ->edit_column('expired', function ($model) {
                    return $this->localization->human_date($model->expired);
                })
                ->add_action('{detail}', array(
                    'detail' => function ($model) use ($gudang, $range) {
                        return $this->action->link('view', $this->url_generator->current_url() . '/detail/' . $model->id_barang . '?gudang=' . $gudang . '&range=' . $range, 'class="btn btn-primary btn-sm"', $this->localization->lang('detail'));
                    }
                ))
                ->generate();
        }
        $this->load->view('inventory/stok_expired/index', $data);
    }

    public function detail($id)
    {
        $title = "Stok Expired";
        $gudang = $this->input->get('gudang');
        $range = $this->input->get('range');
        $model = $this->barang_stok_m->view('barang_stok')
            ->where('barang_stok.id_gudang', $gudang)
            ->where('barang_stok.id_barang', $id)
            ->first_or_fail();
        if ($range) {
            $this->barang_stok_mutasi_m->where("barang_stok_mutasi.expired <= ", date('Y-m-d', strtotime('+' . $range . ' day', strtotime(date('Y-m-d')))));
        }
        $model->detail = $this->barang_stok_mutasi_m->view('stok_expired_detail')
            ->where('barang_stok_mutasi.id_gudang', $gudang)
            ->where('barang_stok_mutasi.id_barang', $id)
            ->order_by('barang_stok_mutasi.expired', 'ASC')
            ->get();
        $model->archives = $this->barang_stok_mutasi_m->view('stok_expired_archives')
            ->where('barang_stok_mutasi.id_gudang', $gudang)
            ->where('barang_stok_mutasi.id_barang', $id)
            ->order_by('barang_stok_mutasi.expired', 'ASC')
            ->get();
        $this->load->view('inventory/stok_expired/detail', array(
            'model' => $model, 'title' => $title
        ));
    }
}
