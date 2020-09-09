<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Penjualan extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('shift_waktu_m');
        $this->load->model('user_cabang_m');
        $this->load->model('produk_m');
        $this->load->model('penjualan_produk_m');
    }

    public function index() {
        $rekap = array(
            'nota' => $this->localization->lang('nota'),
            'harian' => $this->localization->lang('harian'),
            'bulanan' => $this->localization->lang('bulanan')
        );
        $this->load->view('reports/penjualan', array(
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
			$this->penjualan_produk_m->where('penjualan.tanggal >= ', date('Y-m-d', strtotime($post['periode_awal'])));
		}
		if ($post['periode_akhir']) {
			$this->penjualan_produk_m->where('penjualan.tanggal <= ', date('Y-m-d', strtotime($post['periode_akhir'])));
		}
		if ($post['shift']) {
			$this->penjualan_produk_m->where('shift_aktif.id_shift_waktu', $post['shift']);
		}
		if ($post['shift_waktu']) {
			$this->penjualan_produk_m->where('shift_waktu.shift_waktu', $post['shift_waktu']);
		}
		if ($post['kasir']) {
			$this->penjualan_produk_m->where('penjualan.created_by', $post['kasir']);
		}
		if ($post['jenis_produk']) {
			$this->penjualan_produk_m->where('produk.jenis', $post['jenis_produk']);
		}

	    $grand_total = 0;
        switch ($post['rekap']) {
            case 'nota':
                $spreadsheet = IOFactory::load('public/reports/penjualan/nota.xlsx');
                $worksheet = $spreadsheet->getActiveSheet();

                $rs_penjualan_produk = $this->penjualan_produk_m->view('penjualan_nota')
                    ->scope('cabang')
                    ->order_by('penjualan.tanggal', 'asc')
                    ->order_by('penjualan_produk.id', 'asc')
                    ->get();

                $penjualan_produk = array();
                foreach ($rs_penjualan_produk as $r_penjualan_produk) {
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['no_penjualan'] = $r_penjualan_produk->no_penjualan;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['tanggal'] = $r_penjualan_produk->tanggal;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['kasir'] = $r_penjualan_produk->kasir;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['shift'] = $r_penjualan_produk->shift_waktu;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['pelanggan'] = $r_penjualan_produk->pelanggan;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['penjualan_total'] = $r_penjualan_produk->penjualan_total;
                    $penjualan_produk[$r_penjualan_produk->id_penjualan]['produk'][] = $r_penjualan_produk;
                }
                $row = 2;
                foreach ($penjualan_produk as $key => $data) {
                    $worksheet->getCell('A'.$row)->setValue($data['no_penjualan']);
                    $worksheet->getCell('B'.$row)->setValue($data['tanggal']);
                    $worksheet->getCell('C'.$row)->setValue($data['kasir']);
                    $worksheet->getCell('D'.$row)->setValue($data['shift']);
                    $worksheet->getCell('E'.$row)->setValue($data['pelanggan']);
                    $worksheet->getCell('Q'.$row)->setValue($data['penjualan_total']);
                    foreach ($data['produk'] as $produk) {
                        $worksheet->getCell('F'.$row)->setValue($produk->kode);
                        $worksheet->getCell('G'.$row)->setValue($produk->produk);
                        $worksheet->getCell('H'.$row)->setValue($produk->satuan_barang);
	                    if ($produk->konversi) {
		                    $produk->jumlah *= $produk->konversi;
		                    $produk->harga /= $produk->konversi;
	                    }
                        $worksheet->getCell('I'.$row)->setValue($produk->jumlah);
                        $worksheet->getCell('J'.$row)->setValue($produk->harga);
                        $worksheet->getCell('K'.$row)->setValue($produk->diskon);
                        $worksheet->getCell('L'.$row)->setValue($produk->potongan);
                        $worksheet->getCell('M'.$row)->setValue($produk->subtotal);
                        $worksheet->getCell('N'.$row)->setValue($produk->tuslah);
                        $worksheet->getCell('O'.$row)->setValue($produk->ppn);
                        $worksheet->getCell('P'.$row)->setValue($produk->total);
	                    $grand_total += $produk->total;
                        for($i=0;$i<17;$i++){
                            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
                        }
                        $row++;
                    }
                }

	            $worksheet->getCell('A'.$row)->setValue('Grand Total');
	            $worksheet->getCell('Q'.$row)->setValue($grand_total);
	            $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':P'.$row);
	            for($i=0;$i<17;$i++){
		            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
	            }

				foreach ($worksheet->getColumnDimensions() as $colDim) {
					$colDim->setAutoSize(true);
				}
				$status='nota';
				break;
			case 'harian':
				$spreadsheet = IOFactory::load('public/reports/penjualan/harian.xlsx');
				$worksheet = $spreadsheet->getActiveSheet();

                $rs_penjualan_produk = $this->penjualan_produk_m->view('penjualan_rekap_harian')
                    ->scope('cabang')
                    ->order_by('penjualan.tanggal', 'asc')
                    ->order_by('penjualan_produk.id_produk', 'asc')
                    ->order_by('penjualan_produk.id_satuan', 'asc')
                    ->group_by(array(
                        'penjualan_produk.id_satuan',
                        'penjualan.tanggal',
                        'produk.kode',
                        'produk.produk',
                        'satuan_produk.satuan',
                        'barang.id_satuan',
                        'satuan_barang.satuan',
                        'konversi_satuan.konversi',
                    ))
                    ->get();
                $row = 2;
				$record_penjualan_produk = array();
	            foreach ($rs_penjualan_produk as $r_penjualan_produk) {
		            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['tanggal'] = $r_penjualan_produk->tanggal;
		            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['kode'] = $r_penjualan_produk->kode;
		            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['produk'] = $r_penjualan_produk->produk;
		            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['satuan_barang'] = $r_penjualan_produk->satuan_barang;
		            $jumlah = $r_penjualan_produk->jumlah * $r_penjualan_produk->konversi;
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['jumlah'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['jumlah'] += $jumlah;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['jumlah'] = $jumlah;
		            }
		            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['harga'] = $r_penjualan_produk->subtotal / $jumlah;
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['subtotal'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['subtotal'] += $r_penjualan_produk->subtotal;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['subtotal'] = $r_penjualan_produk->subtotal;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['diskon'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['diskon'] += $r_penjualan_produk->diskon;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['diskon'] = $r_penjualan_produk->diskon;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['potongan'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['potongan'] += $r_penjualan_produk->potongan;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['potongan'] = $r_penjualan_produk->potongan;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['tuslah'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['tuslah'] += $r_penjualan_produk->tuslah;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['tuslah'] = $r_penjualan_produk->tuslah;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['ppn'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['ppn'] += $r_penjualan_produk->ppn;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['ppn'] = $r_penjualan_produk->ppn;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['total'])) {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['total'] += $r_penjualan_produk->total;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->tanggal][$r_penjualan_produk->kode]['total'] = $r_penjualan_produk->total;
		            }
	            }

                foreach ($record_penjualan_produk as $key => $rs_penjualan_produk) {
	                foreach ($rs_penjualan_produk as $r_penjualan_produk) {
		                $r_penjualan_produk = (object)$r_penjualan_produk;
		                $worksheet->getCell('A'.$row)->setValue($r_penjualan_produk->tanggal);
		                $worksheet->getCell('B'.$row)->setValue($r_penjualan_produk->kode);
		                $worksheet->getCell('C'.$row)->setValue($r_penjualan_produk->produk);
		                $worksheet->getCell('D'.$row)->setValue($r_penjualan_produk->satuan_barang);
		                $worksheet->getCell('E'.$row)->setValue($r_penjualan_produk->jumlah);
		                $worksheet->getCell('F'.$row)->setValue($r_penjualan_produk->harga);
		                $worksheet->getCell('G'.$row)->setValue($r_penjualan_produk->subtotal);
		                $worksheet->getCell('H'.$row)->setValue($r_penjualan_produk->diskon);
		                $worksheet->getCell('I'.$row)->setValue($r_penjualan_produk->potongan);
		                $worksheet->getCell('J'.$row)->setValue($r_penjualan_produk->tuslah);
		                $worksheet->getCell('K'.$row)->setValue($r_penjualan_produk->ppn);
		                $worksheet->getCell('L'.$row)->setValue($r_penjualan_produk->total);
		                $grand_total += $r_penjualan_produk->total;
		                for($i=0;$i<12;$i++){
			                $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
		                }
		                $row++;
	                }
                }

	            $worksheet->getCell('A'.$row)->setValue('Grand Total');
	            $worksheet->getCell('L'.$row)->setValue($grand_total);
	            $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':K'.$row);
	            for($i=0;$i<12;$i++){
		            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
	            }

				foreach ($worksheet->getColumnDimensions() as $colDim) {
					$colDim->setAutoSize(true);
				}
				$status='harian';
				break;
			case 'bulanan':
				$spreadsheet = IOFactory::load('public/reports/penjualan/bulanan.xlsx');
				$worksheet = $spreadsheet->getActiveSheet();

                $rs_penjualan_produk = $this->penjualan_produk_m->view('penjualan_rekap_bulanan')
                    ->scope('cabang')
                    ->order_by('penjualan.tanggal', 'asc')
                    ->order_by('penjualan_produk.id_produk', 'asc')
                    ->order_by('penjualan_produk.id_satuan', 'asc')
                    ->group_by(array(
                        'penjualan_produk.id_satuan',
                        'left(penjualan.tanggal, 7)',
                        'produk.kode',
                        'produk.produk',
                        'satuan_produk.satuan',
                        'barang.id_satuan',
                        'satuan_barang.satuan',
                        'konversi_satuan.konversi',
                    ))
                    ->get();
                $row = 2;
	            $record_penjualan_produk = array();
	            foreach ($rs_penjualan_produk as $r_penjualan_produk) {
		            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['bulan'] = $r_penjualan_produk->bulan;
		            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['kode'] = $r_penjualan_produk->kode;
		            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['produk'] = $r_penjualan_produk->produk;
		            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['satuan_barang'] = $r_penjualan_produk->satuan_barang;
		            $jumlah = $r_penjualan_produk->jumlah * $r_penjualan_produk->konversi;
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['jumlah'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['jumlah'] += $jumlah;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['jumlah'] = $jumlah;
		            }
		            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['harga'] = $r_penjualan_produk->subtotal / $jumlah;
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['subtotal'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['subtotal'] += $r_penjualan_produk->subtotal;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['subtotal'] = $r_penjualan_produk->subtotal;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['diskon'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['diskon'] += $r_penjualan_produk->diskon;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['diskon'] = $r_penjualan_produk->diskon;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['potongan'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['potongan'] += $r_penjualan_produk->potongan;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['potongan'] = $r_penjualan_produk->potongan;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['tuslah'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['tuslah'] += $r_penjualan_produk->tuslah;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['tuslah'] = $r_penjualan_produk->tuslah;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['ppn'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['ppn'] += $r_penjualan_produk->ppn;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['ppn'] = $r_penjualan_produk->ppn;
		            }
		            if (isset($record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['total'])) {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['total'] += $r_penjualan_produk->total;
		            } else {
			            $record_penjualan_produk[$r_penjualan_produk->bulan][$r_penjualan_produk->kode]['total'] = $r_penjualan_produk->total;
		            }
	            }

	            foreach ($record_penjualan_produk as $key => $rs_penjualan_produk) {
		            foreach ($rs_penjualan_produk as $r_penjualan_produk) {
			            $r_penjualan_produk = (object)$r_penjualan_produk;
			            $worksheet->getCell('A'.$row)->setValue($r_penjualan_produk->bulan);
			            $worksheet->getCell('B'.$row)->setValue($r_penjualan_produk->kode);
			            $worksheet->getCell('C'.$row)->setValue($r_penjualan_produk->produk);
			            $worksheet->getCell('D'.$row)->setValue($r_penjualan_produk->satuan_barang);
			            $worksheet->getCell('E'.$row)->setValue($r_penjualan_produk->jumlah);
			            $worksheet->getCell('F'.$row)->setValue($r_penjualan_produk->harga);
			            $worksheet->getCell('G'.$row)->setValue($r_penjualan_produk->subtotal);
			            $worksheet->getCell('H'.$row)->setValue($r_penjualan_produk->diskon);
			            $worksheet->getCell('I'.$row)->setValue($r_penjualan_produk->potongan);
			            $worksheet->getCell('J'.$row)->setValue($r_penjualan_produk->tuslah);
			            $worksheet->getCell('K'.$row)->setValue($r_penjualan_produk->ppn);
			            $worksheet->getCell('L'.$row)->setValue($r_penjualan_produk->total);
			            $grand_total += $r_penjualan_produk->total;
			            for($i=0;$i<12;$i++){
				            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
			            }
			            $row++;
		            }
	            }

	            $worksheet->getCell('A'.$row)->setValue('Grand Total');
	            $worksheet->getCell('L'.$row)->setValue($grand_total);
	            $spreadsheet->getActiveSheet()->mergeCells('A'.$row.':K'.$row);
	            for($i=0;$i<12;$i++){
		            $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
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
		header('Content-Disposition: attachment; filename="jual_'. $status. '_'. $post['periode_awal']. '__'. $post['periode_akhir']. '_'. $post['shift_waktu']. '.xlsx"');
		$writer->save("php://output");
	}
}