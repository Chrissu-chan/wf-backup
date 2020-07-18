<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_waktu_m extends BaseModel {

    protected $table = 'shift_waktu';
    protected $fillable = array('id_shift', 'urutan', 'shift_waktu', 'jam_mulai', 'jam_selesai');

    public function scope_kasir() {
        $this->db->where('id_shift', $this->config->item('shift_kasir'));
    }
}