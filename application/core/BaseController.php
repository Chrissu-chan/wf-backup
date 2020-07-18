<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {

    protected $contract = true;

	public function __construct() {
		parent::__construct();
        $this->config();
        $this->load->config('middleware');
        $this->middleware = $this->config->item('middleware');
        foreach ($this->middleware['load'] as $middleware) {
            $this->load->library('../middleware/'.$middleware.'_middleware');
        }
        $this->middleware();

        $this->contract = $this->contracts();
	}

    public function config() {
        $this->load->model('config_m');
        foreach ($this->config_m->where('application_id', $this->config->item('application_id'))->get() as $config) {
            $this->config->set_item($config->key, $config->value);
        }
    }

	public function middleware() {
        $access['directory'] = trim($this->router->directory, '/');
        $access['class'] = trim($this->router->fetch_class(), '/');
        $access['method'] = trim($this->router->fetch_method(), '/');

        if ($this->config->item('allow_developers_tools') === false) {
            if ($access['directory'] == 'developers') {
                $this->load->library('../middleware/development_middleware');
                $this->development_middleware->handle($this);
            }
        }

        if ($access['directory']) {
            if (isset($this->middleware['handle']['middleware'])) {
                $exceptions = isset($this->middleware['handle']['exceptions']) ? $this->middleware['handle']['exceptions'] : array();
                if (!in_array($access['directory'], $exceptions)) {
                    foreach ($this->middleware['handle']['middleware'] as $middleware) {
                        $this->{$middleware.'_middleware'}->handle($this);
                    }
                }
            }
            if (isset($this->middleware['handle'][$access['directory']]['middleware'])) {
                $exceptions = isset($this->middleware['handle'][$access['directory']]['exceptions']) ? $this->middleware['handle'][$access['directory']]['exceptions'] : array();
                if (!in_array($access['class'], $exceptions)) {
                    foreach ($this->middleware['handle'][$access['directory']]['middleware'] as $middleware) {
                        $this->{$middleware.'_middleware'}->handle($this);
                    }
                }
            }
            if (isset($this->middleware['handle'][$access['directory']][$access['class']][$access['method']]['middleware'])) {
                foreach ($this->middleware['handle'][$access['directory']][$access['class']][$access['method']]['middleware'] as $middleware) {
                    $this->{$middleware.'_middleware'}->handle($this);
                }
            }
        } else {
            if (isset($this->middleware['handle'][$access['class']][$access['method']]['middleware'])) {
                foreach ($this->middleware['handle'][$access['class']][$access['method']]['middleware'] as $middleware) {
                    $this->{$middleware.'_middleware'}->handle($this);
                }
            }
        }

        if ($access['class']) {
            if ($access['directory']) {
                if (isset($this->middleware['handle'][$access['directory']][$access['class']]['middleware'])) {
                    $exceptions = isset($this->middleware['handle'][$access['directory']][$access['class']]['exceptions']) ? $this->middleware['handle'][$access['directory']][$access['class']]['exceptions'] : array();
                    if (!in_array($access['method'], $exceptions)) {
                        foreach ($this->middleware['handle'][$access['directory']][$access['class']]['middleware'] as $middleware) {
                            $this->{$middleware.'_middleware'}->handle($this);
                        }
                    }
                }
            } else {
                if (isset($this->middleware['handle']['middleware'])) {
                    $exceptions = isset($this->middleware['handle']['exceptions']) ? $this->middleware['handle']['exceptions'] : array();
                    if (!in_array($access['class'], $exceptions)) {
                        foreach ($this->middleware['handle']['middleware'] as $middleware) {
                            $this->{$middleware.'_middleware'}->handle($this);
                        }
                    }
                }
                if (isset($this->middleware['handle'][$access['class']]['middleware'])) {
                    $exceptions = isset($this->middleware['handle'][$access['class']]['exceptions']) ? $this->middleware['handle'][$access['class']]['exceptions'] : array();
                    if (!in_array($access['method'], $exceptions)) {
                        foreach ($this->middleware['handle'][$access['class']]['middleware'] as $middleware) {
                            $this->{$middleware.'_middleware'}->handle($this);
                        }
                    }
                }
            }
        }
    }

	public function contracts() {
		$this->load->library('contracts');
		$directory = $this->router->directory;
		$class = $this->router->fetch_class();
		$method = $this->router->fetch_method();
		if (file_exists(APPPATH . '/contracts/' . $directory . ucwords($class) . '_contracts.php')) {
			$contracts = strtolower($class . '_contracts');
			$this->load->library('../contracts/' . $directory . $contracts);
			if (method_exists($this->{$contracts}, $method)) {
				$params = array_slice($this->uri->rsegment_array(), 2);
				call_user_func_array(array($this->{$contracts}, $method), $params);
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
}