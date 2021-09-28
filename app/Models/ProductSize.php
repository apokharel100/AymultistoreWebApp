<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
	public function product_color()
    {
    	return $this->belongsTo(ProductColor::class,'product_color_id');
    }

	public function size_details()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
    use HasFactory;

    protected $fillable = ['product_color_id', 'size_id', 'price', 'stock_count', 'sku'];
    protected $hidden = ['price', 'sku', 'created_at', 'updated_at'];
}
