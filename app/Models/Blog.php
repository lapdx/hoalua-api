<?php
namespace App\Models;

class Blog extends \Megaads\Apify\Models\BaseModel {
    protected $table = 'blog';
    public $messages = [
        'title.required' => 'Tên không được để trống',
        'slug.unique' => 'Slug không được trùng',
        'slug.required' => 'Slug không được để trống',
    ];
    
    public function getRules($id) {
        return [
            'title' => 'required',
            'slug' => 'required|unique:blog,slug,' . $id . ',id'
        ];
    }

}
