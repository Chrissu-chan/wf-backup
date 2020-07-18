<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Pasien extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('pasien_m');
        $this->load->model('jenis_identitas_m');
        $this->load->model('kota_m');
        $this->load->model('penyakit_m');
        $this->load->model('pasien_alergi_m');
        $this->load->model('pasien_penyakit_m');
        $this->load->model('pasien_penyakit_biologis_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->pasien_m)
            ->view('pasien')
            ->edit_column('jenis_kelamin', function($model) {
                return $this->pasien_m->enum('jenis_kelamin', $model->jenis_kelamin);
            })
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('rekam_medis/pasien/index');
    }

    public function view($id) {
        $model = $this->pasien_m->view('pasien')->find_or_fail($id);
        $rs_riwayat_alergi = $this->pasien_alergi_m->view('alergi')->where('id_pasien', $id)->get();
        foreach ($rs_riwayat_alergi as $r_alergi) {
            $model->alergi[] = $r_alergi->alergi;
        }
        $rs_riwayat_penyakit = $this->pasien_penyakit_m->view('penyakit')->where('id_pasien', $id)->get();
        foreach ($rs_riwayat_penyakit as $r_penyakit) {
            $model->penyakit[] = $r_penyakit->penyakit;
        }
        $rs_riwayat_penyakit_biologis = $this->pasien_penyakit_biologis_m->view('penyakit_biologis')->where('id_pasien', $id)->get();
        foreach ($rs_riwayat_penyakit_biologis as $r_penyakit_biologis) {
            $model->penyakit_biologis[] = $r_penyakit_biologis->penyakit_biologis;
        }
        $this->load->view('rekam_medis/pasien/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('rekam_medis/pasien/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_cabang' => 'required',
            'nama' => 'required',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required|is_unique[pasien.no_identitas]',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'status_pernikahan' => 'required'
        ));
        $result = $this->pasien_m->insert($post);

        $rs_riwayat_alergi = array();
        foreach ($post['id_alergi'] as $riwayat_alergi) {
            $rs_riwayat_alergi[] = array(
                'id_pasien' => $result->id,
                'id_alergi' => $riwayat_alergi
            );
        }
        $this->pasien_alergi_m->insert_batch($rs_riwayat_alergi);

        $rs_riwayat_penyakit = array();
        foreach ($post['id_penyakit'] as $riwayat_penyakit) {
            $rs_riwayat_penyakit[] = array(
                'id_pasien' => $result->id,
                'id_penyakit' => $riwayat_penyakit
            );
        }
        $this->pasien_penyakit_m->insert_batch($rs_riwayat_penyakit);

        $rs_riwayat_penyakit_biologis = array();
        foreach ($post['id_penyakit_biologis'] as $riwayat_penyakit_biologis) {
            $rs_riwayat_penyakit_biologis[] = array(
                'id_pasien' => $result->id,
                'id_penyakit_biologis' => $riwayat_penyakit_biologis
            );
        }
        $this->pasien_penyakit_biologis_m->insert_batch($rs_riwayat_penyakit_biologis);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('pasien')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pasien')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->pasien_m->find_or_fail($id);
        $this->load->view('rekam_medis/pasien/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'id_cabang' => 'required',
            'nama' => 'required',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required|is_unique[pasien.no_identitas.'.$id.']',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'status_pernikahan' => 'required'
        ));

        $result = $this->pasien_m->update($id, $post);

        $this->pasien_alergi_m->where('id_pasien', $id)->delete();
        $this->pasien_penyakit_m->where('id_pasien', $id)->delete();
        $this->pasien_penyakit_biologis_m->where('id_pasien', $id)->delete();
        $rs_riwayat_alergi = array();
        foreach ($post['id_alergi'] as $riwayat_alergi) {
            $rs_riwayat_alergi[] = array(
                'id_pasien' => $id,
                'id_alergi' => $riwayat_alergi
            );
        }
        $this->pasien_alergi_m->insert_batch($rs_riwayat_alergi);

        $rs_riwayat_penyakit = array();
        foreach ($post['id_penyakit'] as $riwayat_penyakit) {
            $rs_riwayat_penyakit[] = array(
                'id_pasien' => $id,
                'id_penyakit' => $riwayat_penyakit
            );
        }
        $this->pasien_penyakit_m->insert_batch($rs_riwayat_penyakit);

        $rs_riwayat_penyakit_biologis = array();
        foreach ($post['id_penyakit_biologis'] as $riwayat_penyakit_biologis) {
            $rs_riwayat_penyakit_biologis[] = array(
                'id_pasien' => $id,
                'id_penyakit_biologis' => $riwayat_penyakit_biologis
            );
        }
        $this->pasien_penyakit_biologis_m->insert_batch($rs_riwayat_penyakit_biologis);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('pasien')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('pasien')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $this->pasien_alergi_m->where('id_pasien', $id)->delete();
        $this->pasien_penyakit_m->where('id_pasien', $id)->delete();
        $this->pasien_penyakit_biologis_m->where('id_pasien', $id)->delete();
        $result = $this->pasien_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('pasien')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('pasien')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import() {
        $this->load->view('rekam_medis/pasien/import');
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

        $format = array(
            'A' => 'No',
            'B' => 'Nama',
            'C' => 'Jenis Identitas',
            'D' => 'No Identitas',
            'E' => 'Jenis Kelamin',
            'F' => 'Tempat Lahir',
            'G' => 'Tanggal Lahir',
            'H' => 'Agama',
            'I' => 'Pendidikan',
            'J' => 'Telepon',
            'K' => 'HP',
            'L' => 'Status Pernikahan',
            'M' => 'Pekerjaan',
            'N' => 'Kota',
            'O' => 'Alamat',
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for($i = 6; $i<=count($worksheet); $i++) {
            $no = $worksheet[$i]['A'];
            $nama = trim($worksheet[$i]['B']);
            $jenis_identitas = trim($worksheet[$i]['C']);
            $no_identitas = trim($worksheet[$i]['D']);
            $jenis_kelamin = trim($worksheet[$i]['E']);
            $tempat_lahir = trim($worksheet[$i]['F']);
            $tanggal_lahir = trim($worksheet[$i]['G']);
            $agama = trim($worksheet[$i]['H']);
            $pendidikan = trim($worksheet[$i]['I']);
            $telepon = trim($worksheet[$i]['J']);
            $hp = trim($worksheet[$i]['K']);
            $status_pernikahan = trim($worksheet[$i]['L']);
            $pekerjaan = trim($worksheet[$i]['M']);
            $kota = trim($worksheet[$i]['N']);
            $alamat = trim($worksheet[$i]['O']);
            $riwayat_alergi = explode("; ", trim($worksheet[$i]['P']));
            $riwayat_penyakit = explode("; ", trim($worksheet[$i]['Q']));
            $riwayat_penyakit_biologis = explode("; ", trim($worksheet[$i]['R']));

            $data = array(
                'nama' => $nama,
                'id_cabang' => $this->session->userdata('cabang')->id,
                'jenis_identitas' => $jenis_identitas,
                'no_identitas' => $no_identitas,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'telepon' => $telepon,
                'hp' => $hp,
                'status_pernikahan' => $status_pernikahan,
                'pekerjaan' => $pekerjaan,
                'kota' => $kota,
                'alamat' => $alamat
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'nama' => 'required',
                'jenis_identitas' => 'required',
                'no_identitas' => 'required|is_unique[pasien.no_identitas]',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama' => 'required',
                'pendidikan' => 'required',
                'status_pernikahan' => 'required'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $r_jenis_kelamin = in_array($jenis_kelamin, $this->pasien_m->enum('jenis_kelamin'));
            if(!$r_jenis_kelamin) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('jenis_kelamin_tidak_terdaftar')));
                continue;
            }
            $data['jenis_kelamin'] = array_search($jenis_kelamin, $this->pasien_m->enum('jenis_kelamin'));

            $r_agama = in_array($agama, $this->pasien_m->enum('agama'));
            if(!$r_agama) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('agama_tidak_terdaftar')));
                continue;
            }
            $data['agama'] = array_search($agama, $this->pasien_m->enum('agama'));

            $r_pendidikan = in_array($pendidikan, $this->pasien_m->enum('pendidikan'));
            if(!$r_pendidikan) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('pendidikan_tidak_terdaftar')));
                continue;
            }
            $data['pendidikan'] = array_search($pendidikan, $this->pasien_m->enum('pendidikan'));

            $r_status_pernikahan = in_array($status_pernikahan, $this->pasien_m->enum('status_pernikahan'));
            if(!$r_status_pernikahan) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('status_pernikahan_tidak_terdaftar')));
                continue;
            }
            $data['status_pernikahan'] = array_search($status_pernikahan, $this->pasien_m->enum('status_pernikahan'));

            if($tempat_lahir) {
                $r_tempat_lahir = $this->kota_m->where('LOWER(kota)', strtolower($tempat_lahir))->first();
                if(!$r_tempat_lahir) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('tempat_lahir_tidak_ditemukan')))));
                    continue;
                }
                $data['tempat_lahir'] = $r_tempat_lahir->id;
            }

            if($kota) {
                $r_kota = $this->kota_m->where('LOWER(kota)', strtolower($kota))->first();
                if(!$r_kota) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('kota_tidak_ditemukan')))));
                    continue;
                }
                $data['id_kota'] = $r_kota->id;
            }

            $r_jenis_identitas = $this->jenis_identitas_m->where('LOWER(jenis_identitas)', strtolower($jenis_identitas))->first();
            if(!$r_jenis_identitas) {
                $this->jenis_identitas_m->insert($jenis_identitas);
            }
            $data['id_jenis_identitas'] = $r_jenis_identitas->id;

            $result = $this->pasien_m->insert($data);

            $rs_riwayat_alergi = array();
            foreach ($riwayat_alergi as $val) {
                $alergi = $this->penyakit_m->where('LOWER(penyakit)', strtolower($val))->first();
                if(!$alergi) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('riwayat_alergi_tidak_terdaftar')));
                    continue;
                }
                $rs_riwayat_alergi[] = array(
                    'id_pasien' => $result->id,
                    'id_alergi' => $alergi->id
                );
            }
            if($rs_riwayat_alergi) {
                $this->pasien_alergi_m->insert_batch($rs_riwayat_alergi);
            }

            $rs_riwayat_penyakit = array();
            foreach ($riwayat_penyakit as $val) {
                $penyakit = $this->penyakit_m->where('LOWER(penyakit)', strtolower($val))->first();
                if(!$penyakit) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('riwayat_penyakit_tidak_terdaftar')));
                    continue;
                } 
                $rs_riwayat_penyakit[] = array(
                    'id_pasien' => $result->id,
                    'id_penyakit' => $penyakit->id
                );
            }
            if($rs_riwayat_penyakit) {
                $this->pasien_penyakit_m->insert_batch($rs_riwayat_penyakit);
            }

            $rs_riwayat_penyakit_biologis = array();
            foreach ($riwayat_penyakit_biologis as $val) {
                $penyakit_biologis = $this->penyakit_m->where('LOWER(penyakit)', strtolower($val))->first();
                if(!$penyakit_biologis) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('riwayat_penyakit_biologis_tidak_terdaftar')));
                    continue;
                }
                $rs_riwayat_penyakit_biologis[] = array(
                    'id_pasien' => $result->id,
                    'id_penyakit_biologis' => $penyakit_biologis->id
                );
            }
            if($rs_riwayat_penyakit_biologis) {
                $this->pasien_penyakit_biologis_m->insert_batch($rs_riwayat_penyakit_biologis);
            }

            if ($result) {
                $success_count++;
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('pasien')))));
            }
        }
        $this->redirect->with('import_error_message', $errors)
        ->with('import_success_message', $success_count)
        ->back();
    }

    public function download_format() {
        $this->load->helper('download');
        $path = base_url('public/rekam_medis/pasien/import_pasien.xlsx');
        $data = file_get_contents($path);
        $name = 'import_pasien.xlsx';
        return force_download($name, $data);
    }

    public function export() {
        $spreadsheet = IOFactory::load('public/rekam_medis/pasien/import_pasien.xlsx');
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


        $rs_pasien = $this->pasien_m->view('pasien')->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Pasien');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_pasien as $key => $pasien) {
            $riwayat_alergi = array();
            $rs_riwayat_alergi = $this->pasien_alergi_m->view('alergi')->where('id_pasien', $pasien->id)->get();
            foreach ($rs_riwayat_alergi as $r_riwayat_alergi) {
                $riwayat_alergi[] = $r_riwayat_alergi->alergi;
            }
            $txt_riwayat_alergi = implode("; ", $riwayat_alergi);

            $riwayat_penyakit = array();
            $rs_riwayat_penyakit = $this->pasien_penyakit_m->view('penyakit')->where('id_pasien', $pasien->id)->get();
            foreach ($rs_riwayat_penyakit as $r_riwayat_penyakit) {
                $riwayat_penyakit[] = $r_riwayat_penyakit->penyakit;
            }
            $txt_riwayat_penyakit = implode("; ", $riwayat_penyakit);            

            $riwayat_penyakit_biologis = array();
            $rs_riwayat_penyakit_biologis = $this->pasien_penyakit_biologis_m->view('penyakit_biologis')->where('id_pasien', $pasien->id)->get();
            foreach ($rs_riwayat_penyakit_biologis as $r_riwayat_penyakit_biologis) {
                $riwayat_penyakit_biologis[] = $r_riwayat_penyakit_biologis->penyakit_biologis;
            }
            $txt_riwayat_penyakit_biologis = implode("; ", $riwayat_penyakit_biologis);

            $worksheet->getCell('A'.$row)->setValue($no);
            $worksheet->getCell('B'.$row)->setValue($pasien->nama);
            $worksheet->getCell('C'.$row)->setValue($pasien->jenis_identitas);
            $worksheet->getCell('D'.$row)->setValue($pasien->no_identitas);
            $worksheet->getCell('E'.$row)->setValue($this->pasien_m->enum('jenis_kelamin', $pasien->jenis_kelamin));
            $worksheet->getCell('F'.$row)->setValue($pasien->tempat_lahir);
            $worksheet->getCell('G'.$row)->setValue($pasien->tanggal_lahir);
            $worksheet->getCell('H'.$row)->setValue($this->pasien_m->enum('agama', $pasien->agama));
            $worksheet->getCell('I'.$row)->setValue($this->pasien_m->enum('pendidikan', $pasien->pendidikan));
            $worksheet->getCell('J'.$row)->setValue($pasien->telepon);
            $worksheet->getCell('K'.$row)->setValue($pasien->hp);
            $worksheet->getCell('L'.$row)->setValue($this->pasien_m->enum('status_pernikahan', $pasien->status_pernikahan));
            $worksheet->getCell('M'.$row)->setValue($pasien->pekerjaan);
            $worksheet->getCell('N'.$row)->setValue($pasien->kota);
            $worksheet->getCell('O'.$row)->setValue($pasien->alamat);
            $worksheet->getCell('P'.$row)->setValue($txt_riwayat_alergi);
            $worksheet->getCell('Q'.$row)->setValue($txt_riwayat_penyakit);
            $worksheet->getCell('R'.$row)->setValue($txt_riwayat_penyakit_biologis);
            for($i=0;$i<9;$i++){
                $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
            }
            $no++; $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="pasien.xlsx"');
        $writer->save("php://output");
    }

    public function find_pasien_alergi_json()
    {
        $id_pasien = $this->input->get('id_pasien');
        $result = $this->pasien_alergi_m->view('alergi')->where('id_pasien', $id_pasien)->get();
        $response = array(
            'message' => 'success',
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_pasien_penyakit_json()
    {
        $id_pasien = $this->input->get('id_pasien');
        $result = $this->pasien_penyakit_m->view('penyakit')->where('id_pasien', $id_pasien)->get();
        $response = array(
            'message' => 'success',
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_pasien_penyakit_biologis_json()
    {
        $id_pasien = $this->input->get('id_pasien');
        $result = $this->pasien_penyakit_biologis_m->view('penyakit_biologis')->where('id_pasien', $id_pasien)->get();
        $response = array(
            'message' => 'success',
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

}