<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_m extends BaseModel {

    protected $table = 'supplier';
    protected $primary_key = 'id';
    protected $fillable = array('id_kategori_supplier','id_jenis_supplier','supplier','nama','jenis_kelamin','telepon','id_kota','alamat','id_bank','no_rekening','nama_rekening');

    public function view_supplier() {
        $this->db->select('supplier.*, jenis_supplier.jenis_supplier, kategori_supplier.kategori_supplier, bank.bank, kota.kota')
            ->join('kategori_supplier', 'kategori_supplier.id = supplier.id_kategori_supplier')
            ->join('jenis_supplier', 'jenis_supplier.id = supplier.id_jenis_supplier')
            ->join('bank', 'bank.id = supplier.id_bank', 'left')
            ->join('kota', 'kota.id = supplier.id_kota', 'left');
    }

	public function scope_auth() {
		$this->db->join('supplier_cabang', 'supplier_cabang.id_supplier = supplier.id')
			->where_in('id_cabang', $this->auth->cabang)
			->or_where('id_cabang', 0);
	}

    public function enum_jenis_kelamin() {
        return array(
            'male' => 'Laki-laki',
            'female' => 'Perempuan'
        );
    }

}