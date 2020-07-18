<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseUpload extends CI_Upload {

    protected $CI;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->CI = &get_instance();
    }

    public function has($name) {
        return file_exists($_FILES[$name]['tmp_name']);
    }

    public function display_errors($prefix = '', $suffix = '') {
        return parent::display_errors($prefix, $suffix);
    }
}