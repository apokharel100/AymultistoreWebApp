<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'display', 'start_date', 'expire_date', 'start_time', 'expire_time', 'min_spend', 'max_discount', 'discount_percentage', 'created_by', 'updated_by'];

    protected $hidden = ['created_by','updated_by','created_at','updated_at'];
}
