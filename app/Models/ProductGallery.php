<?php
namespace App\Models;

class ProductGallery extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'product_gallery';
    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
