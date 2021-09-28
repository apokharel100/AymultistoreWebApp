<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
		    'order_id', 'product_id', 'product_title', 'product_price', 'color_id', 'color_name', 'color_code', 'size_id', 'size_name', 'quantity', 'sub_total', 'status', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function order()
	{
		return $this->belongsTo(Order::class, 'order_id');
	}
}
