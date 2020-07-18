<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_cabang_m extends BaseModel {

    protected $table = 'supplier_cabang';
    protected $primary_key = 'id';
    protected $fillable = array('id_supplier','id_cabang');

    public function view_supplier() {
        $this->db->select('supplier.*')
            ->join('supplier', 'supplier.id = supplier_cabang.id_supplier')
            ->group_by('supplier.id');
    }

    public function scope_cabang_aktif() {
        $this->db->group_start()
            ->where('id_cabang', $this->session->userdata('cabang')->id)
            ->or_where('id_cabang', 0)
            ->group_end();
    }
}