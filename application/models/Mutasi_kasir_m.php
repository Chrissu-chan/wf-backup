<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_kasir_m extends BaseModel {

    protected $table = 'mutasi_kasir';
    protected $primary_key = 'id';
    protected $fillable = array('id_shift_aktif_kasir', 'no_mutasi', 'no_referensi', 'tanggal_mutasi', 'id_jenis_transaksi', 'tipe', 'nominal', 'file', 'status', 'keterangan', 'flag_jurnal', 'proses_jurnal', 'batal', 'jenis_batal', 'alasan_batal', 'deleted_by', 'deleted_at');
    protected $authors = true;
   	protected $timestamps = true;
	protected $id_shift_aktif_kasir;

	public function __construct() {
		$this->default = array(
			'id_cabang' => $this->session->userdata('cabang')->id,
			'status' => 'approved',
			'flag_jurnal' => 'true',
			'proses_jurnal' => 'false',
			'batal' => 0
		);

		$shift_aktif = $this->shift_aktif_m->view('shift_aktif')
			->scope('cabang')
			->scope('aktif')
			->first();
		if ($shift_aktif) {
			$this->id_shift_aktif_kasir = $shift_aktif->id_shift_aktif_kasir;
		}
	}

    public function view_mutasi_kasir() {
        $this->db->select('mutasi_kasir.*,
            jenis_transaksi.jenis_transaksi
        ')
        ->join('jenis_transaksi', 'jenis_transaksi.id = mutasi_kasir.id_jenis_transaksi');
    }

    public function enum_tipe() {
        return array(
            'pemasukan' => 'Pemasukan',
            'pengeluaran' => 'Pengeluaran'
        );
    }

	public function enum_jenis_batal() {
		return array(
			'cancel' => 'Cancel',
			'retur' => 'Retur'
		);
	}

    public function scope_shift_aktif_kasir() {
        $this->db->where('id_shift_aktif_kasir', $this->id_shift_aktif_kasir);
    }

	public function scope_pemasukan() {
		$this->db->where('tipe', 'pemasukan');
	}

	public function scope_pengeluaran() {
		$this->db->where('tipe', 'pengeluaran');
	}

    public function set_tanggal_mutasi($value) {
    	return date('Y-m-d', strtotime($value));
    }

    public function set_nominal($value) {
        return $this->localization->number_value($value);
    }

    public function appr($id) {
        return $this->update($id, array('status' => 'approved'));
    }

    public function cancel_appr($id) {
        return $this->update($id, array('status' => 'waiting-approval'));
    }
}