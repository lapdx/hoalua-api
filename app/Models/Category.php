<?php
namespace App\Models;

class Category extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'category';
    public function attributeIds() {
        return $this->hasMany('App\Models\AttributeNCategory', 'category_id', 'id');
    }
}
