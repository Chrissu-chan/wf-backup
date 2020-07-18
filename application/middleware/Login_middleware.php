<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_middleware {

    public function handle($handle) {
        if ($handle->auth->authenticated()) {
            $handle->exceptions->already_login($handle);
        }
    }
}