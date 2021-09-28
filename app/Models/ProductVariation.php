<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variation_name', 'slug', 'price', 'stock_count', 'sku', 'parent_id', 'child', 'created_by', 'updated_by' ];
}
