<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Failed_authentication_exception {

    public function handle($handle) {
        if ($handle->input->is_ajax_request()) {
            $response = array(
                'success' => false,
                'message' => $handle->localization->lang('error_authentication')
            );
            $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
            exit;
        } else {
            $handle->redirect->route('login');
        }
    }
}