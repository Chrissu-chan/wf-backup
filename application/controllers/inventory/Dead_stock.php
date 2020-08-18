<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dead_stock extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_stok_m');
        $this->load->model('cabang_gudang_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Dead Stok";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            $gudang = $this->input->get('gudang');
            $range = $this->input->get('range');
            if ($range) {
                $this->barang_stok_m->where('barang_stok.tanggal_keluar_terakhir <= ', date('Y-m-d', strtotime('-' . $range . ' day', strtotime(date('Y-m-d')))));
            }
            return $this->datatable->resource($this->barang_stok_m, false)
                ->view('barang_stok')
                ->where('barang_stok.jumlah > ', 0)
                ->where('barang_stok.id_gudang', $gudang)
                ->edit_column('tanggal_keluar_terakhir', function ($model) {
                    return $this->localization->human_date($model->tanggal_keluar_terakhir);
                })
                ->generate();
        }
        $this->load->view('inventory/dead_stock/index', $data);
    }
}
