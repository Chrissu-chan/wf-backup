<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->load->model('broadcast_harga_produk_m');
		$this->load->model('produk_harga_m');
	}

    public function index() {
	    $broadcast_harga_produk = $this->broadcast_harga_produk_m->view('broadcast_harga_produk')
		    ->order_by('tanggal', 'DESC')
		    ->limit(20)
		    ->get();
	    $margin_laba = $this->produk_harga_m->view('margin_laba')
		    ->get();
        $this->load->view('dashboard/index', array(
	        'broadcast_harga_produk' => $broadcast_harga_produk,
	        'margin_laba' => $margin_laba
        ));
    }
}