<?php
namespace App\Models;

class ProductNAttributeValue extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'product_n_attribute_value';
    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
