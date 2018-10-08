<?php
namespace App\Models;

class Inoutput extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'inoutput';
    public function inoutputItems() {
        return $this->hasMany('App\Models\InoutputItem', 'inoutput_id', 'id');
    }
}
