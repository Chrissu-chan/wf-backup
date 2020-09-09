<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Pembelian extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('supplier_cabang_m');
        $this->load->model('user_cabang_m');
        $this->load->model('pembelian_barang_m');
    }

    public function index() {
        $rekap = array(
            'nota' => $this->localization->lang('nota'),
            'harian' => $this->localization->lang('harian'),
            'bulanan' => $this->localization->lang('bulanan')
        );
        $this->load->view('reports/pembelian', array(
            'rekap' => $rekap
        ));
    }

    public function excel() {
        $post = $this->input->post();

        $cols = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        $style=array(
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
                'left' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
                'right' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if ($post['periode_awal']) {
            $this->pembelian_barang_m->where('pembelian.tanggal >= ', date('Y-m-d', strtotime($post['periode_awal'])));
        }
        if ($post['periode_akhir']) {
            $this->pembelian_barang_m->where('pembelian.tanggal <= ', date('Y-m-d', strtotime($post['periode_akhir'])));
        }
         if ($post['supplier']) {
            $this->pembelian_barang_m->where('pembelian.id_supplier', $post['supplier']);
        }
        if ($post['user']) {
            $this->pembelian_barang_m->where('pembelian.created_by', $post['user']);
        }

        switch ($post['rekap']) {
            case 'nota':
                $spreadsheet = IOFactory::load('public/reports/pembelian/nota.xlsx');
                $worksheet = $spreadsheet->getActiveSheet();

                $rs_pembelian_barang = $this->pembelian_barang_m->view('pembelian_nota')
                    ->scope('cabang')
                    ->order_by('pembelian.tanggal', 'asc')
                    ->order_by('pembelian_barang.id', 'asc')
                    ->get();

                $pembelian_barang = array();
                foreach ($rs_pembelian_barang as $r_pembelian_barang) {
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['no_pembelian'] = $r_pembelian_barang->no_pembelian;
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['tanggal'] = $r_pembelian_barang->tanggal;
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['user'] = $r_pembelian_barang->user;
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['supplier'] = $r_pembelian_barang->supplier;
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['pembelian_total'] = $r_pembelian_barang->pembelian_total;
                    $pembelian_barang[$r_pembelian_barang->id_pembelian]['barang'][] = $r_pembelian_barang;
                }
                $row = 2;
                foreach ($pembelian_barang as $key => $data) {
                    $worksheet->getCell('A'.$row)->setValue($data['no_pembelian']);
                    $worksheet->getCell('B'.$row)->setValue($data['tanggal']);
                    $worksheet->getCell('C'.$row)->setValue($data['user']);
                    $worksheet->getCell('D'.$row)->setValue($data['supplier']);
                    $worksheet->getCell('P'.$row)->setValue($data['pembelian_total']);
                    foreach ($data['barang'] as $barang) {
                        $worksheet->getCell('E'.$row)->setValue($barang->kode);
                        $worksheet->getCell('F'.$row)->setValue($barang->barang);
                        $worksheet->getCell('G'.$row)->setValue($barang->expired);
                        $worksheet->getCell('H'.$row)->setValue($barang->satuan_beli);
                        $worksheet->getCell('I'.$row)->setValue($barang->jumlah);
                        $worksheet->getCell('J'.$row)->setValue($barang->harga);
                        $worksheet->getCell('K'.$row)->setValue($barang->diskon);
                        $worksheet->getCell('L'.$row)->setValue($barang->potongan);
                        $worksheet->getCell('M'.$row)->setValue($barang->subtotal);
                        $worksheet->getCell('N'.$row)->setValue($barang->ppn);
                        $worksheet->getCell('O'.$row)->setValue($barang->total);
                        for($i=0;$i<16;$i++){
                            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
                        }
                        $row++;
                    }
                }

                foreach ($worksheet->getColumnDimensions() as $colDim) {
                    $colDim->setAutoSize(true);
                }
                $status='nota';
                break;
            case 'harian':
                $spreadsheet = IOFactory::load('public/reports/pembelian/harian.xlsx');
                $worksheet = $spreadsheet->getActiveSheet();
                $rs_pembelian_barang = $this->pembelian_barang_m->view('pembelian_rekap_harian')
                    ->scope('cabang')
                    ->order_by('pembelian.tanggal', 'asc')
                    ->order_by('pembelian_barang.id_barang', 'asc')
                    ->order_by('pembelian_barang.id_satuan', 'asc')
                    ->group_by(array(
                        'pembelian_barang.id_satuan',
                        'pembelian.tanggal',
                        'pembelian.total',
                        'satuan_beli.satuan',
                        'barang.kode',
                        'barang.nama',
                        'konversi_satuan.konversi'
                    ))
                    ->get();
                $row = 2;
                foreach ($rs_pembelian_barang as $r_pembelian_barang) {
                    $worksheet->getCell('A'.$row)->setValue($r_pembelian_barang->tanggal);
                    $worksheet->getCell('B'.$row)->setValue($r_pembelian_barang->kode);
                    $worksheet->getCell('C'.$row)->setValue($r_pembelian_barang->barang);
                    $worksheet->getCell('D'.$row)->setValue($r_pembelian_barang->satuan_beli);
                    $worksheet->getCell('E'.$row)->setValue($r_pembelian_barang->jumlah);
                    if ($r_pembelian_barang->id_satuan_barang) {
                        if ($r_pembelian_barang->id_satuan <> $r_pembelian_barang->id_satuan_barang) {
                            $worksheet->getCell('D'.$row)->setValue($r_pembelian_barang->satuan_barang);
                            $worksheet->getCell('E'.$row)->setValue($r_pembelian_barang->jumlah * $r_pembelian_barang->konversi);
                        }
                    }
                    $worksheet->getCell('F'.$row)->setValue($r_pembelian_barang->satuan_beli);
                    $worksheet->getCell('G'.$row)->setValue($r_pembelian_barang->jumlah);
                    $worksheet->getCell('H'.$row)->setValue($r_pembelian_barang->subtotal / $r_pembelian_barang->jumlah);
                    $worksheet->getCell('I'.$row)->setValue($r_pembelian_barang->subtotal);
                    $worksheet->getCell('J'.$row)->setValue($r_pembelian_barang->diskon);
                    $worksheet->getCell('K'.$row)->setValue($r_pembelian_barang->potongan);
                    $worksheet->getCell('L'.$row)->setValue($r_pembelian_barang->ppn);
                    $worksheet->getCell('M'.$row)->setValue($r_pembelian_barang->total);
                    for($i=0;$i<13;$i++){
                        $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
                    }
                    $row++;
                }

                foreach ($worksheet->getColumnDimensions() as $colDim) {
                    $colDim->setAutoSize(true);
                }
                $status='harian';
                break;
            case 'bulanan':
                $spreadsheet = IOFactory::load('public/reports/pembelian/bulanan.xlsx');
                $worksheet = $spreadsheet->getActiveSheet();
                $rs_pembelian_barang = $this->pembelian_barang_m->view('pembelian_rekap_bulanan')
                    ->scope('cabang')
                    ->order_by('pembelian.tanggal', 'asc')
                    ->order_by('pembelian_barang.id_barang', 'asc')
                    ->order_by('pembelian_barang.id_satuan', 'asc')
                    ->group_by(array(
                        'pembelian_barang.id_satuan',
                        'left(pembelian.tanggal, 7)',
                        'pembelian.total',
                        'satuan_beli.satuan',
                        'barang.kode',
                        'barang.nama',
                        'konversi_satuan.konversi'
                    ))
                    ->get();
                $row = 2;
                foreach ($rs_pembelian_barang as $r_pembelian_barang) {
                    $worksheet->getCell('A'.$row)->setValue($r_pembelian_barang->bulan);
                    $worksheet->getCell('B'.$row)->setValue($r_pembelian_barang->kode);
                    $worksheet->getCell('C'.$row)->setValue($r_pembelian_barang->barang);
                    $worksheet->getCell('D'.$row)->setValue($r_pembelian_barang->satuan_beli);
                    $worksheet->getCell('E'.$row)->setValue($r_pembelian_barang->jumlah);
                    if ($r_pembelian_barang->id_satuan_barang) {
                        if ($r_pembelian_barang->id_satuan <> $r_pembelian_barang->id_satuan_barang) {
                            $worksheet->getCell('D'.$row)->setValue($r_pembelian_barang->satuan_barang);
                            $worksheet->getCell('E'.$row)->setValue($r_pembelian_barang->jumlah * $r_pembelian_barang->konversi);
                        }
                    }
                    $worksheet->getCell('F'.$row)->setValue($r_pembelian_barang->satuan_beli);
                    $worksheet->getCell('G'.$row)->setValue($r_pembelian_barang->jumlah);
                    $worksheet->getCell('H'.$row)->setValue($r_pembelian_barang->subtotal / $r_pembelian_barang->jumlah);
                    $worksheet->getCell('I'.$row)->setValue($r_pembelian_barang->subtotal);
                    $worksheet->getCell('J'.$row)->setValue($r_pembelian_barang->diskon);
                    $worksheet->getCell('K'.$row)->setValue($r_pembelian_barang->potongan);
                    $worksheet->getCell('L'.$row)->setValue($r_pembelian_barang->ppn);
                    $worksheet->getCell('M'.$row)->setValue($r_pembelian_barang->total);
                    for($i=0;$i<13;$i++){
                        $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
                    }
                    $row++;
                }

                foreach ($worksheet->getColumnDimensions() as $colDim) {
                    $colDim->setAutoSize(true);
                }
                $status='bulanan';
                break;
            default:
                # code...
                break;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="beli_'. $status. '_'. $post['periode_awal']. '__'. $post['periode_akhir']. '.xlsx"');
        $writer->save("php://output");
    }
}