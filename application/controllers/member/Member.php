<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Member extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->model('pelanggan_m');
        $this->load->model('jenis_identitas_m');
        $this->load->model('kota_m');
        $this->load->model('jenis_member_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->member_m)
            ->view('member')->view('pelanggan')
            ->edit_column('tanggal_expired', function($model) {
                return $this->localization->human_date($model->tanggal_expired);
            })
            ->edit_column('jenis_kelamin', function($model) {
                return $this->member_m->enum('jenis_kelamin', $model->jenis_kelamin);
            })
            ->filter(function($model) {
                if($jenis = $this->input->get('jenis')) {
                    $model->where('member.id_jenis_member', $jenis);
                }

                if($tahun_bulan = $this->input->get('tahun_bulan')) {
                    $model->where('LEFT(member.tanggal_daftar, 7) = ', $tahun_bulan);
                }

                if($status = $this->input->get('status')) {
                    if($status == 'aktif') {
                        $model->where('member.tanggal_expired > ', date('Y-m-d'));
                    } else {
                        $model->where('member.tanggal_expired < ', date('Y-m-d'));
                    }
                }
            })
            ->add_action('{view} {edit} {delete}')
            ->generate();
        }
        $this->load->view('member/member/index');
    }

    public function view($id) {
        $model = $this->member_m->view('pelanggan')->find_or_fail($id);
        $this->load->view('member/member/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('member/member/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode' => 'required|is_unique[member.kode]',
            'id_pelanggan' => 'is_unique[member.id_pelanggan]'
        ));
        $post['id_pelanggan'] = (!$post['id_pelanggan']) ? $this->pelanggan_m->insert($post)->id : $post['id_pelanggan'];
        $result = $this->member_m->insert($post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id) {
        $model = $this->member_m->view('pelanggan')->find_or_fail($id);
        $this->load->view('member/member/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode' => 'required|is_unique[member.kode.'.$id.']',
        ));
        $this->pelanggan_m->update($post['id_pelanggan'], $post);
        $result = $this->member_m->update($id, $post);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id) {
        $result = $this->member_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('member')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('member')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function import() {
        $this->load->view('member/member/import');
    }

    public function import_store() {
        $errors = array();
        $success_count = 0;
        $config['upload_path'] = './'.$this->config->item('import_upload_path');
        $config['allowed_types'] = $this->config->item('import_allowed_file_types');
        $config['max_size'] = $this->config->item('document_max_size');
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
            'B' => 'Kode',
            'C' => 'Jenis Member',
            'D' => 'Nama',
            'E' => 'Jenis Identitas',
            'F' => 'No Identitas',
            'G' => 'Jenis Kelamin',
            'H' => 'Telepon',
            'I' => 'HP',
            'J' => 'Kota',
            'K' => 'Alamat',
            'L' => 'Tanggal Daftar',
            'M' => 'Tanggal Expired'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for($i = 6; $i<=count($worksheet); $i++) {
            $no = $worksheet[$i]['A'];
            $kode = trim($worksheet[$i]['B']);
            $jenis_member = trim($worksheet[$i]['C']);
            $nama = trim($worksheet[$i]['D']);
            $jenis_identitas = trim($worksheet[$i]['E']);
            $no_identitas = trim($worksheet[$i]['F']);
            $jenis_kelamin = trim($worksheet[$i]['G']);
            $telepon = trim($worksheet[$i]['H']);
            $hp = trim($worksheet[$i]['I']);
            $kota = trim($worksheet[$i]['J']);
            $alamat = trim($worksheet[$i]['K']);
            $tanggal_daftar = trim($worksheet[$i]['L']);
            $tanggal_expired = trim($worksheet[$i]['M']);

            $data = array(
                'kode' => $kode,
                'jenis_member' => $jenis_member,
                'nama' => $nama,
                'id_cabang' => $this->session->userdata('cabang')->id,
                'jenis_identitas' => $jenis_identitas,
                'no_identitas' => $no_identitas,
                'jenis_kelamin' => $jenis_kelamin,
                'telepon' => $telepon,
                'hp' => $hp,
                'kota' => $kota,
                'alamat' => $alamat,
                'tanggal_daftar' => $tanggal_daftar,
                'tanggal_expired' => $tanggal_expired
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kode' => 'required|is_unique[member.kode]',
                'jenis_member' => 'required',
                'nama' => 'required',
                'jenis_identitas' => 'required',
                'no_identitas' => 'required',
                'jenis_kelamin' => 'required',
                'tanggal_daftar' => 'required|date',
                'tanggal_expired' => 'required|date'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $r_jenis_kelamin = in_array($jenis_kelamin, $this->member_m->enum('jenis_kelamin'));
            if(!$r_jenis_kelamin) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('jenis_kelamin_tidak_terdaftar')));
                continue;
            }
            $data['jenis_kelamin'] = array_search($jenis_kelamin, $this->member_m->enum('jenis_kelamin'));

            if($kota) {
                $r_kota = $this->kota_m->where('LOWER(kota)', strtolower($kota))->first();
                if(!$r_kota) {
                    $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('kota_tidak_ditemukan')));
                    continue;
                }
                $data['id_kota'] = $r_kota->id;
            }

            $r_jenis_identitas = $this->jenis_identitas_m->where('LOWER(jenis_identitas)', strtolower($jenis_identitas))->first();
            if(!$r_jenis_identitas) {
                $r_jenis_identitas = $this->jenis_identitas_m->insert($data);
            }
            $data['id_jenis_identitas'] = $r_jenis_identitas->id;

            $r_pelanggan = $this->pelanggan_m->where('LOWER(nama)', strtolower($nama))
                                             ->where('LOWER(id_jenis_identitas)', strtolower($r_jenis_identitas->id))
                                             ->where('LOWER(no_identitas)', strtolower($no_identitas))
                                             ->first();
            if(!$r_pelanggan) {
                $r_pelanggan = $this->pelanggan_m->insert($data);
            }
            $data['id_pelanggan'] = $r_pelanggan->id;

            $r_member = $this->member_m->where('id_pelanggan', $r_pelanggan->id)->first();
            if($r_member) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('member_sudah_terdaftar')))));
                continue;
            }

            $r_jenis_member = $this->jenis_member_m->where('LOWER(jenis_member)', strtolower($jenis_member))->first();
            if(!$r_jenis_member) {
                $r_jenis_member = $this->jenis_member_m->insert($data);
            }
            $data['id_jenis_member'] = $r_jenis_member->id;

            $result = $this->member_m->insert($data);

            if ($result) {
                $success_count++;
            } else {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('member')))));
            }
        }
        $this->redirect->with('import_error_message', $errors)
        ->with('import_success_message', $success_count)
        ->back();
    }

    public function download_format() {
        $this->load->helper('download');
        $path = base_url('public/member/member/import_member.xlsx');
        $data = file_get_contents($path);
        $name = 'import_member.xlsx';
        return force_download($name, $data);
    }

    public function export() {
        $spreadsheet = IOFactory::load('public/member/member/import_member.xlsx');
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


        $rs_member = $this->member_m->view('pelanggan')->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Member');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_member as $key => $member) {
            $worksheet->getCell('A'.$row)->setValue($no);
            $worksheet->getCell('B'.$row)->setValue($member->kode);
            $worksheet->getCell('C'.$row)->setValue($member->jenis_member);
            $worksheet->getCell('D'.$row)->setValue($member->nama);
            $worksheet->getCell('E'.$row)->setValue($member->jenis_identitas);
            $worksheet->getCell('F'.$row)->setValue($member->no_identitas);
            $worksheet->getCell('G'.$row)->setValue($this->member_m->enum('jenis_kelamin', $member->jenis_kelamin));
            $worksheet->getCell('H'.$row)->setValue($member->telepon);
            $worksheet->getCell('I'.$row)->setValue($member->hp);
            $worksheet->getCell('J'.$row)->setValue($member->kota);
            $worksheet->getCell('K'.$row)->setValue($member->alamat);
            $worksheet->getCell('L'.$row)->setValue(date('d-m-Y', strtotime($member->tanggal_daftar)));
            $worksheet->getCell('M'.$row)->setValue(date('d-m-Y', strtotime($member->tanggal_expired)));
            for($i=0;$i<13;$i++){
                $spreadsheet->getActiveSheet()->getStyle($cols[$i].$row)->applyFromArray($style);
            }
            $no++; $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="member.xlsx"');
        $writer->save("php://output");
    }

    public function json($id) {
        $result = $this->member_m->view('pelanggan')->find_or_fail($id);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

}