<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perpanjangan_membership extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_m');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["title"] = "Perpanjangan Membership";
        if ($this->input->is_ajax_request()) {
            $this->load->library('datatable');
            return $this->datatable->resource($this->member_m)
                ->add_action('{view} {edit} {delete}')
                ->generate();
        }
        $this->load->view('transaksi/perpanjangan_membership/index');
    }

    public function view($id)
    {
        $this->load->view('transaksi/perpanjangan_membership/view');
    }

    public function create()
    {
        $this->load->view('transaksi/perpanjangan_membership/create');
    }
}
