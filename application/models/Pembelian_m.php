<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_m extends BaseModel {

    protected $table = 'pembelian';
    protected $primary_key = 'id';
    protected $fillable = array('id_cabang','id_shift_aktif','id_supplier','no_pembelian','tanggal','diskon_persen','subtotal','ppn','total','metode_pembayaran','jatuh_tempo','id_kas_bank','no_ref', 'flag_jurnal', 'proses_jurnal', 'batal', 'jenis_batal', 'alasan_batal', 'deleted_by', 'deleted_at');
    protected $authors = true;
    protected $timestamps = true;

    public function __construct() {
        $this->default = array(
            'id_cabang' => $this->session->userdata('cabang')->id,
	        'flag_jurnal' => 'true',
	        'proses_jurnal' => 'false',
	        'batal' => 0
        );
    }

    public function view_pembelian() {
        $this->db->select('pembelian.*, supplier.nama AS supplier, kas_bank.nama AS kas_bank')
            ->join('supplier', 'supplier.id = pembelian.id_supplier')
            ->join('kas_bank', 'kas_bank.id = pembelian.id_kas_bank');
    }

    public function scope_cabang() {
        $this->db->where('id_cabang', $this->session->userdata('cabang')->id);
    }

    public function enum_metode_pembayaran() {
        return array(
            'tunai' => 'Tunai',
            'utang' => 'Utang'
        );
    }

	public function enum_jenis_batal() {
		return array(
			'cancel' => 'Cancel',
			'retur' => 'Retur'
		);
	}

    public function set_tanggal($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_jatuh_tempo($value) {
        return date('Y-m-d', strtotime($value));
    }

    public function set_diskon_persen($value) {
        return $this->localization->number_value($value);
    }

    public function set_subtotal($value) {
        return $this->localization->number_value($value);
    }

    public function set_ppn($value) {
        return $this->localization->number_value($value);
    }

    public function set_total($value) {
        return $this->localization->number_value($value);
    }
}