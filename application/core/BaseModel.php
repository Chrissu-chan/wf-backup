<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model {

    protected $table = '';
    protected $primary_key = 'id';
    protected $timestamps = false;
    protected $default = array();
    protected $authors = false;
    protected $author_m = 'users_m';
    protected $author_field = 'username';
    protected $creators = array();
    protected $updaters = array();

    public function __call($method, $params) {
        call_user_func_array(array($this->db, $method), $params);
        return $this;
    }

    public function primary_key() {
        return $this->primary_key;
    }

    public function view($view) {
        $this->{'view_'.$view}();
        return $this;
    }

    public function scope($scope) {
        if (is_array($scope)) {
            foreach ($scope as $method) {
                $this->{'scope_'.$method}();
            }
        } else {
            $this->{'scope_'.$scope}();
        }
        return $this;
    }

    public function get() {
        $result = $this->db->get($this->table)->result();
        return $result;
    }

    public function find($id) {
        $result = $this->db->where($this->table.'.'.$this->primary_key, $id)
            ->get($this->table)
            ->row();
        return $result;
    }

    public function find_or_fail($id) {
        $model = $this->find($id);
        if (!$model) {
            $this->exceptions->model_not_found($this);
        }
        return $model;
    }

    public function first() {
        $result = $this->db->limit(1)
            ->get($this->table)
            ->row();
        return $result;
    }

    public function first_or_fail() {
        $model = $this->first();
        if (!$model) {
            $this->exceptions->model_not_found($this);
        }
        return $model;
    }

    public function count_all_results() {
        return $this->db->count_all_results($this->table);
    }

    public function insert($record) {
        $record = $this->fill($record);
        if ($this->authors) {
            $record['created_by'] = $this->auth->{$this->auth->username_field};
        }
        if ($this->timestamps) {
            $record['created_at'] = date('Y-m-d H:i:s');
        }
        $model = $this->db->insert($this->table, $record);
        if ($model) {
            return $this->find_insert_id();
        }
        return $model;
    }

    public function insert_id() {
        return $this->db->insert_id();
    }

    public function find_insert_id() {
        return $this->find($this->insert_id());
    }

    public function insert_batch($records) {
        foreach ($records as $key => $record) {
            $records[$key] = $this->fill($record);
            if ($this->authors) {
                $records[$key]['created_by'] = $this->auth->{$this->auth->username_field};
            }
            if ($this->timestamps) {
                $records[$key]['created_at'] = date('Y-m-d H:i:s');
            }
        }
        return $this->db->insert_batch($this->table, $records);
    }

    public function update($id, $record = null) {
        if ($record) {
            $result = $this->find_or_fail($id);
            $this->db->where($this->table.'.'.$this->primary_key, $result->{$this->primary_key});
        } else {
            $record = $id;
        }
        $record = $this->fill($record);
        if ($this->authors) {
            $record['updated_by'] = $this->auth->{$this->auth->username_field};
        }
        if ($this->timestamps) {
            $record['updated_at'] = date('Y-m-d H:i:s');
        }
        return $this->db->update($this->table, $record);
    }

    public function delete($id = null) {
        if ($id) {
            $result = $this->find_or_fail($id);
            $this->db->where($this->table.'.'.$this->primary_key, $result->{$this->primary_key});
        }
        return $this->db->delete($this->table);
    }

    public function enum($name, $value = null) {
        $enum = $this->{'enum_'.$name}();
        if (!is_null($value)) {
            if (isset($enum[$value])) {
                return $enum[$value];
            } else {
                return null;
            }
        }
        return $enum;
    }

    protected function fill($record = array()) {
        $data = array();
        foreach ($this->default as $field => $value) {
            $data[$field] = $value;
        }
        foreach ($this->fillable as $field) {
            if (array_key_exists($field, $record)) {
                if ($record[$field] !== '' && !is_null($record[$field])) {
                    $data[$field] = $this->set_record($field, $record[$field]);
                } else {
                    $data[$field] = null;
                }
            }
        }
        return $data;
    }

    protected function set_record($field, $value) {
        if (method_exists($this, 'set_'.$field)) {
            return $this->{'set_'.$field}($value);
        } else {
            return $value;
        }
    }

    public function author() {
        $this->load->model($this->author_m);
        $this->author_m = $this->{$this->author_m};
        $rs = $this->author_m->where($this->author_field.' IN (SELECT DISTINCT created_by FROM '.$this->table.')', null, false)
        ->get();
        foreach ($rs as $r) {
            $this->creators[$r->{$this->author_field}] = $r;
        }
        $rs = $this->author_m->where($this->author_field.' IN (SELECT DISTINCT updated_by FROM '.$this->table.')', null, false)
        ->get();
        foreach ($rs as $r) {
            $this->updaters[$r->{$this->author_field}] = $r;
        }
        return $this;
    }

    protected function set_author($data) {
        if (!$data) {
            return $data;
        }
        if (is_array($data)) {
            foreach ($data as $i => $row) {
                $row = $this->set_author($row);
                $data[$i] = $row;
            }
            return $data;
        } else {
            $data->creator = isset($this->creators[$data->created_by]) ? $this->creators[$data->created_by] : null;
            $data->updater = isset($this->updaters[$data->updated_by]) ? $this->creators[$data->updated_by] : null;
            return $data;
        }
    }
}