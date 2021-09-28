<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'image', 'display', 'popular', 'content', 'order_item', 'child', 'parent_id', 'created_by', 'updated_by'];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by',
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class, 'category_products');
    }

    public function category_products()
    {
    	return $this->hasMany(CategoryProduct::class);
    }

    
}
