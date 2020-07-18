<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Development_middleware {

    public function handle($handle) {
        if (ENVIRONMENT <> 'development') {
            show_404();
        }
    }
}