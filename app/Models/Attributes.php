<?php
namespace App\Models;

class Attributes extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'attributes';
    public function categories() {
        return $this->hasMany('App\Models\AttributeNCategory', 'attribute_id', 'id');
    }
}
