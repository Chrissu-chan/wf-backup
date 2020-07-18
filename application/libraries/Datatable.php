<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable {

    protected $CI;

    protected $model;

    protected $calls = array();

    protected $select;

    protected $join = array();

    protected $view;

    protected $where = array();

    protected $having = array();

    protected $scopes = array();

    protected $add_columns = array();

    protected $edit_columns = array();

    protected $group_by;

    protected $order_by = array();

    protected $limit = array();

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function __call($method, $params = array()) {
        $this->calls[] = array(
            'method' => $method,
            'params' => $params
        );
        return $this;
    }

    public function resource($model, $select = NULL) {
        $this->model = $model;
        if ($select) {
            $this->select = $select;
        }
        return $this;
    }

    public function view($view, $params = array()) {
        $this->view = $view;
        return $this;
    }

    public function join($table, $cond, $type = '', $escape = null) {
        $this->join[] = array(
            'table' => $table,
            'cond' => $cond,
            'type' => $type,
            'escape' => $escape
        );
        return $this;
    }

    public function where($key, $value = null, $escape = null) {
        $this->where[] = array(
            'type' => 'where',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
        return $this;
    }

    public function where_in($key, $value = null, $escape = null) {
        $this->where[] = array(
            'type' => 'where_in',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
        return $this;
    }

    public function or_where($key, $value = null, $escape = null) {
        $this->where[] = array(
            'type' => 'or_where',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
        return $this;
    }

    public function or_where_in($key, $value = null, $escape = null) {
        $this->where[] = array(
            'type' => 'or_where_in',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
        return $this;
    }

    public function like($key, $value = null, $side = 'both', $escape = null) {
        $this->where[] = array(
            'type' => 'like',
            'key' => $key,
            'value' => $value,
            'side' => $side,
            'escape' => $escape
        );
        return $this;
    }

    public function or_like($key, $value = null, $side = 'both', $escape = null) {
        $this->where[] = array(
            'type' => 'or_like',
            'key' => $key,
            'value' => $value,
            'side' => $side,
            'escape' => $escape
        );
        return $this;
    }

    public function scope($scope) {
        if (is_array($scope)) {
            foreach ($scope as $method) {
                $this->scopes[] = $method;
            }
        } else {
            $this->scopes[] = $scope;
        }
        return $this;
    }

    public function author() {
        $this->model->author();
        return $this;
    }

    public function filter($filter) {
        $filter($this);
        return $this;
    }

    public function having($key, $value = NULL, $escape = NULL) {
        $this->having[] = array(
            'type' => 'having',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
    }

    public function or_having($key, $value = NULL, $escape = NULL) {
        $this->having[] = array(
            'type' => 'or_having',
            'key' => $key,
            'value' => $value,
            'escape' => $escape
        );
    }

    public function group_by($field){
        $this->group_by = $field;
        return $this;
    }

    public function order_by($order_by, $direction = '', $escape = NULL) {
        $this->order_by[] = array(
            'order_by' => $order_by,
            'direction' => $direction,
            'escape' => $escape
        );
        return $this;
    }

    public function limit($length, $start = 0) {
        $this->limit = array($length, $start);
    }

    public function add_column($name, $value = null) {
        $this->add_columns[$name] = $value;
        return $this;
    }

    public function add_action($template = '{view} {edit} {delete}', $actions = array()) {
        $this->add_column('_action', function($model) use ($template, $actions) {
            $base_actions = array(
                'view' => $this->CI->action->button('view', 'class="btn btn-info btn-sm" onclick="view(\''.$model->{$this->model->primary_key()}.'\')"'),
                'edit' => $this->CI->action->button('edit', 'class="btn btn-warning btn-sm" onclick="edit(\''.$model->{$this->model->primary_key()}.'\')"'),
                'delete' => $this->CI->action->button('delete', 'class="btn btn-danger btn-sm" onclick="remove(\''.$model->{$this->model->primary_key()}.'\')"')
            );
            $actions = array_merge($base_actions, $actions);
            foreach ($actions as $name => $action) {
                if (is_callable($action)) {
                    $actions[$name] = $action($model);
                } else {
                    $actions[$name] = $action;
                }
            }
            return $this->CI->action->render($template, $actions);
        });
        return $this;
    }

    public function edit_column($name, $value) {
        $this->edit_columns[$name] = $value;
        return $this;
    }

    protected function build_view() {
        if ($this->view) {
            $this->model->view($this->view);
        } else {
            if ($this->select) {
                $this->model->select($this->select);
            }
        }
    }

    protected function build_join() {
        foreach ($this->join as $join) {
            $this->model->join($join['table'], $join['cond'], $join['type'], $join['escape']);
        }
    }

    protected function build_where() {
        if (count($this->where) <> 0) {
            $this->model->group_start();
        }
        foreach ($this->where as $where) {
            switch ($where['type']) {
                case 'where':
                    $this->model->where($where['key'], $where['value'], $where['escape']);
                    break;
                case 'where_in':
                    $this->model->where_in($where['key'], $where['value'], $where['escape']);
                    break;
                case 'or_where':
                    $this->model->or_where($where['key'], $where['value'], $where['escape']);
                    break;
                case 'or_where_in':
                    $this->model->or_where_in($where['key'], $where['value'], $where['escape']);
                    break;
                case 'like':
                    $this->model->like($where['key'], $where['value'], $where['side'], $where['escape']);
                    break;
                case 'or_like':
                    $this->model->or_like($where['key'], $where['value'], $where['side'], $where['escape']);
                    break;
            }
        }
        if (count($this->where) <> 0) {
            $this->model->group_end();
        }
    }

    public function build_having() {
        foreach ($this->having as $having) {
            switch ($having['type']) {
                case 'having':
                    $this->model->having($having['key'], $having['value'], $having['escape']);
                    break;
                case 'or_having':
                    $this->model->od_having($having['key'], $having['value'], $having['escape']);
                    break;
            }
        }
    }

    protected function build_search() {
        $params = $this->CI->input->get();
        if (isset($params['search'])) {
            if ($params['search']['value']) {
                foreach ($params['columns'] as $column) {
                    if ($column['searchable'] == 'true') {
                        if ($column['name'] <> '') {
                            $searchable[] = $column['name'];
                        } else {
                            $searchable[] = $column['data'];
                        }
                    }
                }
                if (count($searchable) <> 0) {
                    $this->model->group_start();
                    $this->model->like($searchable[0], $params['search']['value']);
                    unset($searchable[0]);
                    foreach ($searchable as $column) {
                        $this->model->or_like($column, $params['search']['value']);
                    }
                    $this->model->group_end();
                }
            }
        }
    }

    protected function build_order() {
        $params = $this->CI->input->get();
        if (isset($params['order'])) {
            foreach ($params['order'] as $order) {
                if ($params['columns'][$order['column']]['name'] <> '') {
                    $column = $params['columns'][$order['column']]['name'];
                } else {
                    $column = $params['columns'][$order['column']]['data'];
                }
                $this->model->order_by($column, $order['dir']);
            }
        } else {
            foreach ($this->order_by as $order_by) {
                $this->model->order_by($order_by['order_by'], $order_by['direction'], $order_by['escape']);
            }
        }
    }

    protected function build_scopes() {
        $this->model->scope($this->scopes);
    }

    protected function build_limit() {
        $params = $this->CI->input->get();
        if (isset($params['length'])) {
            if ($params['length'] <> -1) {
                $this->model->limit($params['length'], $params['start']);
            }
        } else {
            $this->model->limit($this->limit[0], $this->limit[1]);
        }
    }

    protected function build_calls() {
        foreach ($this->calls as $call) {
            call_user_func_array(array($this->model, $call['method']), $call['params']);
        }
    }

    public function generate($return = false) {
        $params = $this->CI->input->get();

        $this->build_calls();
        $this->build_view();
        $this->build_join();
        $this->build_where();
        $this->build_scopes();
        $this->build_search();
        $this->build_order();
        $this->build_having();
        if($this->group_by){
            $this->model->group_by($this->group_by);
        }
        $this->build_limit();
        $result = $this->model->get();
        $data = $result;
        foreach ($data as $key => $row) {
            if (count($this->add_columns) <> 0) {
                foreach ($this->add_columns as $add_column => $value) {
                    if (!isset($row->add_column)) {
                        if (is_callable($value)) {
                            $row->$add_column = $value($row);
                        } else {
                            $row->$add_column = $value;
                        }
                    } else {
                        show_error('Column already exist');
                    }
                }
            }

            if (count($this->edit_columns) <> 0) {
                foreach ($this->edit_columns as $edit_column => $value) {
                    $row->original[$edit_column] = $row->$edit_column;
                    if (is_callable($value)) {
                        $row->$edit_column = $value($row);
                    } else {
                        $row->$edit_column = $value;
                    }
                }
            }
            $data[$key] = $row;
        }

        $this->build_calls();
        $this->build_view();
        $this->build_join();
        $this->build_where();
        $this->build_scopes();
        $this->build_order();
        $this->build_having();
        if($this->group_by) {
            $this->model->group_by($this->group_by);
        }
        $record_total = $this->model->count_all_results();

        $this->build_calls();
        $this->build_view();
        $this->build_join();
        $this->build_where();
        $this->build_scopes();
        $this->build_search();
        $this->build_order();
        $this->build_having();
        if($this->group_by) {
            $this->model->group_by($this->group_by);
        }
        $record_filtered = $this->model->count_all_results();

        $response = array(
            'draw' => $params['draw'],
            'recordsTotal' => $record_total,
            'recordsFiltered' => $record_filtered,
            'data' => $data
        );
        if ($return) {
            return $response;
        } else {
            $this->CI->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }
}