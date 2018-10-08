<?php
namespace App\Models;

class AttributeNCategory extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'attribute_n_category';
    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
