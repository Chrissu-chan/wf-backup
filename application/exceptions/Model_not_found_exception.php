<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_not_found_exception {

    public function handle($handle) {
        if ($handle->input->is_ajax_request()) {
            $response = array(
                'success' => false,
                'message' => $handle->localization->lang('model_not_found')
            );
            $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
            exit;
        } else {
            $handle->redirect->with('error_message', $handle->localization->lang('model_not_found'))->back();
        }
    }
}