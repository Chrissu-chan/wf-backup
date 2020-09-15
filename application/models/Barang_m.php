<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_m extends BaseModel {

    protected $table = 'barang';
    protected $primary_key = 'id';
    protected $fillable = array('kode','barcode','nama','id_kategori_barang','id_jenis_barang','id_satuan','id_satuan_beli','minus', 'id_rak_gudang');
    protected $default = array(
        'id_rak_gudang' => 0
    );

    protected $id_gudang;

    public function __construct() {
        $this->load->model('barang_stok_m');
        $this->load->model('cabang_gudang_m');

        if ($this->session->userdata('cabang')) {
            $this->id_gudang = $this->cabang_gudang_m->scope('utama')
                ->where('id_cabang', $this->session->userdata('cabang')->id)
                ->first()->id_gudang;
        }
    }

    public function view_barang() {
        $this->db->select('barang.*, kategori_barang.kategori_barang, jenis_barang.jenis_barang, satuan.satuan, obat.total')
            ->join('kategori_barang', 'kategori_barang.id = barang.id_kategori_barang')
            ->join('jenis_barang', 'jenis_barang.id = barang.id_jenis_barang')
            ->join('satuan', 'satuan.id = barang.id_satuan')
	        ->join('obat', 'obat.id_barang = barang.id');
    }

    public function scope_not_located() {
        $this->db->where('id_rak_gudang', 0);
    }

	public function enum_minus() {
		return array(
			'0' => 'False',
			'1' => 'True'
		);
	}
}