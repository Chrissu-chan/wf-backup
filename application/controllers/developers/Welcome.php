<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends BaseController {

    public function index() {
        $this->load->view('developers/welcome');
    }
}