<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'slug', 'brand_id', 'sku', 'image', 'price', 'discounted_price', 'display', 'featured', 'variation_type', 'short_description', 'long_description', 'stock_status', 'stock_count', 'unit_name', 'tags', 'views', 'order_item', 'created_by', 'updated_by', 'deleted_at'];

    protected $hidden = ['created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at'];

    public function product_variations()
    {
    	return $this->hasMany(ProductVariation::class); // not used
    }

    public function product_colors()
    {
    	return $this->hasMany(ProductColor::class);
    }

    public function product_sizes()
    {
        return $this->hasManyThrough(ProductSize::class, ProductColor::class, 'product_id', 'product_color_id');
    }

    public function categories()
    {
    	return $this->belongsToMany(Category::class, 'category_products');
    }

    public function category_products()
    {
        return $this->hasMany(CategoryProduct::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
