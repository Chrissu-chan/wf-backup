<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Barang extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_m');
        $this->load->model('kategori_barang_m');
        $this->load->model('jenis_barang_m');
        $this->load->model('satuan_m');
        $this->load->model('konversi_satuan_m');
        $this->load->model('views/view_hpp_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Master Barang";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->barang_m)
                ->view('barang')
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('master/barang/index', $data);
    }

    public function view($id)
    {
        $model = $this->barang_m->view('barang')->find_or_fail($id);
        $this->load->view('master/barang/view', array(
            'model' => $model
        ));
    }

    public function create()
    {
        $this->load->view('master/barang/create');
    }

    public function store()
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode' => 'required|is_unique[barang.kode]',
            'barcode' => 'callback_validate_barcode',
            'nama' => 'required',
            'id_kategori_barang' => 'required',
            'id_jenis_barang' => 'required',
            'id_satuan_barang' => 'required'
        ));
        $result = $this->barang_m->insert($post);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_store_message', array('name' => $this->localization->lang('barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_store_message', array('name' => $this->localization->lang('barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function edit($id)
    {
        $model = $this->barang_m->find_or_fail($id);
        $rs_kategori_obat = $this->barang_kategori_obat_m->where('id_barang', $id)->get();
        $rs_fungsi_obat = $this->barang_fungsi_obat_m->where('id_barang', $id)->get();
        foreach ($rs_kategori_obat as $r_kategori_obat) {
            $model->kategori_obat[] = $r_kategori_obat->id_kategori_obat;
        }
        foreach ($rs_fungsi_obat as $r_fungsi_obat) {
            $model->fungsi_obat[] = $r_fungsi_obat->id_fungsi_obat;
        }
        $this->load->view('master/barang/edit', array(
            'model' => $model
        ));
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'kode' => 'required|is_unique[barang.kode.' . $id . ']',
            'barcode' => 'callback_validate_barcode[' . $id . ']',
            'nama' => 'required',
            'id_kategori_barang' => 'required',
            'id_jenis_barang' => 'required',
            'id_satuan_barang' => 'required'
        ));
        $result = $this->barang_m->update($id, $post);

        $this->barang_kategori_obat_m->where('id_barang', $id)->delete();
        $rs_barang_kategori_obat = array();
        foreach ($post['kategori_obat'] as $kategori_obat) {
            $rs_barang_kategori_obat[] = array(
                'id_barang' => $id,
                'id_kategori_obat' => $kategori_obat
            );
        }
        $this->barang_kategori_obat_m->insert_batch($rs_barang_kategori_obat);

        $this->barang_fungsi_obat_m->where('id_barang', $id)->delete();
        $rs_barang_fungsi_obat = array();
        foreach ($post['fungsi_obat'] as $fungsi_obat) {
            $rs_barang_fungsi_obat[] = array(
                'id_barang' => $id,
                'id_fungsi_obat' => $fungsi_obat
            );
        }
        $this->barang_fungsi_obat_m->insert_batch($rs_barang_fungsi_obat);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_update_message', array('name' => $this->localization->lang('barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_update_message', array('name' => $this->localization->lang('barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete($id)
    {
        $result = $this->barang_m->delete($id);
        if ($result) {
            $response = array(
                'success' => true,
                'message' => $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('barang')))
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('error_delete_message', array('name' => $this->localization->lang('barang')))
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_json()
    {
        if ($id_rak_gudang = $this->input->get('id_rak_gudang')) {
            $this->barang_m->where('id_rak_gudang', $id_rak_gudang);
        } else {
            $this->barang_m->scope('not_located');
        }
        $key = $this->input->get('key');
        if ($key) {
            $this->db->group_start()
                ->like('kode', $key)
                ->or_like('barcode', $key)
                ->or_like('nama', $key)
                ->group_end();
        }
        $result = $this->barang_m->view('barang')->get();
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_json()
    {
        $id = $this->input->get('id');
        $result = $this->barang_m->view('barang')->find_or_fail($id);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_by_barcode_json()
    {
        $post = $this->input->post();
        $key = explode(' - ', $post['key']);
        $result = $this->barang_m->view('barang')
            ->group_start()
            ->like('barcode', $key[0])
            ->or_like('kode', $key[0])
            ->or_like('nama', end($key))
            ->group_end()
            ->first();
        if ($result) {
            $response = array(
                'success' => TRUE,
                'data' => $result
            );
        } else {
            $response = array(
                'success' => FALSE,
                'data' => NULL
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function find_hpp_json($id_barang)
    {
        if ($id_gudang = $this->input->get('id_gudang')) {
            $this->db->where('id_gudang', $id_gudang);
        }
        $result = $this->view_hpp_m->view('hpp')
            ->where('id_barang', $id_barang)
            ->first();
        if ($result->id_satuan) {
            if (($id_satuan_tujuan = $this->input->get('id_satuan')) && ($id_satuan_tujuan != $result->id_satuan)) {
                $konversi = $this->konversi_satuan_m->convert($id_satuan_tujuan, $result->id_satuan, 1);
                if ($konversi) {
                    $result->hpp *= $konversi;
                    $response = array(
                        'success' => true,
                        'data' => $result
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => $this->localization->lang('konversi_satuan_tidak_ditemukan')
                    );
                }
            } else {
                $response = array(
                    'success' => true,
                    'data' => $result
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => $this->localization->lang('hpp_tidak_ditemukan')
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function hpp_json()
    {
        $id = $this->input->get('id');
        $result = $this->view_hpp_m->find_or_fail($id);
        $response = array(
            'success' => true,
            'data' => $result
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function browse()
    {
        if ($this->input->get('load')) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->barang_m)
                ->view('barang')
                ->edit_column('stok', function ($model) {
                    return $this->localization->number($model->stok);
                })
                ->generate();
        }
        $this->load->view('master/barang/browse');
    }

    public function import()
    {
        $this->load->view('master/barang/import');
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
            'B' => 'Kode',
            'C' => 'Barcode',
            'D' => 'Nama',
            'E' => 'Kategori Barang',
            'F' => 'Jenis Barang',
            'G' => 'Satuan Barang',
            'H' => 'Jenis Obat',
            'I' => 'Kategori Obat',
            'J' => 'Fungsi Obat',
            'K' => 'Kandungan Obat'
        );

        foreach ($format as $key => $value) {
            if ($worksheet['5'][$key] != $value) {
                $this->redirect->with('error_message', $this->localization->lang('format_tidak_sesuai'))->back();
            }
        }

        for ($i = 6; $i <= count($worksheet); $i++) {
            $no = $worksheet[$i]['A'];
            $kode = trim($worksheet[$i]['B']);
            $barcode = trim($worksheet[$i]['C']);
            $nama = trim($worksheet[$i]['D']);
            $kategori_barang = trim($worksheet[$i]['E']);
            $jenis_barang = trim($worksheet[$i]['F']);
            $satuan_barang = trim($worksheet[$i]['G']);
            $jenis_obat = trim($worksheet[$i]['H']);
            $kategori_obat = explode(";", trim($worksheet[$i]['I']));
            $fungsi_obat = explode(";", trim($worksheet[$i]['J']));
            $kandungan_obat = trim($worksheet[$i]['K']);

            $data = array(
                'kode' => $kode,
                'barcode' => $barcode,
                'nama' => $nama,
                'kategori_barang' => $kategori_barang,
                'jenis_barang' =>  $jenis_barang,
                'satuan_barang' => $satuan_barang,
                'jenis_obat' => $jenis_obat,
                'kandungan_obat' => $kandungan_obat
            );

            $this->form_validation->set_data($data);
            if (!$this->form_validation->validate(array(
                'kode' => 'required|is_unique[barang.kode]',
                'barcode' => 'callback_validate_barcode',
                'nama' => 'required',
                'kategori_barang' => 'required',
                'jenis_barang' => 'required',
                'satuan_barang' => 'required',
                'jenis_obat' => 'required'
            ), true)) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => implode(', ', $this->form_validation->errors())));
                continue;
            }

            $r_kategori_barang = $this->kategori_barang_m->where('LOWER(kategori_barang)', strtolower($kategori_barang))->first();
            if (!$r_kategori_barang) {
                $r_kategori_barang = $this->kategori_barang_m->insert(array(
                    'kategori_barang' => $kategori_barang,
                    'parent_id' => 0
                ));
            }
            $data['id_kategori_barang'] = $r_kategori_barang->id;

            $r_jenis_barang = $this->jenis_barang_m->where('LOWER(jenis_barang)', strtolower($jenis_barang))->first();
            if (!$r_jenis_barang) {
                $r_jenis_barang = $this->jenis_barang_m->insert(array(
                    'jenis_barang' => $jenis_barang
                ));
            }
            $data['id_jenis_barang'] = $r_jenis_barang->id;

            $r_satuan_barang = $this->satuan_m->where('LOWER(satuan)', strtolower($satuan_barang))->first();
            if (!$r_satuan_barang) {
                $errors[] = $this->localization->lang('import_error_message', array('no' => $no, 'errors' => $this->localization->lang('satuan_barang_tidak_terdaftar')));
                continue;
            }
            $data['id_satuan_barang'] = $r_satuan_barang->id;

            $r_jenis_obat = $this->jenis_obat_m->where('LOWER(jenis_obat)', strtolower($jenis_obat))->first();
            if (!$r_jenis_obat) {
                $r_jenis_obat = $this->jenis_obat_m->insert(array(
                    'jenis_obat' => $jenis_obat
                ));
            }
            $data['id_jenis_obat'] = $r_jenis_obat->id;

            $result = $this->barang_m->insert($data);

            $rs_kategori_obat = array();
            foreach ($kategori_obat as $val) {
                $r_kategori_obat = $this->kategori_obat_m->where('LOWER(kategori_obat)', strtolower($val))->first();
                if (!$r_kategori_obat) {
                    $r_kategori_obat = $this->kategori_obat_m->insert(array(
                        'kategori_obat' => $val
                    ));
                }
                $rs_kategori_obat[] = array(
                    'id_barang' => $result->id,
                    'id_kategori_obat' => $r_kategori_obat->id
                );
            }
            if ($rs_kategori_obat) {
                $this->barang_kategori_obat_m->insert_batch($rs_kategori_obat);
            }

            $rs_fungsi_obat = array();
            foreach ($fungsi_obat as $val) {
                $r_fungsi_obat = $this->fungsi_obat_m->where('LOWER(fungsi_obat)', strtolower($val))->first();
                if (!$r_fungsi_obat) {
                    $r_fungsi_obat = $this->fungsi_obat_m->insert(array(
                        'fungsi_obat' => $val
                    ));
                }
                $rs_fungsi_obat[] = array(
                    'id_barang' => $result->id,
                    'id_fungsi_obat' => $r_fungsi_obat->id
                );
            }
            if ($rs_fungsi_obat) {
                $this->barang_fungsi_obat_m->insert_batch($rs_fungsi_obat);
            }

            if ($result) {
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
        $path = base_url('public/master/barang/import_barang.xlsx');
        $data = file_get_contents($path);
        $name = 'import_barang.xlsx';
        return force_download($name, $data);
    }

    public function export()
    {
        $spreadsheet = IOFactory::load('public/master/barang/import_barang.xlsx');
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


        $rs_barang = $this->barang_m->view('barang')->get();
        $row = 6;
        $no = 1;
        $worksheet->getCell('A1')->setValue('Data Barang');
        $worksheet->getCell('A3')->setValue(date('d-m-Y'));
        foreach ($rs_barang as $key => $barang) {
            $worksheet->getCell('A' . $row)->setValue($no);
            $worksheet->getCell('B' . $row)->setValue($barang->kode);
            $worksheet->getCell('C' . $row)->setValue($barang->barcode);
            $worksheet->getCell('D' . $row)->setValue($barang->nama);
            $worksheet->getCell('E' . $row)->setValue($barang->kategori_barang);
            $worksheet->getCell('F' . $row)->setValue($barang->jenis_barang);
            $worksheet->getCell('G' . $row)->setValue($barang->kode_satuan_barang);
            $worksheet->getCell('H' . $row)->setValue($barang->jenis_obat);
            $worksheet->getCell('I' . $row)->setValue(str_replace(', ', ';', $barang->kategori_obat));
            $worksheet->getCell('J' . $row)->setValue(str_replace(', ', ';', $barang->fungsi_obat));
            $worksheet->getCell('K' . $row)->setValue($barang->kandungan_obat);
            for ($i = 0; $i < 1; $i++) {
                $spreadsheet->getActiveSheet()->getStyle($cols[$i] . $row)->applyFromArray($style);
            }
            $no++;
            $row++;
        }


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="barang.xlsx"');
        $writer->save("php://output");
    }

    public function validate_barcode($str, $attr)
    {
        if ($this->input->post('barcode')) {
            if ($attr) {
                $this->barang_m->where('id != ', $attr);
            }
            $r_barang = $this->barang_m->where('barcode', $str)->first();
            if ($r_barang) {
                $this->form_validation->set_message('validate_barcode', 'The {field} field must contain a unique value.');
                return FALSE;
            }
        }
    }
}
