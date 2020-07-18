<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Quantities_exception {

        public function handle($handle) {
            $handle->transaction->rollback();
            if ($handle->input->is_ajax_request()) {
                $response = array(
                    'success' => false,
                    'message' => $handle->localization->lang('error_quantities_message')
                );
                $handle->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
                exit;
            } else {
                $handle->redirect->with_input()->with('error_message', $handle->localization->lang('error_quantities_message'))->back();
            }
        }
    }