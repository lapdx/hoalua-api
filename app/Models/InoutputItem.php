<?php
namespace App\Models;

class InoutputItem extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'inoutput_item';
    public function inoutput() {
        return $this->belongsTo('App\Models\Inoutput', 'inoutput_id');
    }
}
