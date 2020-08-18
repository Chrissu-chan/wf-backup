<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Supplier extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_m');
        $this->load->model('supplier_cabang_m');
        $this->load->model('jenis_supplier_m');
        $this->load->model('kategori_supplier_m');
        $this->load->model('kota_m');
        $this->load->model('bank_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Supplier";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->supplier_m)
                ->view('supplier')
                ->edit_column('jenis_kelamin', function ($model) {
                    return $this->supplier_m->enum('jenis_kelamin', $model->jenis_kelamin);
                })
                ->filter(function ($model) {
                    if ($kategori = $this->input->get('kategori')) {
                        $model->where('supplier.id_kategori_supplier', $kategori);
                    }
                    if ($jenis = $this->input->get('jenis')) {
                        $model->where('supplier.id_jenis_supplier', $jenis);
                    }
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('supplier/supplier/index', $data);
    }

    public function view($id)
    {
        $model = $this->supplier_m->select('supplier.*')
            ->view('supplier')
            ->view('bank')
            ->view('kota')
            ->find_or_fail($id);
        $this->load->view('supplier/supplier/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('supplier/supplier/create');
    }

    public function store()
    {
        $this->transaction->start();
        $this->form_validation->validate(array(
            'id_kategori_supplier' => 'required',
            'id_jenis_supplier' => 'required',
            'supplier' => 'required',
            'nama' => 'required'
        ));
        $post = $this->input->post();
        $result = $this->supplier_m->insert($post);
        $record_supplier_cabang = array();
        if (isset($post['supplier_cabang'])) {
            foreach ($post['supplier_cabang'] as $id_cabang) {
                $record_supplier_cabang[] = array(
                    'id_supplier' => $result->id,
                    'id_cabang' => $id_cabang
                );
            }
        } else {
            $record_supplier_cabang[] = array(
                'id_supplier' => $result->id,
                'id_cabang' => 0
            );
        }
        if ($record_supplier_cabang) {
            $this->supplier_cabang_m->insert_batch($record_supplier_cabang);
        }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('supplier')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('supplier')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->supplier_m->find_or_fail($id);
        foreach ($this->supplier_cabang_m->where('id_supplier', $id)->get() as $supplier_cabang) {
            $model->supplier_cabang[] = $supplier_cabang->id_cabang;
        }

        $this->load->view('supplier/supplier/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $this->form_validation->validate(array(
            'id_kategori_supplier' => 'required',
            'id_jenis_supplier' => 'required',
            'supplier' => 'required',
            'nama' => 'required'
        ));
        $this->transaction->start();
        $post = $this->input->post();
        $this->supplier_m->update($id, $post);
        $this->supplier_cabang_m->where('id_supplier', $id)->delete();
        $record_supplier_cabang = array();
        if (isset($post['supplier_cabang'])) {
            foreach ($post['supplier_cabang'] as $id_cabang) {
                $record_supplier_cabang[] = array(
                    'id_supplier' => $id,
                    'id_cabang' => $id_cabang
                );
            }
        } else {
            $record_supplier_cabang[] = array(
                'id_supplier' => $id,
                'id_cabang' => 0
            );
        }
        if ($record_supplier_cabang) {
            $this->supplier_cabang_m->insert_batch($record_supplier_cabang);
        }
        if ($this->transaction->complete()) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('supplier')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('supplier')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->supplier_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('supplier')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('supplier')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json()
    {
        $key = $this->input->get('q');
        $result = $this->supplier_m->view('supplier')
            ->like('supplier', $key)
            ->scope('auth')
            ->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import()
    {
        $this->load->view('supplier/supplier/import');
    }

    public function import_store()
    {
        $errors = array();
        $success_count = 0;
        $config['upload_path'] = './public/supplier/supplier/';
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

        $format = array(
            'A' => 'No',
            'B' => 'Kategori Supplier',
            'C' => 'Jenis Supplier',
            'D' => 'Supplier',
            'E' => 'Nama',
            'F' => 'Jenis Kelamin',
            'G' => 'Telepon',
            'H' => 'Kota',
            'I' => 'Alamat',
            'J' => 'Bank',
            'K' => 'No Rekening',
            'L' => 'Nama Rekening'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 6; $i <= count($worksheet); $i++) {
            $no = $worksheet[$i]['A'];
            $kategori_supplier = trim($worksheet[$i]['B']);
            $jenis_supplier = trim($worksheet[$i]['C']);
            $supplier = trim($worksheet[$i]['D']);
            $cabang = trim($worksheet[$i]['E']);
            $nama = trim($worksheet[$i]['F']);
            $jenis_kelamin = trim($worksheet[$i]['G']);
            $telepon = trim($worksheet[$i]['H']);
            $kota = trim($worksheet[$i]['I']);
            $alamat = trim($worksheet[$i]['J']);
            $bank = trim($worksheet[$i]['K']);
            $no_rekening = trim($worksheet[$i]['L']);
            $nama_rekening = trim($worksheet[$i]['M']);

            $data = array(
                'kategori_supplier' => $kategori_supplier,
                'jenis_supplier' => $jenis_supplier,
                'supplier' => $supplier,
                'cabang' => $cabang,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'telepon' => $telepon,
                'kota' => $kota,
                'alamat' => $alamat,
                'bank' => $bank,
                'no_rekening' => $no_rekening,
                'nama_rekening' => $nama_rekening
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kategori_supplier' => 'required',
                'jenis_supplier' => 'required',
                'supplier' => 'required|is_unique[supplier.supplier]',
                'cabang' => 'required',
                'nama' => 'required|is_unique[supplier.nama]',
                'jenis_kelamin' => 'required'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $supplier_cabang = explode(',', $cabang);
            $r_supplier_cabang = array();
            foreach ($supplier_cabang as $cabang) {
                $r_cabang = $this->cabang_m->scope('auth')->where('LOWER(nama)', strtolower(trim($cabang)))->first();
                if (!$r_cabang) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('cabang_tidak_terdaftar')));
                    continue;
                }
                $r_supplier_cabang[] = $r_cabang->id;
            }

            $r_jenis_kelamin = in_array($jenis_kelamin, $this->supplier_m->enum('jenis_kelamin'));
            if (!$r_jenis_kelamin) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('jenis_kelamin_tidak_terdaftar')));
                continue;
            }
            $data['jenis_kelamin'] = array_search($jenis_kelamin, $this->supplier_m->enum('jenis_kelamin'));

            if ($kota) {
                $r_kota = $this->kota_m->where('LOWER(kota)', strtolower($kota))->first();
                if (!$r_kota) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('kota_tidak_terdaftar')));
                    continue;
                }
                $data['id_kota'] = $r_kota->id;
            }

            $r_kategori_supplier = $this->kategori_supplier_m->where('LOWER(kategori_supplier)', strtolower($kategori_supplier))->first();
            if (!$r_kategori_supplier) {
                $r_kategori_supplier = $this->kategori_supplier_m->insert(array(
                    'kategori_supplier' => $kategori_supplier,
                    'parent_id' => 0
                ));
            }
            $data['id_kategori_supplier'] = $r_kategori_supplier->id;

            $r_jenis_supplier = $this->jenis_supplier_m->where('LOWER(jenis_supplier)', strtolower($jenis_supplier))->first();
            if (!$r_jenis_supplier) {
                $r_jenis_supplier = $this->jenis_supplier_m->insert($data);
            }
            $data['id_jenis_supplier'] = $r_jenis_supplier->id;

            if ($bank) {
                $r_bank = $this->bank_m->where('LOWER(bank)', strtolower($bank))->first();
                if (!$r_bank) {
                    $r_bank = $this->bank_m->insert($data);
                }
                $data['id_bank'] = $r_bank->id;
            }

            $result = $this->supplier_m->insert($data);
            if ($result) {
                $record_supplier_cabang = array();
                foreach ($r_supplier_cabang as $id_cabang) {
                    $record_supplier_cabang[] = array(
                        'id_supplier' => $result->id,
                        'id_cabang' => $id_cabang
                    );
                }
                if ($record_supplier_cabang) {
                    $this->supplier_cabang_m->insert_batch($record_supplier_cabang);
                }
                $success_count++;
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('barang')))));
            }
        }
        $this->redirect->with('import_error_message', $errors)
            ->with('import_success_message', $success_count)
            ->back();
    }

    public function download_format()
    {
        $this->load->helper('download');
        $path = base_url('public/supplier/supplier/import_supplier.xlsx');
        $data = file_get_contents($path);
        $name = 'import_supplier.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $spreadsheet = IOFactory::load('public/supplier/supplier/import_supplier.xlsx');
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


        $rs_supplier = $this->supplier_m->view('supplier')->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Supplier');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_supplier as $key => $supplier) {
            $rs_supplier_cabang = $this->cabang_m->scope('auth')->where('id_supplier', $supplier->id)->get();
            $supplier_cabang = array();
            if ($rs_supplier_cabang) {
                foreach ($rs_supplier_cabang as $r_supplier_cabang) {
                    $supplier_cabang[] = $r_supplier_cabang->nama;
                }
            }
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($supplier->kategori_supplier);
            $worksheet->getCell('C' . $row)->setValue($supplier->jenis_supplier);
            $worksheet->getCell('D' . $row)->setValue($supplier->supplier);
            $worksheet->getCell('E' . $row)->setValue(implode(', ', $supplier_cabang));
            $worksheet->getCell('F' . $row)->setValue($supplier->nama);
            $worksheet->getCell('G' . $row)->setValue($this->supplier_m->enum('jenis_kelamin', $supplier->jenis_kelamin));
            $worksheet->getCell('H' . $row)->setValue($supplier->telepon);
            $worksheet->getCell('I' . $row)->setValue($supplier->kota);
            $worksheet->getCell('J' . $row)->setValue($supplier->alamat);
            $worksheet->getCell('K' . $row)->setValue($supplier->bank);
            $worksheet->getCell('L' . $row)->setValue($supplier->no_rekening);
            $worksheet->getCell('M' . $row)->setValue($supplier->nama_rekening);
            for ($i = 0; $i < 13; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="supplier.xlsx"');
        $writer->save("php://output");
    }
}
