<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Failed_device_exception {

    public function handle($handle) {
         $response = array(
            'success' => false,
            'message' => $handle->localization->lang('unknown_device')
        );
        $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit;
    }
}