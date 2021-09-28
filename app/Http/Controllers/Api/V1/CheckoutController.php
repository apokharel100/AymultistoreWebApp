<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Services\ModelHelper;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\SiteSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\DiscountCoupon;
use App\Models\AppliedCoupon;

class CheckoutController extends Controller
{

    public function apply_coupon(Request $request){

		$cart_total_price = $request->sub_total_price;

        $code = strtolower($request->code);
        $coupon = DiscountCoupon::where('code',$code)->first();
        
        $todayDate = date('Y-m-d h:i:s');

        if ($coupon) {

            if ($cart_total_price < $coupon->min_spend) {

            	return response()->json(["status" => 422, "message" => 'Minimum Spend', 'data' => ['errors' => ["You must spend Nrs.".$coupon->min_spend." to apply this Code!"]]]);

            }


            $user = Auth::user();
            $alreadyApplied = AppliedCoupon::where([['customer_id', $user->id],['coupon_id', $coupon->id]])->exists();

            if ($alreadyApplied) {
                
                return response()->json(["status" => 422, "message" => 'Coupon Already Applied', 'data' => ['errors' => ["You have applied this Code already!"]]]);
                
            }
            // exit();

            if ($todayDate >= $coupon->start_date && $todayDate <= $coupon->expire_date ) {

                $discount_amount = $cart_total_price * ($coupon->discount_percentage/100);

                if ($discount_amount > $coupon->max_discount) {

                    $discount_amount = $coupon->max_discount;
                }

                $cart_total_price = $cart_total_price - $discount_amount;
                $couponDiscount = $coupon->discount_percentage."% off (upto Nrs.".$coupon->max_discount.")";

                $coupon_details = array("id" => $coupon->id, "code" => $coupon->code, "title" => $coupon->name." - ".$couponDiscount, "discount_amount" => round($discount_amount, 2), "total_price" => round($cart_total_price, 2));

                return response()->json(["status" => 200, 'message' =>'Coupon Applied Successfully!', "data" => array("coupon_details" => $coupon_details)]);

            }else{

                return response()->json(["status" => 422, "message" => 'Expired Coupon', 'data' => ['errors' => ["Coupon has been already Expired!"]]]);

            }
        }else{
            return response()->json(["status" => 422, "message" => 'Invalid Coupon Code', 'data' => ['errors' => ["Please Enter Valid Coupon Code!"]]]);
        }

        // echo $cart_total_price;
        // exit();
        session()->put("total_price", $cart_total_price);
        session()->save();
        
        echo json_encode($data);   

    }

