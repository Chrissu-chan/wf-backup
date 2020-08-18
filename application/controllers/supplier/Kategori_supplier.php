<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Kategori_supplier extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kategori_supplier_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $title = "Kategori Supplier";
        $data = $this->kategori_supplier_m->get();
        $model = tree($data, 'id', 'parent_id', 0);
        $this->load->view('supplier/kategori_supplier/index', array('model' => $model, 'title' => $title));
    }

    public function view($id)
    {
        $model = $this->kategori_supplier_m->find_or_fail($id);
        $this->load->view('supplier/kategori_supplier/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $parent_id = $this->input->get('parent_id');
        $this->load->view('supplier/kategori_supplier/create', array(
            'parent_id' => $parent_id
        ));
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_supplier' => 'required|is_unique[kategori_supplier.kategori_supplier]'
        ));
        $result = $this->kategori_supplier_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('kategori_supplier')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_supplier')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->kategori_supplier_m->find_or_fail($id);
        $this->load->view('supplier/kategori_supplier/edit', array(
            'model' => $model,
            'parent_id' => $model->parent_id
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kategori_supplier' => 'required|is_unique[kategori_supplier.kategori_supplier.' . $id . ']'
        ));
        if ($id != $post['parent_id'] || $post['parent_id'] == 0) {
            $result = $this->kategori_supplier_m->update($id, $post);
            if ($result) {
                $response = array(
                    'success' => true,
                    'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('kategori_supplier')))
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('kategori_supplier')))
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('parent_salah')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $get = $this->kategori_supplier_m->where('parent_id', $id)->get();
        if (!$get) {
            $result = $this->kategori_supplier_m->delete($id);
            if ($result) {
                $response = array(
                    'success' => true,
                    'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('kategori_supplier')))
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('kategori_supplier')))
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('kategori_memiliki_turunan')))
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import()
    {
        $this->load->view('supplier/kategori_supplier/import');
    }

    public function import_store()
    {
        $errors = array();
        $success_count = 0;
        $config['upload_path'] = './public/supplier/kategori_supplier/';
        $config['allowed_types'] = 'xlsx|xls';
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
            'B' => 'Kategori Supplier'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 6; $i <= $numRows; $i++) {
            $no = trim($worksheet[$i]["A"]);
            $kategori_supplier = trim($worksheet[$i]["B"]);
            $parent = trim($worksheet[$i]["C"]);

            $data = array(
                'kategori_supplier' => $kategori_supplier,
                'parent_id' => 0
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kategori_supplier' => 'required|is_unique[kategori_supplier.kategori_supplier]',
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            if ($parent) {
                $r_induk = $this->kategori_supplier_m->where('LOWER(kategori_supplier)', strtolower($parent))->first();
                if (!$r_induk) {
                    $data['kategori_supplier'] = $parent;
                    $r_induk = $this->kategori_supplier_m->insert($data);
                }
                $data['parent_id'] = $r_induk->id;
            }

            $r_kategori_supplier = $this->kategori_supplier_m->where('LOWER(kategori_supplier)', strtolower($kategori_supplier))->first();
            if (!$r_kategori_supplier) {
                $result = $this->kategori_supplier_m->insert($data);
                if ($result) {
                    $success_count++;
                } else {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kategori_supplier')))));
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
        $path = base_url('public/supplier/kategori_supplier/import_kategori_supplier.xlsx');
        $data = file_get_contents($path);
        $name = 'import_kategori_supplier.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $spreadsheet = IOFactory::load('public/supplier/kategori_supplier/import_kategori_supplier.xlsx');

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

        $rs_kategori_supplier = $this->kategori_supplier_m->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Kategori Supplier');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_kategori_supplier as $key => $kategori_supplier) {
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($kategori_supplier->kategori_supplier);
            $worksheet->getCell('C' . $row)->setValue('');
            for ($i = 0; $i < 3; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="kategori-supplier.xlsx"');
        $writer->save("php://output");
    }
}
