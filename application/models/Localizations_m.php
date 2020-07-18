<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Localizations_m extends BaseModel {

    protected $table = 'localizations';
    protected $primary_key = 'id';
    protected $fillable = array('country','timezone');
}