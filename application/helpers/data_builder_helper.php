<?php

function lists($data, $value, $label, $placeholder = null, $placeholder_value = '') {
    $CI = &get_instance();
    $lists = array();
    if ($placeholder) {
        if ($placeholder === TRUE) {
            $placeholder = $CI->localization->lang('select_placeholder');
        }
        $lists[$placeholder_value] = $CI->localization->lang($placeholder);
    }
    if (is_callable($data)) {
        $data = $data();
    }
    foreach ($data as $row) {
        $row = (Object) $row;
        if (is_callable($value)) {
            $value_data = $value($row);
        } else {            
            $value_data = $row->$value;
        }

        if (is_callable($label)) {
            $label_data = $label($row);
        } else {
            $label_data = $row->$label;
        }
        $lists[$value_data] = $label_data;
    }
    return $lists;
}

function tree($data, $id, $parent, $start = 0, $nested = false, $nested_index = 'childs')   {    
    $tree = array();
    $result = array();
    foreach ($data as $row) {
        $tree[$row->$parent][] = (Object) $row;
    }
    if (isset($tree[$start])) {
        if ($nested) {
            $result = set_tree_nested($tree, $tree[$start], $id, $parent, $nested_index);
        } else {
            $result = set_tree($tree, $tree[$start], $id, $parent);
        }
    }
    return $result;
}

function set_tree($data, $parent_data, $id, $parent, $level = 0) {
    $result = array();  
    foreach ($parent_data as $key => $row) {
        $row->tree_level = $level;
        $result[] = $row;               
        if (isset($data[$row->$id])) {              
            $result = array_merge($result, set_tree($data, $data[$row->$id], $id, $parent_data, $level + 1));
            unset($data[$row->$id]);
        }
    }   
    return $result;    
}

function set_tree_nested($data, $parent_data, $id, $parent, $nested_index, $level = 0) {
    $result = array();  
    foreach ($parent_data as $key => $row) {
        $row->tree_level = $level;                
        if (isset($data[$row->$id])) {              
            $row->$nested_index = set_tree_nested($data, $data[$row->$id], $id, $parent_data, $nested_index, $level + 1);
            unset($data[$row->$id]);
        }
        $result[] = $row;     
    }   
    return $result;    
}

function tree_lists($data, $id, $parent, $start = 0, $value, $label, $placeholder = null, $placeholder_value = '') {
    $data = tree($data, $id, $parent, $start);
    return lists($data, $value, function($row) use ($label) {
        if (is_callable($label)) {
            $label_data = $label($row);
        } else {
            $label_data = $row->$label;
        }
        return str_repeat('&nbsp;&nbsp;&nbsp;', $row->tree_level).$label_data;
    }, $placeholder, $placeholder_value);
}