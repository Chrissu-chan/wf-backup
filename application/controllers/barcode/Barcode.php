<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Barcode extends BaseController {

        public function __construct() {
            parent::__construct();
            $this->load->model('barang_m');
            $this->load->library('form_validation');
            $this->load->helper('file');
        }

        public function index() {
            $this->load->view('barcode/barcode/index');
        }

        public function print_barcode() {
            $post = $this->input->post();
            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            $record = array();
            if ($post['barang']) {
                foreach ($post['barang'] as $key => $barang) {
                    for ($i=0; $i<$barang['jumlah']; $i++) {
                        $record[] = array(
                            'id_barang' => $barang['id_barang'],
                            'kode_barang' => $barang['kode_barang'],
                            'nama_barang' => $barang['nama_barang'],
                            'barcode' => base64_encode($generator->getBarcode($barang['barcode'], $generator::TYPE_CODE_128))
                        );
                    }
                }
            }
            $print_barcode = $this->load->view('barcode/barcode/print', array('barang' => $record), TRUE);
            $file_name = date('YmdHis');
            if (write_file(APPPATH.'views/barcode/barcode/'.$file_name.'.php', $print_barcode)){
                $response = array(
                    'success' => true,
                    'file_name' => $file_name,
                    'message' => $this->localization->lang('success_print_barcode_message', array('name' => $this->localization->lang('barcode')))
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => $this->localization->lang('error_print_barcode_message', array('name' => $this->localization->lang('barcode')))
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }

        public function view_barcode($file_name) {
            $this->load->view('barcode/barcode/'.$file_name);
            unlink(APPPATH.'views/barcode/barcode/'.$file_name.'.php');
        }
    }