<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Failed_authorization_exception {

    public function handle($handle) {
        if ($handle->input->is_ajax_request()) {
            $response = array(
                'success' => false,
                'message' => $handle->localization->lang('error_authorization')
            );
            $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
            exit;
        } else {
            show_error($handle->localization->lang('error_authorization'));
        }
    }
}