    public function checkout(Request $request)
	{
		
		// return response()->json(Auth::check());
		$validationArray =  [
								'customer_id' => 'required|integer',

								'products' => 'required|array',
								'products.*.product_id' => 'required|integer',
								'products.*.color_id' => 'required|integer',
								'products.*.size_id' => 'required|integer',
								'products.*.unit_price' => 'required',
								'products.*.quantity' => 'required|integer',
								
								'ship_to_different_address' => 'required|boolean',
					            
					            'billing_address.billing_name' => 'required|max:255',
					            'billing_address.billing_email' => 'required|email|max:255',
					            'billing_address.billing_phone' => 'required|max:255',
					            'billing_address.billing_street_address' => 'required|max:255',
					            'billing_address.billing_city' => 'required|max:255',
					            'billing_address.billing_zip_code' => 'required|max:255',
					            'billing_address.billing_country' => 'required|max:255',
					            'billing_address.billing_state' => 'required|max:255',

					            'shipping_address.shipping_name' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_email' => 'required_if:ship_to_different_address,1|email|max:255',
					            'shipping_address.shipping_phone' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_street_address' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_city' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_zip_code' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_country' => 'required_if:ship_to_different_address,1|max:255',
					            'shipping_address.shipping_state' => 'required_if:ship_to_different_address,1|max:255',

					            'coupon_details.id' => 'required_with:coupon_details|integer',
					            'coupon_details.code' => 'required_with:coupon_details',
					            'coupon_details.title' => 'required_with:coupon_details',
					            'coupon_details.discount_amount' => 'required_with:coupon_details',

					            'payment_details.payment_method' => 'required',
					            'payment_details.grand_total' => 'required'
					        ];
	

    	
        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()){

            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        
        /*--------- Cart Product Functionalities Start ----------------*/

        $cart = array();

        foreach ($request->products as $key => $cartItem) {

        	$product = Product::find($cartItem['product_id']);
        	if ($product) {
		        $product_price = $product->discounted_price != NULL || $product->discounted_price != 0 ? $product->discounted_price : $product->price;
	            $cart_item_sub_total = $product_price * $cartItem['quantity'];

	            $product_color = ProductColor::where("id", $cartItem['color_id'])->first();
	            $product_size = ProductSize::where("id", $cartItem['size_id'])->first();

				$cartProduct = array(
										'product_id' => $cartItem['product_id'],
										'product_title' => $product->title,
										'color_id' => (int)$cartItem['color_id'], 
										'color_name' => isset($product_color) ? $product_color->color_details->title : NULL,
										'color_code' => isset($product_color) ? $product_color->color_details->code : NULL,
										'size_id' => (int)$cartItem['size_id'], 
										'size_name' => isset($product_size) ? $product_size->size_details->title : NULL,
										'ordered_qty' =>(int)$cartItem['quantity'],
										'sub_total' => (float)$cart_item_sub_total
									);

				array_push($cart, $cartProduct);
			}
        }

        // return response($cart);

        /*--------- Cart Product Functionalities End ----------------*/

        
        $billing_details = json_encode($request->billing_address);

        if ($request->ship_to_different_address == 0) {
        	$shipping_address['shipping_name'] = $request->billing_address['billing_name'];
        	$shipping_address['shipping_email'] = $request->billing_address['billing_email'];
        	$shipping_address['shipping_phone'] = $request->billing_address['billing_phone'];
        	$shipping_address['shipping_street_address'] = $request->billing_address['billing_street_address'];
        	$shipping_address['shipping_apt_ste_bldg'] = $request->billing_address['billing_apt_ste_bldg'];
        	$shipping_address['shipping_city'] = $request->billing_address['billing_city'];
        	$shipping_address['shipping_zip_code'] = $request->billing_address['billing_zip_code'];
        	$shipping_address['shipping_country'] = $request->billing_address['billing_country'];
        	$shipping_address['shipping_state'] = $request->billing_address['billing_state'];
        }else{
        	$shipping_address = $request->shipping_address;
        }

        $shipping_details = json_encode($shipping_address);

        if ($request->coupon_details) {
        	$coupon_details = $request->coupon_details;
        }else{
        	$coupon_details = array();
        }
        $order_status = 0;
        $payment_status = 0;
        
        $max_id = DB::table('orders')->max('id');
        $generated_order_no = (date('Y')*10000)+$max_id+1;

        $orderArray = array('order_no' => $generated_order_no,
                            'customer_id' => $request->customer_id,
                            'customer_name' => $request->billing_address['billing_name'],
                            'customer_email' => $request->billing_address['billing_email'],
                            'customer_phone' => $request->billing_address['billing_phone'],
                            'billing_details' => $billing_details,
                            'shipping_details' => $shipping_details,
                            'coupon_details' => json_encode($coupon_details),
                            'status' => $order_status,
                            'total_price' => $request->payment_details['grand_total'],
                            'payment_method' => $request->payment_details['payment_method'],
                            'payment_status' =>isset($request->payment_details['payment_unique_id']) && $request->payment_details['payment_unique_id'] != NULL ? 1 : 0,
                            'payment_id' => isset($request->payment_details['payment_unique_id']) ? $request->payment_details['payment_unique_id'] : $generated_order_no,
                            'paid_date' => isset($request->payment_details['payment_unique_id']) ? date('Y-m-d h:i:s') : NULL,
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

            if ($request->customer_id != 0 && isset($coupon_details['id'])) {

        	    $appliedCouponArray = array(
        	    							'customer_id' => $request->customer_id,
        	    							'order_id' => $order->id,
        	    							'coupon_id' => $coupon_details['id'],
        	    							'coupon_code' => $coupon_details['code'],
        	    							'coupon_title' => $coupon_details['title'],
        	    							'discount_amount' => $coupon_details['discount_amount']
        	    						);

        	    AppliedCoupon::create($appliedCouponArray);
        	}

        	$order->billing_details = json_decode($order->billing_details);
        	$order->shipping_details = json_decode($order->shipping_details);
        	$order->ordered_products = $order->ordered_products;

            return response()->json(["status" => 200, 'message' =>'order_placed', "data" => array("order" => $order)]);

        }else{
        	return response()->json(["status" => 500, "message" => 'Database Error']);
        }
        
	}


}
