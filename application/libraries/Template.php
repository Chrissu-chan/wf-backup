<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template {

	protected $CI;

	protected $openSectionName = null;

	protected $render = array();

	public function __construct() {
		$this->CI = & get_instance();		
	}

	public function section($name)  {
		$this->openSectionName = $name;
		ob_start();
	}

	public function endsection() {	
		try {
			if ($this->openSectionName)	{
				$this->render[$this->openSectionName] = ob_get_contents();
				$this->openSectionName = null;
				ob_get_clean();
			} else {
				throw new Exception('EndSectionWitoutStart');
			}
		} catch(Exception $e) {
			show_error($e->getMessage());
		}		
	}

	public function has_section($name) {
		return isset($this->render[$name]);
	}

	public function render($name, $return = false) {		
		if (isset($this->render[$name])) {
			if ($return) {
				return $this->render;
			}
			echo $this->render[$name];
		}
	}

	public function view($view, $data = null, $return = false) {
		$data['CI'] = $this->CI;
		$output = $this->CI->load->view($view, $data, true);
		$output = trim($output);
		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}

}