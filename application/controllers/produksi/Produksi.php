<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('produksi_m');
        $this->load->model('produksi_bahan_baku_m');
        $this->load->model('barang_produksi_bahan_baku_m');
        $this->load->model('satuan_m');
        $this->load->model('cabang_gudang_m');
        $this->load->model('fifo_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Produksi";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->produksi_m)
                ->view('produksi')
                ->edit_column('tanggal_produksi', function ($model) {
                    return $this->localization->human_date($model->tanggal_produksi);
                })
                ->edit_column('hpp', function ($model) {
                    return $this->localization->number($model->hpp);
                })
                ->add_action('{view} {edit} {delete}', array(
                    'edit' => function ($model) {
                        return $this->action->link('edit', $this->route->name('produksi.produksi.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
                    },
                    'delete' => function ($model) {
                        return $this->action->button('delete', 'class="btn btn-danger btn-sm" onclick="swalConfirm(\'Apakah anda yakin akan menghapus data ini?\', \'' . $this->route->name('produksi.produksi.delete', array('id' => $model->id)) . '\')"');
                    }
                ))
                ->generate();
        }
        $this->load->view('produksi/produksi/index', $data);
    }

    public function view($id)
    {
        $model = $this->produksi_m->view('produksi')->find_or_fail($id);
        $model->produksi_bahan_baku = $this->produksi_bahan_baku_m->view('bahan_baku')->where('id_produksi', $id)->get();
        $this->load->view('produksi/produksi/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $data["title"] = "Produksi";
        $this->load->view('produksi/produksi/create', $data);
    }

    public function store()
    {
        $post = $this->input->post();
        $validate = array(
            'no_produksi' => 'required',
            'tanggal_produksi' => 'required',
            'id_barang_produksi' => 'required',
            'id_barang' => 'required',
            'id_satuan' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required|numeric|greater_than[0]',
            'total_bahan_baku' => 'required|numeric|greater_than[0]',
            'total_biaya_lainnya' => 'numeric',
            'total_biaya_produksi' => 'required|numeric|greater_than[0]',
            'hpp' => 'required|numeric|greater_than[0]',
            'produksi_bahan_baku[]' => 'required'
        );

        foreach ($post['produksi_bahan_baku'] as $key => $val) {
            $validate['produksi_bahan_baku[' . $key . '][id_satuan]'] = array(
                'field' => $this->localization->lang('produksi_bahan_baku_satuan', array('name' => $post['produksi_bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required'
            );
            $validate['produksi_bahan_baku[' . $key . '][jumlah]'] = array(
                'field' => $this->localization->lang('produksi_bahan_baku_jumlah', array('name' => $post['produksi_bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required|numeric'
            );
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
        $result = $this->produksi_m->insert($post);
        $this->fifo_m->insert('masuk', array(
            'jenis_mutasi' => 'produksi',
            'id_ref' => $result->id,
            'tanggal_mutasi' => $result->tanggal_produksi,
            'id_barang' => $result->id_barang,
            'id_satuan' => $result->id_satuan,
            'jumlah' => $result->jumlah,
            'total' => $result->total_biaya_produksi,
            'expired' => NULL
        ));

        foreach ($post['produksi_bahan_baku'] as $produksi_bahan_baku) {
            $produksi_bahan_baku['id_produksi'] = $result->id;
            $result_produksi_bahan_baku = $this->produksi_bahan_baku_m->insert($produksi_bahan_baku);
            if ($result_produksi_bahan_baku) {
                $this->fifo_m->insert('keluar', array(
                    'jenis_mutasi' => 'produksi',
                    'id_ref' => $result_produksi_bahan_baku->id,
                    'tanggal_mutasi' => $result->tanggal_produksi,
                    'id_barang' => $result_produksi_bahan_baku->id_barang,
                    'id_satuan' => $result_produksi_bahan_baku->id_satuan,
                    'jumlah' => $result_produksi_bahan_baku->jumlah,
                    'total' => 0
                ));
            }
        }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produksi'))))->route('produksi.produksi');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('produksi'))))->back();
        }
    }

    public function edit($id)
    {
        $title = "Produksi";
        $model = $this->produksi_m->view('produksi')->find_or_fail($id);
        $model->barang = $model->kode_barang . ' - ' . $model->nama_barang;
        $rs_bahan_baku = $this->barang_produksi_bahan_baku_m->view('bahan_baku')->where('id_barang_produksi', $id)->get();
        foreach ($rs_bahan_baku as $bahan_baku) {
            $model->bahan_baku[$bahan_baku->id_barang] = $bahan_baku;
        }
        $rs_produksi_bahan_baku = $this->produksi_bahan_baku_m->view('bahan_baku')->where('id_produksi', $id)->get();
        foreach ($rs_produksi_bahan_baku as $produksi_bahan_baku) {
            $model->produksi_bahan_baku[$produksi_bahan_baku->id_barang] = $produksi_bahan_baku;
        }
        $this->load->view('produksi/produksi/edit', array(
            'model' => $model, 'title' => $title
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $validate = array(
            'no_produksi' => 'required',
            'tanggal_produksi' => 'required',
            'id_barang_produksi' => 'required',
            'id_barang' => 'required',
            'id_satuan' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required|numeric|greater_than[0]',
            'total_bahan_baku' => 'required|numeric|greater_than[0]',
            'total_biaya_lainnya' => 'numeric',
            'total_biaya_produksi' => 'required|numeric|greater_than[0]',
            'hpp' => 'required|numeric|greater_than[0]',
            'produksi_bahan_baku[]' => 'required'
        );

        foreach ($post['produksi_bahan_baku'] as $key => $val) {
            $validate['produksi_bahan_baku[' . $key . '][id_satuan]'] = array(
                'field' => $this->localization->lang('produksi_bahan_baku_satuan', array('name' => $post['produksi_bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required'
            );
            $validate['produksi_bahan_baku[' . $key . '][jumlah]'] = array(
                'field' => $this->localization->lang('produksi_bahan_baku_jumlah', array('name' => $post['produksi_bahan_baku'][$key]['nama_barang'])),
                'rules' => 'required|numeric'
            );
        }
        $this->form_validation->validate($validate);
        $this->transaction->start();
        $this->produksi_m->update($id, $post);
        $result = $this->produksi_m->find_or_fail($id);
        $this->fifo_m->edit($id, 'masuk', array(
            'jenis_mutasi' => 'produksi',
            'id_ref' => $id,
            'tanggal_mutasi' => $result->tanggal_produksi,
            'id_barang' => $result->id_barang,
            'id_satuan' => $result->id_satuan,
            'jumlah' => $result->jumlah,
            'total' => $result->total_biaya_produksi,
            'expired' => NULL
        ));
        $rs_produksi_bahan_baku = $this->produksi_bahan_baku_m->where('id_produksi', $id)->get();
        foreach ($rs_produksi_bahan_baku as $produksi_bahan_baku) {
            $this->fifo_m->_delete($produksi_bahan_baku->id, 'keluar');
        }
        $this->produksi_bahan_baku_m->where('id_produksi', $id)->delete();
        foreach ($post['produksi_bahan_baku'] as $produksi_bahan_baku) {
            $produksi_bahan_baku['id_produksi'] = $id;
            $result_produksi_bahan_baku = $this->produksi_bahan_baku_m->insert($produksi_bahan_baku);
            if ($result_produksi_bahan_baku) {
                $this->fifo_m->insert('keluar', array(
                    'jenis_mutasi' => 'produksi',
                    'id_ref' => $result_produksi_bahan_baku->id,
                    'tanggal_mutasi' => $result->tanggal_produksi,
                    'id_barang' => $result_produksi_bahan_baku->id_barang,
                    'id_satuan' => $result_produksi_bahan_baku->id_satuan,
                    'jumlah' => $result_produksi_bahan_baku->jumlah,
                    'total' => 0
                ));
            }
        }
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('produksi'))))->route('produksi.produksi');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_store_message', array('name' => $this->localization->lang('produksi'))))->back();
        }
    }

    public function delete($id)
    {
        $this->transaction->start();
        $result = $this->produksi_m->find_or_fail($id);
        $this->fifo_m->delete($result->id, 'masuk');
        $rs_produksi_bahan_baku = $this->produksi_bahan_baku_m->where('id_produksi', $id)->get();
        foreach ($rs_produksi_bahan_baku as $produksi_bahan_baku) {
            $this->fifo_m->delete($produksi_bahan_baku->id, 'keluar');
        }
        $this->produksi_m->delete($id);
        $this->produksi_bahan_baku_m->where('id_produksi', $id)->delete();
        if ($this->transaction->complete()) {
            $this->redirect->with('success_message', $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('produksi'))))->route('produksi.produksi');
        } else {
            $this->redirect->with('error_message', $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('produksi'))))->back();
        }
    }
}
