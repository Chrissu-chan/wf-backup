<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Already_login_exception {

    public function handle($handle) {
        if ($handle->input->is_ajax_request()) {
            $response = array(
                'success' => false,
                'message' => $handle->localization->lang('already_login')
            );
            $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
            exit;
        } else {
            $handle->redirect->with('error_message', $handle->localization->lang('already_login'))->route('dashboard');        
        }
    }
}