<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Color;
use App\Models\Size;
use App\Models\DiscountCoupon;
use App\Models\AppliedCoupon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Services\ModelHelper;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function place_order(StoreOrderRequest $request)
    {
    	$cart = session()->get('cart');
        $total_price = session()->get('total_price');
        $coupon_details = (array)session()->get('coupon_details');
        // dd(array('checkout_details' => $_POST, 'cart' => $cart, 'total_price' => $total_price, 'coupon_details' => $coupon_details));

        $billing_details = array(
            'billing_name' => $request->billing_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_street_address' => $request->billing_street_address,
            'billing_apt_ste_bldg' => $request->billing_apt_ste_bldg,
            'billing_city' => $request->billing_city,
            'billing_zip_code' => $request->billing_zip_code,
            'billing_country' => $request->billing_country,
            'billing_state' => $request->billing_state
        );

        if (!isset($request->different_address)) {

        	$shipping_details = array(
	            'shipping_name' => $request->billing_name,
	            'shipping_email' => $request->billing_email,
	            'shipping_phone' => $request->billing_phone,
	            'shipping_street_address' => $request->billing_street_address,
	            'shipping_apt_ste_bldg' => $request->billing_apt_ste_bldg,
	            'shipping_city' => $request->billing_city,
	            'shipping_zip_code' => $request->billing_zip_code,
	            'shipping_country' => $request->billing_country,
	            'shipping_state' => $request->billing_state
	        );

        }else{

	        $shipping_details = array(
	            'shipping_name' => $request->shipping_name,
	            'shipping_email' => $request->shipping_email,
	            'shipping_phone' => $request->shipping_phone,
	            'shipping_street_address' => $request->shipping_street_address,
	            'shipping_apt_ste_bldg' => $request->shipping_apt_ste_bldg,
	            'shipping_city' => $request->shipping_city,
	            'shipping_zip_code' => $request->shipping_zip_code,
	            'shipping_country' => $request->shipping_country,
	            'shipping_state' => $request->shipping_state
	        );
	    }

	    $order_status = 0;
        $payment_status = 0;

        $max_id = Order::max('id');
        $order_no = (date('Y')*10000)+$max_id+1;

        $orderArray = array('order_no' => $order_no,
                            'customer_id' => Auth::check() ? Auth::user()->id : 0,
                            'customer_name' => $request->billing_name,
                            'customer_email' => $request->billing_email,
                            'customer_phone' => $request->billing_phone,
                            'billing_details' => json_encode($billing_details),
                            'shipping_details' => json_encode($shipping_details),
                            'coupon_details' => json_encode($coupon_details),
                            'status' => $order_status,
                            'total_price' => $total_price,
                            'payment_status' => $payment_status,
                            'payment_method' => $request->payment_method,
                            'payment_id' => $order_no,
                            'order_json' => json_encode($cart),
                            'additional_message' => $request->additional_message,
                            'created_by' => "Customer"
                            );

        $order = Order::create($orderArray);

        if ($order) {

	        $orderedProductArray = array();

	        foreach ($cart as $key => $item) {
	        	
                $product = Product::where('id', $item["product_id"])->first();
	        	$product_price = $product->discounted_price != NULL || $product->discounted_price != 0 ? $product->discounted_price : $product->price;

	        	$productArray = array(  'product_id' => $item['product_id'],
	        	                        'product_title' => $item['product_title'],
	        	                        'product_price' => $product_price,
	        	                        'color_id' => $item['color_id'],
	        	                        'color_name' => $item['color_name'],
	        	                        'color_code' => $item['color_code'],
	        	                        'size_id' => $item['size_id'],
	        	                        'size_name' => $item['size_name'],
	        	                        'quantity' => $item['ordered_qty'],
	        	                        'sub_total' => $item['sub_total'],
	        	                        'status' => $order_status,
	        	                        'created_by' => "Customer"
	        	                    );

	        	array_push($orderedProductArray, $productArray);
	        }

	        $order->ordered_products()->createMany($orderedProductArray);

        	foreach ($cart as $item) {

            	$product = Product::where('id', $item["product_id"])->first();

            	switch ($product->variation_type) {

            	    case 0:
            	    	$product->stock_count = $product->stock_count - $item["ordered_qty"];
            			$product->save();
            	        
            	        break;

            	    case 1:
            	    	$product_color = ProductColor::where('id',$item['color_id'])->first();
            	    	$product_color->stock_count = $product_color->stock_count - $item["ordered_qty"];
            			$product_color->save();
            	        
            	        break;

            	    case 2:
            	        $product_size = ProductSize::where('id',$item['size_id'])->first();
            	    	$product_size->stock_count = $product_size->stock_count - $item["ordered_qty"];
            			$product_size->save();
            	        
            	        break;
            	    
            	    default:
            	        
            	        break;
            	}
            }

            if (Auth::check() && isset($coupon_details['id'])) {

        	    $user = User::find(Auth::user()->id);

        	    $appliedCouponArray = array(
        	    							'customer_id' => Auth::user()->id,
        	    							'order_id' => $order->id,
        	    							'coupon_id' => $coupon_details['id'],
        	    							'coupon_code' => $coupon_details['code'],
        	    							'coupon_title' => $coupon_details['title'],
        	    							'discount_amount' => $coupon_details['discount_amount']
        	    						);

        	    AppliedCoupon::create($appliedCouponArray);
        	}
        	// dd('success');
            session()->forget('cart');
            session()->forget('total_price');
            session()->forget('coupon_details');

            session()->save();

            return redirect()->route('home')->with('status','Your order has been placed Successfully!');

	    }else{
	    	return redirect()->back()->withInput()->with("error","Sorry! Order Couldn't be Created.");
	    }
    	
    }
}
