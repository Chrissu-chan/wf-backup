<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Pelanggan extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pelanggan_m');
        $this->load->model('kota_m');
        $this->load->model('jenis_identitas_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Pelanggan";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->pelanggan_m)
                ->view('pelanggan')
                ->edit_column('jenis_kelamin', function ($model) {
                    return $this->pelanggan_m->enum('jenis_kelamin', $model->jenis_kelamin);
                })
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('pelanggan/pelanggan/index', $data);
    }

    public function view($id)
    {
        $model = $this->pelanggan_m->view('pelanggan')->find_or_fail($id);
        $this->load->view('pelanggan/pelanggan/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('pelanggan/pelanggan/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_cabang' => 'required',
            'nama' => 'required',
            'id_jenis_identitas' => 'required',
            'no_identitas' => 'required'
        ));
        $result = $this->pelanggan_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('pelanggan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pelanggan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->pelanggan_m->find_or_fail($id);
        $this->load->view('pelanggan/pelanggan/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_cabang' => 'required',
            'nama' => 'required',
            'id_jenis_identitas' => 'required',
            'no_identitas' => 'required'
        ));
        $result = $this->pelanggan_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('pelanggan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('pelanggan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->pelanggan_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('pelanggan')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('pelanggan')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_json()
    {
        $id = $this->input->get('id');
        $result = $this->pelanggan_m->where('id', $id)->first();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import()
    {
        $this->load->view('pelanggan/pelanggan/import');
    }

    public function import_store()
    {
        $errors = array();
        $success_count = 0;
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

        $format = array(
            'A' => 'No',
            'B' => 'Nama',
            'C' => 'Jenis Identitas',
            'D' => 'No Identitas',
            'E' => 'Jenis Kelamin',
            'F' => 'Telepon',
            'G' => 'HP',
            'H' => 'Kota',
            'I' => 'Alamat',
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 6; $i <= count($worksheet); $i++) {
            $no = $worksheet[$i]['A'];
            $nama = trim($worksheet[$i]['B']);
            $jenis_identitas = trim($worksheet[$i]['C']);
            $no_identitas = trim($worksheet[$i]['D']);
            $jenis_kelamin = trim($worksheet[$i]['E']);
            $telepon = trim($worksheet[$i]['F']);
            $hp = trim($worksheet[$i]['G']);
            $kota = trim($worksheet[$i]['H']);
            $alamat = trim($worksheet[$i]['I']);

            $data = array(
                'nama' => $nama,
                'id_cabang' => $this->session->userdata('cabang')->id,
                'jenis_identitas' => $jenis_identitas,
                'no_identitas' => $no_identitas,
                'jenis_kelamin' => $jenis_kelamin,
                'telepon' => $telepon,
                'hp' => $hp,
                'kota' => $kota,
                'alamat' => $alamat
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'nama' => 'required',
                'jenis_identitas' => 'required',
                'no_identitas' => 'required',
                'jenis_kelamin' => 'required'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $r_jenis_kelamin = in_array($jenis_kelamin, $this->pelanggan_m->enum('jenis_kelamin'));
            if (!$r_jenis_kelamin) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('jenis_kelamin_tidak_terdaftar')));
                continue;
            }
            $data['jenis_kelamin'] = array_search($jenis_kelamin, $this->pelanggan_m->enum('jenis_kelamin'));

            if ($kota) {
                $r_kota = $this->kota_m->where('LOWER(kota)', strtolower($kota))->first();
                if (!$r_kota) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('kota_tidak_ditemukan')));
                    continue;
                }
                $data['id_kota'] = $r_kota->id;
            }

            $r_jenis_identitas = $this->jenis_identitas_m->where('LOWER(jenis_identitas)', strtolower($jenis_identitas))->first();
            if (!$r_jenis_identitas) {
                $this->jenis_identitas_m->insert($data);
            }
            $data['id_jenis_identitas'] = $r_jenis_identitas->id;

            $result = $this->pelanggan_m->insert($data);

            if ($result) {
                $success_count++;
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pelanggan')))));
            }
        }
        $this->redirect->with('import_error_message', $errors)
            ->with('import_success_message', $success_count)
            ->back();
    }

    public function download_format()
    {
        $this->load->helper('download');
        $path = base_url('public/pelanggan/pelanggan/import_pelanggan.xlsx');
        $data = file_get_contents($path);
        $name = 'import_pelanggan.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $spreadsheet = IOFactory::load('public/pelanggan/pelanggan/import_pelanggan.xlsx');
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


        $rs_pelanggan = $this->pelanggan_m->view('pelanggan')->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Pelanggan');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_pelanggan as $key => $pelanggan) {
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($pelanggan->nama);
            $worksheet->getCell('C' . $row)->setValue($pelanggan->jenis_identitas);
            $worksheet->getCell('D' . $row)->setValue($pelanggan->no_identitas);
            $worksheet->getCell('E' . $row)->setValue($this->pelanggan_m->enum('jenis_kelamin', $pelanggan->jenis_kelamin));
            $worksheet->getCell('F' . $row)->setValue($pelanggan->telepon);
            $worksheet->getCell('G' . $row)->setValue($pelanggan->hp);
            $worksheet->getCell('H' . $row)->setValue($pelanggan->kota);
            $worksheet->getCell('I' . $row)->setValue($pelanggan->alamat);
            for ($i = 0; $i < 9; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="pelanggan.xlsx"');
        $writer->save("php://output");
    }
}
