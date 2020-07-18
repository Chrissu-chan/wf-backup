<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exceptions {

    protected $exceptions;

    public function __construct() {     
        $this->exceptions = &get_instance();
    }

    public function __call($method, $args) {
        $this->handler($method);
    }

    public function handler($exception) {
        $this->exceptions->load->library('../exceptions/'.$exception.'_exception');
        $this->exceptions->{$exception.'_exception'}->handle($this->exceptions);
    }
}