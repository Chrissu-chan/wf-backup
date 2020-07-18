<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Url_generator {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function full_url() {
        return current_url().'?'.$_SERVER['QUERY_STRING'];;
    }

    public function current_url() {
        return current_url();
    }

    public function intended_url() {
        $url = $this->CI->session->userdata('intended_url');
        if ($url) {
            return $url;
        } else {
            return null;
        }
    }

    public function previous_url($to = null) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return $to;
        }
    }

    public function route($name, $params = null) {
        $url = $this->CI->routes->name($name);
        return $this->compile_url($url, $params);
    }

    public function compile_url($url, $params = null) {
        $compiler_params = array();
        $compiled_url = $url;
        preg_match_all('/\{(.*?)\}/', $url, $matches);
        foreach ($matches[1] as $key => $param_name) {
            $param_name = trim($param_name, '?');
            if (isset($params[$param_name])) {
                $compiler_params[$matches[1][$key]] = $params[$param_name];
            } else {
                if (strpos($matches[1][$key], '?') === false) {
                    show_error('Undefined variable '.$param_name.' on compile url');
                }
            }
        }
        foreach ($compiler_params as $param_name => $param_value) {
            $compiled_url = str_replace('{'.$param_name.'}', $param_value, $compiled_url);
            $param_name = trim($param_name, '?');
            unset($params[$param_name]);
        }
        $url_query = array();
        if ($params) {
            $url_query = array();
            foreach ($params as $param_name => $param_value) {
                $url_query[]=$param_name.'='.$param_value;
            }
            if (count($url_query) > 0) {
                if (strpos($compiled_url, '?') === false) {
                    $compiled_url .='?'.implode('&', $url_query);
                } else {
                    $compiled_url .='&'.implode('&', $url_query);
                }
            }
        }
        $compiled_url = preg_replace('/\{(.*?)\}/', '', $compiled_url);
        return base_url($compiled_url);
    }
}