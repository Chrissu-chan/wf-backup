<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_middleware {

    public function handle($handle) {
        $handle->load->model('shift_aktif_m');
        if (!$handle->shift_aktif_m->scope('cabang')->scope('aktif')->first()) {
            $handle->exceptions->shift($handle);
        }
    }
}