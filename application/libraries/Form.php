<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form
{

    protected $CI;

    protected $_data;

    protected $_input;

    protected $_macros = array();

    public function __construct($config = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('form');
        $this->_input = $this->CI->session->flashdata('input');
    }

    public function set_data($data)
    {
        $data = json_encode($data);
        $data = json_decode($data, true);
        $this->_data = $data;
        return $this;
    }

    public function data($name, $value = null)
    {
        if ($this->get_input_value($name) || $this->get_input_value($name) === '' || $this->get_input_value($name) === '0' || $this->get_input_value($name) === 0) {
            $value = $this->get_input_value($name);
            return $value;
        } elseif ($this->get_data_value($name) || $this->get_data_value($name) === '' || $this->get_data_value($name) === '0' || $this->get_data_value($name) === 0) {
            $data = $this->get_data_value($name);
            $value = $data;
            return $value;
        } else {
            return $value;
        }
    }

    public function model($data, $action, $attributes = '', $hidden = array(), $validation = true) {
        $this->set_data($data);
        if ($validation) {
            return $this->CI->template->view('layouts/partials/validation') . form_open($action, $attributes, $hidden);
        } else {
            return form_open($action, $attributes, $hidden);
        }
    }

    public function open($action = null, $attributes = '', $hidden = array(), $validation = true)
    {
        if ($validation) {
            return $this->CI->template->view('layouts/partials/validation') . form_open($action, $attributes, $hidden);
        } else {
            return form_open($action, $attributes, $hidden);
        }
    }

    public function open_multipart($action = '', $attributes = '', $hidden = array(), $validation = true)
    {
        if ($validation) {
            return $this->CI->template->view('layouts/partials/validation') . form_open_multipart($action, $attributes, $hidden);
        } else {
            return form_open_multipart($action, $attributes, $hidden);
        }
    }

    public function hidden($name, $value = null, $attributes = '')
    {
        return '<input type="hidden" name="'.$name.'" value="'.$this->data($name, $value).'" '.$attributes.'>';
    }

    public function text($name, $value = null, $attributes = '')
    {
        return form_input($name, $this->data($name, $value), $attributes);
    }

    public function number($name, $value = null, $attributes = '', $thousand_separator = null, $decilmal_separator = null, $decimals = 2)
    {
        return form_input($name, $this->CI->localization->number($this->data($name, $value), $decimals, $thousand_separator, $decilmal_separator), $attributes);
    }

    public function date($name, $value = null, $attributes = '')
    {
        return form_input($name, $this->CI->localization->date($this->data($name, $value)), $attributes);
    }

    public function time($name, $value = null, $attributes = '')
    {
        return form_input($name, locale_time($this->data($name, $value)), $attributes);
    }

    public function datetime($name, $value = null, $attributes = '')
    {
        return form_input($name, $this->CI->localization->datetime($this->data($name, $value)), $attributes);
    }

    public function password($name, $value = null, $attributes = '')
    {
        return form_password($name, null, $attributes);
    }

    public function textarea($name, $value = null, $attributes = '')
    {
        return '<textarea name="'.$name.'" '.$attributes.'>'.$this->data($name).'</textarea>';
    }

    public function radio($name, $value = '', $checked = false, $attributes = '')
    {
        if ($this->data($name) == $value) {
            $checked = true;
        }
        return form_radio($name, $value, $checked, $attributes);
    }

    public function checkbox($name, $value = '', $checked = false, $attributes = '')
    {
        if ($this->data($name) == $value) {

            $checked = true;
        }
        return form_checkbox($name, $value, $checked, $attributes);
    }

    public function select($name, $data = array(), $selected = null, $attributes = '')
    {
        return form_dropdown($name, $data, $this->data($name, $selected), $attributes);
    }

    public function multiselect($name, $option = array(), $selected = array(), $attributes = '')
    {
        return form_multiselect($name, $option, $this->data($name, $selected), $attributes);
    }

    public function upload($data, $value = null, $attributes = '')
    {
        return form_upload($data, $value, $attributes);
    }

    public function submit($name, $value = '', $attributes = '')
    {
        return form_submit($name, $value, $attributes);
    }

    public function reset($data, $value = '', $attributes = '')
    {
        return form_reset($data, $value, $attributes);
    }

    public function button($data, $content = '', $attributes = '')
    {
        return form_button($data, $content, $attributes);
    }

    public function label($label, $id = '', $attributes = '')
    {
        return form_label($label, $id, $attributes);
    }

    public function close()
    {
        return form_close();
    }

	public function disabled($actions = array()) {
		$CI = get_instance();
		$method = $CI->app->fetch_activity()->method;
		if ($method) {
			if (in_array($method, $actions)) {
				return 'disabled';
			}
		}
		return '';
	}


	protected function transform_key($key)
    {
        return str_replace(array('.', '[]', '[', ']'), array('_', '', '.', ''), $key);
    }

    protected function get_data_array($array, $key, $default) {
        $key = $this->transform_key($key);
        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }

    protected function get_data_object($object, $key, $default) {
        $object = $this->_data;
        $key = $this->transform_key($key);
        if (is_null($key) || trim($key) == '') return $object;
        foreach (explode('.', $key) as $segment) {
            if (!is_object($object) || !isset($object->{$segment})) {
                return $default;
            }

            $object = $object->{$segment};
        }
        return $object;
    }

    protected function get_input_value($key, $default = null)
    {
        if (is_array($this->_input)) {
            $result = $this->get_data_array($this->_input, $key, $default);
        } else {
            $result = $this->get_data_object($this->_input, $key, $default);
        }
        return $result;
    }

    protected function get_data_value($key, $default = null)
    {
        if (is_array($this->_data)) {
            $result = $this->get_data_array($this->_data, $key, $default);
        } else {
            $result = $this->get_data_object($this->_data, $key, $default);
        }
        return $result;
    }

}