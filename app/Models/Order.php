<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
		    'order_no', 'customer_id', 'customer_name', 'customer_email', 'customer_phone', 'billing_details', 'shipping_details', 'coupon_details', 'status', 'delivery_charge', 'total_price', 'payment_status', 'payment_method', 'delivery_method', 'payment_id', 'paid_date', 'order_json', 'additional_message', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'customer_id', 'created_by', 'updated_by','created_at', 'updated_at', 'order_json', 'coupon_details'
    ];

    public function ordered_products()
    {
    	return $this->hasMany(OrderedProduct::class, 'order_id');
    }

    public static function order_status()
    {
        $order_status = [   '0' => ['Pending', 'warning'],
                            '1' => ['On Process', 'primary'],
                            '2' => ['Delivered', 'success'],
                            '3' => ['Cancelled', 'danger']
                        ];

        return $order_status;
    }

    public function applied_coupon()
    {
        return $this->hasOne(AppliedCoupon::class);
    }    
}
