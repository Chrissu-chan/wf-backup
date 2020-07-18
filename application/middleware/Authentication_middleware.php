<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication_middleware {

    public function handle($handle) {
        if (!$handle->auth->authenticated()) {
            $handle->exceptions->failed_authentication($handle);
        }
    }
}