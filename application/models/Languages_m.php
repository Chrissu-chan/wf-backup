<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Languages_m extends BaseModel {

    protected $table = 'languages';
    protected $primary_key = 'id';
    protected $fillable = array('id_localization','type','key','message');

    public function view_languages() {
        $this->db->select('languages.*, localizations.country')
            ->join('localizations', 'localizations.id = languages.id_localization');
    }
}