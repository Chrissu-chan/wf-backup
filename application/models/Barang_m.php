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
    
    public function view_barang_stock_opname() {
	    $this->db->select('barang.*, satuan.satuan, obat.id AS id_obat, stock_opname_barang.id AS stock_opname_barang')
		    ->join('obat', 'obat.id_barang = barang.id')
            ->join('satuan', 'satuan.id = barang.id_satuan')
		    ->join('stock_opname_barang', 'stock_opname_barang.id_barang = barang.id AND stock_opname_barang.id_cabang = \''.$this->session->userdata('cabang')->id.'\'', 'left');
    }

    public function view_barang_stok() {
        $this->db->select('barang.*, kategori_barang.kategori_barang, jenis_barang.jenis_barang, satuan.satuan, obat.total, barang_stok.jumlah AS stok')
            ->join('kategori_barang', 'kategori_barang.id = barang.id_kategori_barang')
            ->join('jenis_barang', 'jenis_barang.id = barang.id_jenis_barang')
            ->join('satuan', 'satuan.id = barang.id_satuan')
            ->join('obat', 'obat.id_barang = barang.id')
            ->join('barang_stok', 'barang_stok.id_barang = barang.id AND barang_stok.id_gudang = '. $this->id_gudang, 'left');           
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