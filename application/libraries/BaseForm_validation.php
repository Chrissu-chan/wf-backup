<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseForm_validation extends CI_Form_validation {

    protected $CI;

    public function __construct() {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function validate($validations = array(), $return = false) {
        $this->_error_array = array();
        foreach ($validations as $name => $validation) {
            if (is_array($validation)) {
                $field = $validation['field'];
                $rules = $validation['rules'];
            } else {
                $field = $name;
                $rules = $validation;
            }
            $this->set_rules($name, $this->CI->localization->lang(str_replace(array('[',']'), '', $field)), $rules);
        }
        if (!$return) {
            if (!$this->run()) {
                if ($this->CI->input->is_ajax_request()) {
                    $response = array(
                        'success' => false,
                        'message' => validation_errors(),
                        'validation' => $this->errors()
                    );
                    $this->CI->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
                    exit;
                } else {
                    $this->CI->redirect->with_input()->with_validation()->back();
                }
            }
        }
        return $this->run();
    }

    public function numeric($str) {
        return parent::numeric($this->CI->localization->number_value($str));
    }

    public function greater_than($str, $value) {
        return parent::greater_than($this->CI->localization->number_value($str), $this->CI->localization->number_value($value));
    }

    public function greater_than_equal_to($str, $value) {
        return parent::greater_than_equal_to($this->CI->localization->number_value($str), $this->CI->localization->number_value($value));
    }

    public function is_unique($str, $field) {
        $column_id = 'id';
        $parse = explode('.', $field);
        if (isset($parse[3])) {
            $column_id = $parse[3];
        }
        if (isset($parse[2])) {
            $key = $parse[2];
            if (isset($parse[3])) {
                $column_id = $parse[2];
                $key = $parse[3];
            }
            $this->CI->db->where($column_id.' <> ', $key);
        }
        if ($this->CI->db->where('LOWER('.$parse[1].')', strtolower($str))->get($parse[0])->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function errors() {
        $errors = array();
        foreach ($this->error_array() as $field => $message) {
            $field = str_replace(array('[', ']'), '', $field);
            $errors[$field] = $message;
        }
        return $errors;
    }
}