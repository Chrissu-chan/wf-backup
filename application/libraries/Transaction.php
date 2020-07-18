<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction {

    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();              
    }

    public function start() {
        $this->CI->db->trans_start();
    }

    public function commit() {
        $this->CI->db->trans_commit();
    }

    public function rollback() {
        $this->CI->db->trans_rollback();
    }

    public function status() {
        $this->CI->db->trans_status();
    }

    public function complete() {
        if ($status = $this->CI->db->trans_status()) {
            $this->CI->db->trans_commit();
        } else {
            $this->CI->db->trans_rollback();            
        }
        return $status;
    }
}