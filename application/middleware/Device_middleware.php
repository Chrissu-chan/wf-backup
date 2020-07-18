<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_middleware {

    public function handle($handle) {
        $device_id = $handle->input->post('device_id');
        if (!$device_id) {
            $handle->exceptions->failed_device($handle);
        }
        $user = $handle->auth->attempt_api($device_id, array('active' => 1));
        if (!$user) {
            $handle->exceptions->failed_device($handle);
        }
    }
}