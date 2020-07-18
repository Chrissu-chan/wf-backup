<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tree {

    protected $CI;

    protected $parent_id = 'parent_id';

    protected $tree_id = 'tree_id';

    protected $tree_id_digit = '3';

    protected $model;

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function resource($model) {
        $this->model = $model;
    }

    public function generate($parent_id = null) {
        $digit = str_repeat('0', $this->tree_id_digit);
        $last_id = $this->model->select_max($this->tree_id)
        ->where($this->parent_id, $parent_id)
        ->get($this->table)
        ->row()
        ->{$this->tree_id};
        if ($parent_id) {
            $prefix = $this->find($parent_id)->tree_id;
            if ($last_id) {
                $counter = $last_id+1;
                return $prefix.substr($digit.$counter, -strlen($digit));
            } else {
                return $prefix.substr($digit.'1', -strlen($digit));
            }
        } else {
            if ($last_id) {
                $counter = $last_id+1;
                return substr($digit.$counter, -strlen($digit));
            } else {
                return substr($digit.'1', -strlen($digit));
            }
        }
    }
}