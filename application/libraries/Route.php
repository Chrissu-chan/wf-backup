<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route {

    protected $CI;

    protected $routes = array();

    public function __construct($config = array()) {
        $this->CI = &get_instance();
        $this->CI->load->config('routes');
        $this->routes = $this->CI->config->item('routes');        
    }

    public function name($name, $params = null) {        
        if (isset($this->routes[$name])) {
            $url = $this->routes[$name];            
            return $this->CI->url_generator->compile_url($url, $params);
        } else {
            return $this->CI->url_generator->compile_url(str_replace('.', '/', $name), $params);            
        }
    }
}