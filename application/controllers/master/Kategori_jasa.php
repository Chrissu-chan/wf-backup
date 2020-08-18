<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Kategori_jasa extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kategori_jasa_m');
        $this->load->library('form_validation');
    }

    public function index()
    {

        $title = "Master Kategori Jasa";
        $data = $this->kategori_jasa_m->get();
        $model = tree($data,  'id', 'parent_id', 0);
        $this->load->view('master/kategori_jasa/index',  ['model' => $model, 'title' => $title]);
    }

    public function view($id)
    {
        $model = $this->kategori_jasa_m->find_or_fail($id);
        $this->load->view('master/kategori_jasa/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $parent_id = $this->input->get('parent_id');
        $this->load->view('master/kategori_jasa/create', array('parent_id' => $parent_id));
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_jasa' => 'required|is_unique[kategori_jasa.kategori_jasa]'
        ));
        $result = $this->kategori_jasa_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->kategori_jasa_m->find_or_fail($id);
        $this->load->view('master/kategori_jasa/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_jasa' => 'required|is_unique[kategori_jasa.kategori_jasa.' . $id . ']'
        ));
        $result = $this->kategori_jasa_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->kategori_jasa_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('kategori_jasa')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import()
    {
        $this->load->view('master/kategori_jasa/import');
    }

    public function import_store()
    {
        $errors = array();
        $config['upload_path'] = './' . $this->config->item('import_upload_path');
        $config['allowed_types'] = $this->config->item('import_allowed_file_types');
        $config['max_size'] = $this->config->item('document_max_size');
        $this->load->library('upload', $config);

        if (!$this->upload->has('file')) {
            $this->redirect->with('error_message', $this->localization->lang('upload_required'))->back();
        }
        if (!$this->upload->do_upload('file')) {
            $this->redirect->with('error_message', $this->upload->display_errors())->back();
        }
        $file_name = $this->upload->data('file_name');
        try {
            $inputFileName = $config['upload_path'] . '/' . $file_name;
            $spreadsheet = IOFactory::load($inputFileName);
        } catch (Exception $e) {
            $this->redirect->with('error_message', $e->getMessage())->back();
        }

        $worksheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $numRows = count($worksheet);

        $format = array(
            'A' => 'No',
            'B' => 'Kategori Jasa',
            'C' => 'Induk'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 6; $i <= $numRows; $i++) {
            $no = trim($worksheet[$i]["A"]);
            $kategori_jasa = trim($worksheet[$i]["B"]);
            $parent = trim($worksheet[$i]["C"]);

            $data = array(
                'kategori_jasa' => $kategori_jasa,
                'parent_id' => 0
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kategori_jasa' => 'required|is_unique[kategori_jasa.kategori_jasa]',
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            if ($parent) {
                $r_induk = $this->kategori_jasa_m->where('LOWER(kategori_jasa)', $parent)->first();
                if (!$r_induk) {
                    $data['kategori_jasa'] = $parent;
                    $r_induk = $this->kategori_jasa_m->insert($data);
                }
                $data['parent_id'] = $r_induk->id;
            }

            $r_kategori_jasa = $this->kategori_jasa_m->where('LOWER(kategori_jasa)', $kategori_jasa)->first();
            if (!$r_kategori_jasa) {
                $result = $this->kategori_jasa_m->insert($data);
                if ($result) {
                    $success_count++;
                } else {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_jasa')))));
                }
            }
        }
        $this->redirect->with('import_error_message', $errors)
            ->with('import_success_message', $success_count)
            ->back();
    }

    public function download_format()
    {
        $this->load->helper('download');
        $path = base_url('public/master/jasa/import_kategori_jasa.xlsx');
        $data = file_get_contents($path);
        $name = 'import_kategori_jasa.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $spreadsheet = IOFactory::load('public/master/jasa/import_kategori_jasa.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();

        $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $style = array(
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

        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Kategori Jasa');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        $rs_kategori_jasa = $this->kategori_jasa_m->view('kategori_jasa')->get();
        foreach ($rs_kategori_jasa as $key => $kategori_jasa) {
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($kategori_jasa->kategori_jasa);
            $worksheet->getCell('C' . $row)->setValue($kategori_jasa->induk);
            for ($i = 0; $i < 3; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="kategori_jasa.xlsx"');
        $writer->save("php://output");
    }
}
