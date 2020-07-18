<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('shift_m');
        $this->load->model('shift_waktu_m');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->shift_m)
            ->add_column('waktu', function($model) {
                return $this->shift_waktu_m->where('id_shift', $model->id)->get();
            })
            ->add_action('{view} {edit} {delete}', array(
                'edit' => function($model) {
                    return $this->action->link('edit', $this->route->name('master.shift.edit', array('id' => $model->id)), 'class="btn btn-warning btn-sm"');
                }
            ))
            ->generate();
        }
        $this->load->view('master/shift/index');
    }

    public function view($id) {
        $model = $this->shift_m->find_or_fail($id);
        $rs_shift_waktu = $this->shift_waktu_m->where('id_shift', $id)->get();
        foreach ($rs_shift_waktu as $r_shift_waktu) {
            $model->waktu[$r_shift_waktu->urutan] = $r_shift_waktu;
        }
        $this->load->view('master/shift/view', array(
            'model' => $model
        ));
    }

    public function create() {
        $this->load->view('master/shift/create');
    }

    public function store() {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'shift' => 'required',
            'jumlah_shift' => 'required|greater_than[0]',
            'waktu[]' => 'required'
        ));
        $shift = $this->shift_m->insert($post);
        foreach ($post['waktu'] as $urutan => $waktu) {
            $waktu['id_shift'] = $shift->id;
            $waktu['urutan'] = $urutan;
            $this->shift_waktu_m->insert($waktu);
        }
        $this->redirect->with('success_message', $this->localization->lang('success_store_message', array('name' => $this->localization->lang('shift'))))->route('master.shift');
    }

    public function edit($id) {
        $model = $this->shift_m->find_or_fail($id);
        $rs_shift_waktu = $this->shift_waktu_m->where('id_shift', $id)->get();
        foreach ($rs_shift_waktu as $r_shift_waktu) {
            $model->waktu[$r_shift_waktu->urutan] = $r_shift_waktu;
        }
        $this->load->view('master/shift/edit', array(
            'model' => $model
        ));
    }

    public function update($id) {
        $post = $this->input->post();
        $this->form_validation->validate(array(
            'shift' => 'required',
            'jumlah_shift' => 'required|greater_than[0]',
            'waktu[]' => 'required'
        ));
        $this->shift_m->update($id, $post);
        $this->shift_waktu_m->where('id_shift', $id)->delete();
        foreach ($post['waktu'] as $urutan => $waktu) {
            $waktu['id_shift'] = $id;
            $waktu['urutan'] = $urutan;
            $this->shift_waktu_m->insert($waktu);
        }
        $this->redirect->with('success_message', $this->localization->lang('success_update_message', array('name' => $this->localization->lang('shift'))))->route('master.shift');
    }

    public function delete($id) {
        $this->shift_m->delete($id);
        $this->shift_waktu_m->where('id_shift', $id)->delete();
        $this->redirect->with('success_message', $this->localization->lang('success_delete_message', array('name' => $this->localization->lang('shift'))))->route('master.shift');
    }
}