<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_m extends BaseModel {

    protected $table = 'shift';
    protected $fillable = array('shift', 'jumlah_shift');
}