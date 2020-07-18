<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Kategori_barang extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('kategori_barang_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->kategori_barang_m)
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('master/kategori_barang/index');
    }

    public function view($id) {
        $model = $this->kategori_barang_m->find_or_fail($id);
        $this->load->view('master/kategori_barang/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/kategori_barang/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_barang' => 'required|is_unique[kategori_barang.kategori_barang]'
        ));
        $result = $this->kategori_barang_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->kategori_barang_m->find_or_fail($id);
        $this->load->view('master/kategori_barang/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_barang' => 'required|is_unique[kategori_barang.kategori_barang.'.$id.']'
        ));
        $result = $this->kategori_barang_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->kategori_barang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('kategori_barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import() {
        $this->load->view('master/kategori_barang/import');
    }

    public function import_store() {
        $errors = array();
        $success_count = 0;
        $config['upload_path'] = './'.$this->config->item('import_upload_path');
        $config['allowed_types'] = $this->config->item('import_allowed_file_types');
        $this->load->library('upload', $config);

        if (!$this->upload->has('file')) {
            $this->redirect->with('error_message', $this->localization->lang('upload_required'))->back();
        }
        if(!$this->upload->do_upload('file')) {
            $this->redirect->with('error_message', $this->upload->display_errors())->back();
        }
        $file_name = $this->upload->data('file_name');
        try {
            $inputFileName = $config['upload_path'].'/'.$file_name;
            $spreadsheet = IOFactory::load($inputFileName);
        } catch(Exception $e) {
            $this->redirect->with('error_message', $e->getMessage())->back();
        }

        $worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numRows = count($worksheet);

        $format = array(
            'A' => 'No',
            'B' => 'Kategori Barang'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for($i = 6; $i<=$numRows; $i++) {
            $no = trim($worksheet[$i]["A"]);
            $kategori_barang = trim($worksheet[$i]["B"]);

            $data = array(
                'kategori_barang' => $kategori_barang
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kategori_barang' => 'required|is_unique[kategori_barang.kategori_barang]',
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $r_kategori_barang = $this->kategori_barang_m->where('LOWER(kategori_barang)', strtolower($kategori_barang))->first();
            if(!$r_kategori_barang) {
                $result = $this->kategori_barang_m->insert($data);
                if($result) {
                    $success_count++;
                } else {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_barang')))));
                }
            }
        }
        $this->redirect->with('import_error_message', $errors)
        ->with('import_success_message', $success_count)
        ->back();
    }

    public function download_format() {
        $this->load->helper('download');
        $path = base_url('public/master/barang/import_kategori_barang.xlsx');
        $data = file_get_contents($path);
        $name = 'import_kategori_barang.xlsx';
        return force_download($name, $data);
    }

    public function export() {
        $spreadsheet = IOFactory::load('public/master/barang/import_kategori_barang.xlsx');

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

        $rs_kategori_barang = $this->kategori_barang_m->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Kategori Barang');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_kategori_barang as $key => $kategori_barang) {
            $worksheet->getCell('A'.$row)->setValue($no);
            $worksheet->getCell('B'.$row)->setValue($kategori_barang->kategori_barang);
            for($i=0;$i<2;$i++){
                $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
            }
            $no++; $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="kategori_barang.xlsx"');
        $writer->save("php://output");
    }

}