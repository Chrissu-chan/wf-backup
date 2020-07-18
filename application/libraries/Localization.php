<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Localization {

    protected $CI;

    protected $lang = array();

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->helper('file');
        $this->load();
    }

    public function load() {
        $language = $this->CI->config->item('language');
        $path = APPPATH . '/language/' . $language;
        $files = $data = get_filenames($path);
        if ($files) {
            foreach ($files as $file) {
                if (strtolower(substr($file, -3)) == 'php') {
                    require_once($path . '/' . $file);
                    if (isset($lang)) {
                        $this->lang = array_merge($this->lang, $lang);
                    }
                }
            }
        }
    }

    public function lang($name, $params = null) {
        if (isset($this->lang[$name])) {
            return $this->build($this->lang[$name], $params);
        } else {
            $lang = str_replace('_', ' ', $name);
            return ucwords($lang);
        }
    }

    public function build($word, $params = null) {
        if ($params) {
            foreach ($params as $key => $param) {
                $word = str_replace(':'.$key, $param, $word);
            }
            return $word;
        } else {
            return $word;
        }
    }

    function months($placeholder = null, $placeholder_value = '') {
        if ($placeholder) {
            $lists[$placeholder_value] = $placeholder;
        }
        $lists['01'] = $this->lang('month_01');
        $lists['02'] = $this->lang('month_02');
        $lists['03'] = $this->lang('month_03');
        $lists['04'] = $this->lang('month_04');
        $lists['05'] = $this->lang('month_05');
        $lists['06'] = $this->lang('month_06');
        $lists['07'] = $this->lang('month_07');
        $lists['08'] = $this->lang('month_08');
        $lists['09'] = $this->lang('month_09');
        $lists['10'] = $this->lang('month_10');
        $lists['11'] = $this->lang('month_11');
        $lists['12'] = $this->lang('month_12');
        return $lists;
    }

    function years($placeholder = null, $placeholder_value = '') {
        if ($placeholder) {
            $lists[$placeholder_value] = $placeholder;
        }
        $lists = array();
        for ($y=date('Y'); $y>=$this->lang('start_year'); $y--) {
            $lists[$y] = $y;
        }
        return $lists;
    }

    function date($timestamp, $format = null) {
        $timestamp = strtotime($timestamp);
        if (!$format) {
            $format = $this->lang('date_format');
        }
        if ($time = $timestamp) {
            return date($format, $time);
        } else {
            return null;
        }
    }

    function time($timestamp, $format = null) {
        $timestamp = strtotime($timestamp);
        if (!$format) {
            $format = $this->lang('time_format');
        }
        if ($time = $timestamp) {
            return date($format, $time);
        } else {
            return null;
        }
    }

    function datetime($timestamp) {
        $timestamp = strtotime($timestamp);
        if ($time = $timestamp) {
            return date($this->lang('datetime_format'), $time);
        } else {
            return null;
        }
    }

    function number($number, $decimals = null, $thousand_separator = null, $decimal_separator = null) {
        $number = $this->number_value($number);
        if (is_numeric($number)) {
            if (is_null($thousand_separator)) {
                $thousand_separator = $this->lang('thousand_separator');
            }
            if (is_null($decimal_separator)) {
                $decimal_separator = $this->lang('decimal_separator');
            }
            $parse = explode('.', $number);
	        if (is_null($decimals)) {
	            if (isset($parse[1])) {
		            $decimals = strlen($parse[1]);
	            }
            }
            $result = number_format($number, $decimals, $decimal_separator, $thousand_separator);
            return $result;
        } else {
            return 0;
        }
    }

    function number_value($str) {
        if (!is_numeric($str)) {
            $parse = explode($this->lang('decimal_separator'), $str);
            $result = str_replace($this->lang('thousand_separator'), "", $parse[0]);
            if (is_numeric($result)) {
                if (isset($parse[1])) {
                    $result .= '.' . $parse[1];
                }
                return $result;
            } else {
                return 0;
            }
        } else {
            return $str;
        }
    }

    function currency($str) {
        return $this->lang('currency').$this->number($str);
    }

    function human_date($timestamp, $format = null) {
        $timestamp = strtotime($timestamp);
        if ($time = $timestamp) {
            $y = date('Y', $time);
            $m = $this->lang('month_' . date('m', $time));
            $d = date('d', $time);
            $H = date('H', $time);
            $i = date('i', $time);
            $s = date('s', $time);
            if (!$format) {
                $humanDate = $this->lang('human_date_format');
            } else {
                $humanDate = $format;
            }
            $humanDate = str_replace(array('{Y}', '{m}', '{d}'), array($y, $m, $d), $humanDate);
            return $humanDate;
        } else {
            return null;
        }
    }

    function human_datetime($timestamp, $format = null) {
        $timestamp = strtotime($timestamp);
        if ($time = $timestamp) {
            $y = date('Y', $time);
            $m = $this->lang('month_'.date('m', $time));
            $d = date('d', $time);
            $H = date('H', $time);
            $i = date('i', $time);
            $s = date('s', $time);
            if (!$format) {
                $humanDate = $this->lang('human_datetime_format');
            } else {
                $humanDate = $format;
            }
            $humanDate = str_replace(array('{Y}', '{m}', '{d}', '{H}', '{i}', '{s}'), array($y, $m, $d, $H, $i, $s), $humanDate);
            return $humanDate;
        } else {
            return null;
        }
    }

    public function time_elapsed($datetime, $full = false) {
        $today = time();
        $createdday= strtotime($datetime);
        $datediff = abs($today - $createdday);
        $difftext="";
        $years = floor($datediff / (365*60*60*24));
        $months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $hours= floor($datediff/3600);
        $minutes= floor($datediff/60);
        $seconds= floor($datediff);
        //year checker
        if($difftext=="")
        {
        if($years>1)
        $difftext=$years." years ago";
        elseif($years==1)
        $difftext=$years." year ago";
        }
        //month checker
        if($difftext=="")
        {
        if($months>1)
        $difftext=$months." months ago";
        elseif($months==1)
        $difftext=$months." month ago";
        }
        //month checker
        if($difftext=="")
        {
        if($days>1)
        $difftext=$days." days ago";
        elseif($days==1)
        $difftext=$days." day ago";
        }
        //hour checker
        if($difftext=="")
        {
        if($hours>1)
        $difftext=$hours." hours ago";
        elseif($hours==1)
        $difftext=$hours." hour ago";
        }
        //minutes checker
        if($difftext=="")
        {
        if($minutes>1)
        $difftext=$minutes." minutes ago";
        elseif($minutes==1)
        $difftext=$minutes." minute ago";
        }
        //seconds checker
        if($difftext=="")
        {
        if($seconds>1)
        $difftext=$seconds." seconds ago";
        elseif($seconds==1)
        $difftext=$seconds." second ago";
        }
        return $difftext;
    }

    function boolean($boolean, $true = null, $false = null) {
        if ($boolean) {
            if (!$true) {
                $true = '<i class="fa fa-check text-success"></i>';
            }
            return $true;
        } else {
            if (!$false) {
                $false = '<i class="fa fa-times text-danger"></i>';
            }
            return $false;
        }
    }
}