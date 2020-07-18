<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contracts {

	protected $contracts;

	public function __construct() {		
		$this->contracts = &get_instance();
	}

}
