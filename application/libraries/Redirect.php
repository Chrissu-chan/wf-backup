<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect {

    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();              
    }

    public function with($name, $value) {
        if (is_array($name)) {
            foreach ($name as $var => $val) {
                $this->CI->session->set_flashdata($var, $val);
            }
        } else {
            $this->CI->session->set_flashdata($name, $value);
        }
        return $this;
    }

    public function with_input($input = null) {
        if (!$input) {
            $input = $this->CI->input->post();
        }
        $this->CI->session->set_flashdata('input', $input);
        return $this;
    }

    public function with_validation() {
        $this->CI->session->set_flashdata('validation_errors', $this->CI->form_validation->errors());
        $this->CI->session->set_flashdata('validation_message', validation_errors());                
        return $this;
    }

    public function to($url) {
        redirect($url);
    }

    public function route($name, $params = null) {
        $url = $this->CI->route->name($name, $params);
        redirect($url);
    }

    public function back() {
        redirect($this->CI->url_generator->previous_url());
    }

    public function refresh() {
        redirect($this->CI->url_generator->full_url());
    }

    public function guest($url) {
        $this->CI->session->set_userdata('intended_url', $this->CI->url_generator->full_url());
        redirect($url);
    }

    public function intended($url) {
        $intended_url = $this->CI->session->userdata('intended_url');
        $this->CI->session->unset_userdata('intended_url');
        if ($intended_url) {
            redirect($intended_url);
        } else {
            redirect($url);
        }
    }
}