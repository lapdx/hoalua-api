<?php
namespace App\Models;

class Product extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'product';
    public function images() {
        return $this->hasMany('App\Models\ProductGallery', 'product_id', 'id');
    }
    public function attributeIds() {
        return $this->hasMany('App\Models\ProductNAttributeValue', 'product_id', 'id');
    }
    public $messages = [
        'title.required' => 'Tên không được để trống',
        'slug.unique' => 'Slug không được trùng',
        'slug.required' => 'Slug không được để trống',
        'price.required' => 'Giá NCC không được để trống',
        'sale_price.required' => 'Giá bán không được để trống',
        'category_id.required' => 'Danh mục không được để trống',
        'manufacturer_id.required' => 'Hãng không được để trống',
        'content.required' => 'Mô tả không được để trống',
    ];
    
    public function getRules($id) {
        return [
            'title' => 'required',
            'price' => 'required',
            'sale_price' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'manufacturer_id' => 'required',
            'slug' => 'required|unique:blog,slug,' . $id . ',id'
        ];
    }
}
