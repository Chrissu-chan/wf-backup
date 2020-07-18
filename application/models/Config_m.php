<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_m extends BaseModel {

    protected $table = 'config';
    protected $primary_key = 'id';
    protected $fillable = array('application_id','config','key','value');
}