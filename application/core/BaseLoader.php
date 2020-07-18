<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseLoader extends CI_Loader {

    public function view($view, $vars = array(), $return = false) {
        $output = parent::view($view, $vars, true);
        preg_match_all('/\{{[a-zA-Z0-9_]*\}}/', $output, $langs);
        foreach ($langs[0] as $lang) {
            $label = $this->localization->lang(str_replace(array('{', '}'), '', $lang));
            $output = str_replace($lang, $label, $output);
        }
        $output = trim($output);
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    public function library($library, $params = NULL, $object_name = NULL) {
        if (is_array($library)) {
            foreach ($library as $key => $value)  {
                if (strpos($value, ':')) {
                    $parse = explode(':', $value);
                    $this->library($parse[0], null, $parse[1]);
                } else {
                    $this->library($value);
                }
            }
        } else {
            parent::library($library, $params, $object_name);
        }
        return $this;
    }
}