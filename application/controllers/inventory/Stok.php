<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Stok extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('barang_stok_m');
        $this->load->model('barang_stok_mutasi_m');
        $this->load->model('cabang_gudang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            $gudang = $this->input->get('gudang');
            $tanggal_awal = $this->input->get('tanggal_awal');
            $tanggal_akhir = $this->input->get('tanggal_akhir');
            return $this->datatable->resource($this->barang_stok_m, false)
                ->stok($tanggal_awal, $tanggal_akhir)
                ->where('barang_stok.id_gudang', $gudang)
	            ->order_by('barang_stok.id_barang', 'ASC')
                ->edit_column('jumlah', function($model) {
                    return $this->localization->number($model->stok_awal);
                })
                ->edit_column('jumlah', function($model) {
                    return $this->localization->number($model->mutasi);
                })
                ->edit_column('jumlah', function($model) {
                    return $this->localization->number($model->stok_akhir);
                })
                ->add_action('{detail}', array(
                    'detail' => function($model) use ($gudang, $tanggal_awal, $tanggal_akhir) {
                        return $this->action->link('view', $this->url_generator->current_url().'/detail/'.$model->id_barang.'?gudang='.$gudang.'&tanggal_awal='.$tanggal_awal.'&tanggal_akhir='.$tanggal_akhir, 'class="btn btn-primary btn-sm"', $this->localization->lang('kartu_stok'));
                    }
                ))
                ->generate();
        }
        $this->load->view('inventory/stok/index');
    }

    public function detail($id) {
        $gudang = $this->input->get('gudang');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $model = $this->barang_stok_m->stok_awal($tanggal_awal)
	        ->where('barang_stok.id_gudang', $gudang)
	        ->where('barang_stok.id_barang', $id)
	        ->first_or_fail();
        $model->mutasi = $this->barang_stok_mutasi_m->view('barang_stok_mutasi')
            ->where('barang_stok_mutasi.tanggal_mutasi >= ', date('Y-m-d',strtotime($tanggal_awal)))
            ->where('barang_stok_mutasi.tanggal_mutasi <= ', date('Y-m-d',strtotime($tanggal_akhir)))
            ->where('barang_stok_mutasi.id_gudang', $gudang)
            ->where('barang_stok_mutasi.id_barang', $id)
            ->get();
        $this->load->view('inventory/stok/detail', array(
            'model' => $model,
	        'gudang' => $gudang,
	        'tanggal_awal' => $tanggal_awal,
	        'tanggal_akhir' => $tanggal_akhir
        ));
    }

	public function export($id) {
		$gudang = $this->input->get('gudang');
		$tanggal_awal = $this->input->get('tanggal_awal');
		$tanggal_akhir = $this->input->get('tanggal_akhir');
		$spreadsheet = IOFactory::load('public/inventory/stok/stok.xlsx');
		$worksheet = $spreadsheet->getActiveSheet();

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

		$stok_awal = $this->barang_stok_m->stok_awal($tanggal_awal)
			->where('barang_stok.id_barang', $id)
			->first_or_fail();
		$barang_stok_mutasi = $this->barang_stok_mutasi_m->view('barang_stok_mutasi')
			->where('barang_stok_mutasi.tanggal_mutasi >= ', date('Y-m-d',strtotime($tanggal_awal)))
			->where('barang_stok_mutasi.tanggal_mutasi <= ', date('Y-m-d',strtotime($tanggal_akhir)))
			->where('barang_stok_mutasi.id_gudang', $gudang)
			->where('barang_stok_mutasi.id_barang', $id)
			->get();

		$worksheet->getCell('B3')->setValue($tanggal_awal);
		$worksheet->getCell('B4')->setValue($tanggal_akhir);
		$worksheet->getCell('B5')->setValue($stok_awal->gudang);
		$worksheet->getCell('B6')->setValue($stok_awal->kode_barang);
		$worksheet->getCell('B7')->setValue($stok_awal->nama_barang);
		$worksheet->getCell('B8')->setValue($stok_awal->satuan);

		$worksheet->getCell('A11')->setValue('Stok Awal');
		$worksheet->getCell('I11')->setValue($stok_awal->stok_awal);
		$spreadsheet->getActiveSheet()->mergeCells('A11'.':H11');

		$total_mutasi = $stok_awal->stok_awal;
		$row = 12;
		foreach ($barang_stok_mutasi as $mutasi) {
			$total_mutasi += $mutasi->jumlah;
			$worksheet->getCell('A'.$row)->setValue(date('d-m-Y',strtotime($mutasi->tanggal_mutasi)));
			$worksheet->getCell('B'.$row)->setValue($mutasi->no_nota);
			$worksheet->getCell('C'.$row)->setValue($mutasi->batch_number);
			$worksheet->getCell('D'.$row)->setValue(($mutasi->expired ? date('d-m-Y',strtotime($mutasi->expired)) : ''));
			$worksheet->getCell('E'.$row)->setValue($mutasi->jenis_mutasi);
			$worksheet->getCell('F'.$row)->setValue($mutasi->keterangan);
			$worksheet->getCell('G'.$row)->setValue(($mutasi->jumlah > 0 ? $mutasi->jumlah : ''));
			$worksheet->getCell('H'.$row)->setValue(($mutasi->jumlah < 0 ? ($mutasi->jumlah * -1) : ''));
			$worksheet->getCell('I'.$row)->setValue($total_mutasi);
			$worksheet->getCell('J'.$row)->setValue($mutasi->created_by);

			for($i=0;$i<10;$i++){
				$spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
			}
			$row++;
		}

		$worksheet->getCell('A'.$row)->setValue('Stok Akhir');
		$worksheet->getCell('I'.$row)->setValue($total_mutasi);
		$spreadsheet->getActiveSheet()->mergeCells('A'.$row.':H'.$row);
		for($i=0;$i<10;$i++){
			$spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
		}

		foreach ($worksheet->getColumnDimensions() as $colDim) {
			$colDim->setAutoSize(true);
		}

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		header('Content-Disposition: attachment; filename="stok.xlsx"');
		$writer->save("php://output");
	}
}