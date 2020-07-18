<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_barang_m extends BaseModel {

    protected $table = 'pembelian_barang';
    protected $primary_key = 'id';
    protected $fillable = array('id_pembelian', 'id_barang', 'id_satuan', 'jumlah', 'harga', 'diskon_persen', 'diskon', 'potongan', 'subtotal', 'ppn_persen', 'ppn', 'total', 'expired', 'batch_number');

    public function view_pembelian_barang() {
        $this->db->select('pembelian_barang.*, barang.kode AS kode_barang, barang.nama AS nama_barang, satuan.satuan as satuan, barang.id_satuan AS barang_id_satuan, barang_satuan.satuan AS barang_satuan')
            ->join('barang', 'barang.id = pembelian_barang.id_barang')
            ->join('satuan', 'satuan.id = pembelian_barang.id_satuan')
            ->join('satuan as barang_satuan', 'barang_satuan.id = barang.id_satuan');
    }

    public function view_pembelian_nota() {
        $this->db->select('pembelian_barang.*,
                pembelian.no_pembelian,
                pembelian.tanggal,
                pembelian.total as pembelian_total,
                users.name as user,
                supplier.supplier,
                satuan_beli.satuan as satuan_beli,
                barang.kode,
                barang.nama as barang')
            ->join('pembelian', 'pembelian.id = pembelian_barang.id_pembelian')
            ->join('supplier', 'supplier.id = pembelian.id_supplier')
            ->join('users', 'users.username = pembelian.created_by')
            ->join('satuan satuan_beli', 'satuan_beli.id = pembelian_barang.id_satuan')
            ->join('barang', 'barang.id = pembelian_barang.id_barang')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan');
    }

    public function view_pembelian_rekap_harian() {
        $this->db->select('pembelian_barang.id_satuan,
                pembelian.tanggal,
                pembelian.total as pembelian_total,
                satuan_beli.satuan as satuan_beli,
                barang.kode,
                barang.nama as barang,
                barang.id_satuan as id_satuan_barang,
                satuan_barang.satuan as satuan_barang,
                konversi_satuan.konversi,
                sum(pembelian_barang.total) as total,
                sum(pembelian_barang.ppn) as ppn,
                sum(pembelian_barang.diskon * pembelian_barang.jumlah) as diskon,
                sum(pembelian_barang.potongan * pembelian_barang.jumlah) as potongan,
                sum(pembelian_barang.jumlah * pembelian_barang.harga) as subtotal,
                sum(pembelian_barang.jumlah) as jumlah')
            ->join('pembelian', 'pembelian.id = pembelian_barang.id_pembelian')
            ->join('supplier', 'supplier.id = pembelian.id_supplier')
            ->join('users', 'users.username = pembelian.created_by')
            ->join('satuan satuan_beli', 'satuan_beli.id = pembelian_barang.id_satuan')
            ->join('barang', 'barang.id = pembelian_barang.id_barang')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan')
            ->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = pembelian_barang.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left');
    }

    public function view_pembelian_rekap_bulanan() {
        $this->db->select('pembelian_barang.id_satuan,
                left(pembelian.tanggal, 7) as bulan,
                pembelian.total as pembelian_total,
                satuan_beli.satuan as satuan_beli,
                barang.kode,
                barang.nama as barang,
                barang.id_satuan as id_satuan_barang,
                satuan_barang.satuan as satuan_barang,
                konversi_satuan.konversi,
                sum(pembelian_barang.total) as total,
                sum(pembelian_barang.ppn) as ppn,
                sum(pembelian_barang.diskon * pembelian_barang.jumlah) as diskon,
                sum(pembelian_barang.potongan * pembelian_barang.jumlah) as potongan,
                sum(pembelian_barang.jumlah * pembelian_barang.harga) as subtotal,
                sum(pembelian_barang.jumlah) as jumlah')
            ->join('pembelian', 'pembelian.id = pembelian_barang.id_pembelian')
            ->join('supplier', 'supplier.id = pembelian.id_supplier')
            ->join('users', 'users.username = pembelian.created_by')
            ->join('satuan satuan_beli', 'satuan_beli.id = pembelian_barang.id_satuan')
            ->join('barang', 'barang.id = pembelian_barang.id_barang')
            ->join('satuan satuan_barang', 'satuan_barang.id = barang.id_satuan')
            ->join('konversi_satuan', 'konversi_satuan.id_satuan_asal = pembelian_barang.id_satuan AND konversi_satuan.id_satuan_tujuan = barang.id_satuan', 'left');
    }

    public function set_jumlah($value) {
        return $this->localization->number_value($value);
    }

    public function set_harga($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_diskon($value) {
        return $this->localization->number_value($value);
    }

    public function set_potongan($value) {
        return $this->localization->number_value($value);
    }

    public function set_subtotal($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }

    public function set_expired($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function scope_cabang() {
        $this->db->where('pembelian.id_cabang', $this->session->userdata('cabang')->id);
    }
}