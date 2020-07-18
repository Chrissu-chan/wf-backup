<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autonumber {

    protected $CI;

    protected $field = 'key';

    protected $format = '{Y}{m}{d}:3';

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function resource($model, $field = '') {
        $this->model = $model;
        $this->field = $field;
        return $this;
    }

    public function format($format) {
        $this->format = $format;
        return $this;
    }

    public function generate() {
        $format = $this->format;
        $parse = explode(':', $format);
        $prefix = str_replace(array('{Y}', '{m}', '{d}'), array(date('Y'), date('m'), date('d')), $parse[0]);
        $digit = str_repeat('0', $parse[1]);
        $last_id =  $this->model->select_max($this->field)
        ->where('left('.$this->field.', '.(strlen($prefix)).') = ', $prefix)
        ->first()
        ->{$this->field};
        if ($last_id) {
            $counter = substr($last_id, -strlen($digit)) + 1;
            return $prefix.substr($digit.$counter, -strlen($digit));
        } else {
            return $prefix.substr($digit.'1', -strlen($digit));
        }
    }
}