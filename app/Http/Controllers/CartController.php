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

class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {
    	$cart = (array)session()->get("cart");
    	$cart_total_price = (float)session()->get("total_price");

    	if (session()->get('coupon_details')) {
    		session()->forget('coupon_details');
    		session()->save();
    	}
    	

    	if ($request->product_id) {

            $product = Product::where("id", $request->product_id)->first();
            $product_price = $product->discounted_price != NULL || $product->discounted_price != 0 ? $product->discounted_price : $product->price;
            $cart_item_sub_total = $product_price * $request->ordered_qty;

            $product_color = ProductColor::where("id", $request->color_id)->first();
            $product_size = ProductSize::where("id", $request->size_id)->first();

            switch ($product->variation_type) {
                case 0:
                	
                    $max_order_qty = $product->stock_count;

                    $cart_product_ids = array_column($cart, 'product_id');
                    $cart_key = (int)array_search($request->product_id, $cart_product_ids);
                    break;

                case 1:
                	
                    $max_order_qty = $product_color->stock_count;

                    $cart_color_ids = array_column($cart, 'color_id');
                    $cart_key = (int)array_search($request->color_id, $cart_color_ids);
                    break;

                case 2:
                	
                    $max_order_qty = $product_size->stock_count;

                    $cart_size_ids = array_column($cart, 'size_id');
                    $cart_key = (int)array_search($request->size_id, $cart_size_ids);
                    break;
                
                default:
                    $max_order_qty = 1;
                    break;
            }

            // var_dump($cart_key);
            // exit();
            $in_cart = count($cart) == 0 ? 0 : $cart[$cart_key]['ordered_qty'];
            $availableStock = $max_order_qty - $in_cart;

            if ($max_order_qty >= $request->ordered_qty) {
            	
            	$cart_item_count = count($cart);

            	if ($cart_item_count == 0) {
            		$cartItem = array('product_id' => (int)$request->product_id, 
	                                  'product_title' => addslashes($product->title),
	                                  'color_id' => (int)$request->color_id, 
	                                  'color_name' => isset($product_color) ? $product_color->color_details->title : NULL,
	                                  'color_code' => isset($product_color) ? $product_color->color_details->code : NULL,
	                                  'size_id' => (int)$request->size_id, 
	                                  'size_name' => isset($product_size) ? $product_size->size_details->title : NULL,
	    							  'ordered_qty' =>(int)$request->ordered_qty,
	    							  'sub_total' => (float)$cart_item_sub_total
	    							);
            		$cart_total_price = $cart_total_price + $cart_item_sub_total;

    				session()->push("cart", $cartItem);
    				session()->put("total_price", $cart_total_price);

    				$data = array('status'=> 'success', 'total_qty'=>count(session()->get('cart')) , 'total_price' => $cart_total_price);
    				
    				echo json_encode($data);

            	}else{

            		/*if (isset($cart_key)) {

            			$ordered_qty_temp = (int)$cart[$cart_key]['ordered_qty'] + (int)$request->ordered_qty;
            			echo $ordered_qty_temp;
            			exit();
            			if ($ordered_qty_temp > $max_order_qty) {

            			    $data = array('status'=> 'stockerror', 'stock'=> $availableStock, 'in_cart' => $in_cart);
            			    echo json_encode($data);
            			    exit();

            			}else{

            			    $cart[$cart_key]["ordered_qty"] = $ordered_qty_temp;
            			    $cart[$cart_key]['sub_total'] = (float)$cart[$cart_key]['sub_total'] + (float)$cart_item_sub_total;

            			    $cart_total_price = $cart_total_price + $cart_item_sub_total;

            			    session()->put("cart", $cart);
            			    session()->put("total_price", $cart_total_price);
            			    session()->save();

            			    $data = array('status'=> 'success', 'total_qty'=>count($cart) , 'total_price' => $cart_total_price);
            			    echo json_encode($data);
            			    exit();
            			}
            		}else{

            			$cartItem = array('product_id' => (int)$request->product_id, 
            				'product_title' => addslashes($product->title),
            				'color_id' => (int)$request->color_id, 
            				'color_name' => isset($product_color) ? $product_color->name : NULL,
            				'size_id' => (int)$request->size_id, 
            				'size_name' => isset($product_size) ? $product_size->name : NULL,
            				'ordered_qty' =>(int)$request->ordered_qty,
            				'sub_total' => (float)$cart_item_sub_total,
            			);
            			$cart_total_price = $cart_total_price + $cart_item_sub_total;

            			session()->push("cart", $cartItem);
            			session()->put("total_price", $cart_total_price);

            			$data = array('status'=> 'success', 'total_qty'=>count(session()->get('cart')) , 'total_price' => $cart_total_price);

            			echo json_encode($data);
            		}*/

            		for($i=0; $i < $cart_item_count; $i++){

    					if ($request->product_id == $cart[$i]['product_id'] && $request->color_id == $cart[$i]['color_id'] && $request->size_id == $cart[$i]['size_id']) {
    						
    						$ordered_qty_temp = (int)$cart[$i]['ordered_qty'] + (int)$request->ordered_qty;
    						
                            if ($ordered_qty_temp > $max_order_qty) {

                                $data = array('status'=> 'stockerror', 'stock'=> $availableStock, 'in_cart' => $in_cart);
                                echo json_encode($data);
                                exit();

                            }else{

                                $cart[$i]["ordered_qty"] = $ordered_qty_temp;
                                $cart[$i]['sub_total'] = (float)$cart[$i]['sub_total'] + (float)$cart_item_sub_total;

                                $cart_total_price = $cart_total_price + $cart_item_sub_total;

                                session()->put("cart", $cart);
                                session()->put("total_price", $cart_total_price);
                                session()->save();

                                $data = array('status'=> 'success', 'total_qty'=>count($cart) , 'total_price' => $cart_total_price);
                                echo json_encode($data);
                                exit();
                            }
    					}
    				}

    				$cartItem = array('product_id' => (int)$request->product_id, 
    					'product_title' => addslashes($product->title),
    					'color_id' => (int)$request->color_id, 
    					'color_name' => isset($product_color) ? $product_color->color_details->title : NULL,
    					'color_code' => isset($product_color) ? $product_color->color_details->code : NULL,
    					'size_id' => (int)$request->size_id, 
    					'size_name' => isset($product_size) ? $product_size->size_details->title : NULL,
    					'ordered_qty' =>(int)$request->ordered_qty,
    					'sub_total' => (float)$cart_item_sub_total,
    				);
    				$cart_total_price = $cart_total_price + $cart_item_sub_total;

    				session()->push("cart", $cartItem);
    				session()->put("total_price", $cart_total_price);

    				$data = array('status'=> 'success', 'total_qty'=>count(session()->get('cart')) , 'total_price' => $cart_total_price);

    				echo json_encode($data);
            	}


            }else{

                $data = array('status'=> 'stockerror', 'stock'=> $availableStock, 'in_cart' => $in_cart);
                echo json_encode($data);
            }

    	}
    }

    public function cart()
    {
    	$cart = (array)session()->get('cart');
        // dd($cart);
    	// $coupon_details = (array)session()->get('coupon_details');
    	if (session()->get('coupon_details')) {
    		session()->forget('coupon_details');
    		session()->save();
    	}
    	return view('frontend.cart', compact('cart'));
    }

    public function update_cart(Request $request){
    	// dd($request);
    	$cart = (array)session()->get("cart");
    	$cart_total_price = (float)session()->get("total_price");

    	if (session()->get('coupon_details')) {

    		session()->forget('coupon_details');
    		session()->save();
    	}

        $product_id = $request->product_id;
        $color_id = $request->color_id;
        $size_id = $request->size_id;
        $ordered_qty = $request->qty;

        $product = Product::where("id", $product_id)->first();
        $product_price = $product->discounted_price != NULL || $product->discounted_price != 0 ? $product->discounted_price : $product->price;

        $cart_item_sub_total = $product_price * $ordered_qty;

        $product_color = ProductColor::where("id", $color_id)->first();
        $product_size = ProductSize::where("id", $size_id)->first();

        switch ($product->variation_type) {
            case 0:
            	
                $max_order_qty = $product->stock_count;

                $cart_product_ids = array_column($cart, 'product_id');
                $cart_key = (int)array_search($product_id, $cart_product_ids);
                break;

            case 1:
            	
                $max_order_qty = $product_color->stock_count;

                $cart_color_ids = array_column($cart, 'color_id');
                $cart_key = (int)array_search($color_id, $cart_color_ids);
                break;

            case 2:
            	
                $max_order_qty = $product_size->stock_count;

                $cart_size_ids = array_column($cart, 'size_id');
                $cart_key = (int)array_search($size_id, $cart_size_ids);
                break;
            
            default:
                $max_order_qty = 1;
                break;
        }

        // var_dump($cart_key);
        // exit();
        $in_cart = count($cart) == 0 ? 0 : $cart[$cart_key]['ordered_qty'];
        $availableStock = $max_order_qty - $in_cart;

        if ($max_order_qty >= $ordered_qty) {
			

            $cart[$cart_key]["ordered_qty"] = $ordered_qty;
            $cart_total_price = $cart_total_price - $cart[$cart_key]['sub_total'];
            $cart[$cart_key]['sub_total'] = (float)$cart_item_sub_total;

            $cart_total_price = $cart_total_price + $cart_item_sub_total;

            session()->put("cart", $cart);
            session()->put("total_price", $cart_total_price);
            session()->save();

            $data = array('status'=> 'success', 'total_qty'=>count($cart) ,  'item_sub_total' => $cart_item_sub_total, 'total_price' => $cart_total_price);
            echo json_encode($data);
            exit();
	    }else{

            $data = array('status'=> 'stockerror', 'stock'=> $availableStock, 'in_cart' => $in_cart);
            echo json_encode($data);
        }

        // ==========================================================================================

    }

    public function delete_cart_item(Request $request)
    {

        $cart = (array)session()->get("cart");
        $cart_total_price = (float)session()->get("total_price");

        if (@$request->action == 'delete') {
            $item_key = $request->item_key;

            $cart_total_price = $cart_total_price - $cart[$item_key]['sub_total'];

            unset($cart[$item_key]);
            $cart = array_values($cart);

            $data = array('status' => 'deleted', 'total_qty' => count($cart), 'total_price' => $cart_total_price);

            session()->put("cart", $cart);
            session()->put("total_price", $cart_total_price);
            session()->save();

            echo json_encode($data);
        }
    }

    public function checkout()
    {
    	$cart = session()->get('cart');
    	$cart_total_price = session()->get('total_price');
    	
    	if (session()->get('coupon_details')) {
    		session()->forget('coupon_details');
    		session()->save();
    	}

    	if ($cart == null) {
    	    return redirect('/')->with('error', 'Shopping Cart Empty!');
    	}

    	if (Auth::check()) {
    	    
    	    $customer = User::where('id', Auth::user()->id)->first();
    	    $billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
    	    $shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();

    	}else{

    	    $customer = $billing_address = $shipping_address = NULL;
    	}

    	$coupon_details = (array)session()->get('coupon_details');
    	$db_countries = DB::table('countries')->get();
    	$payment_methods = ModelHelper::payment_methods();
        $available_coupons = DiscountCoupon::where([['display',1],['start_date','<=', date('Y-m-d')],['expire_date','>=', date('Y-m-d')]])->get();
    	// dd($payment_methods);

    	return view('frontend.checkout',compact('customer', 'billing_address', 'shipping_address', 'coupon_details', 'db_countries', 'cart', 'payment_methods', 'available_coupons'));
    }

    public function apply_coupon(Request $request){
        
        if (!Auth::check()) {

            $data = array('status' => 'auth_failed');
            echo json_encode($data);
            exit();
        }

        $cart = (array)session()->get('cart');
        $cart_total_price = $request->sub_total;
        $coupon_details = (array)session()->get('coupon_details');

        if ($request->action == 'apply_coupon') {

            $code = strtolower($request->code);
            $coupon = DiscountCoupon::where('code',$code)->first();
            
            $todayDate = date('Y-m-d h:i:s');

            if ($coupon) {

                if ($cart_total_price < $coupon->min_spend) {

                    $data = array('status' => 'min_spend_invalid', 'min_spend' => $coupon->min_spend);
                    echo json_encode($data);
                    exit();
                }


                $user = User::find(Auth::user()->id);
                $alreadyApplied = AppliedCoupon::where([['customer_id', $user->id],['coupon_id', $coupon->id]])->exists();

                if ($alreadyApplied) {
                    
                    $data = array('status' => 'already_used');
                    echo json_encode($data);
                    exit();
                }

                if ($todayDate >= $coupon->start_date && $todayDate <= $coupon->expire_date ) {

                    $discount_amount = $cart_total_price * ($coupon->discount_percentage/100);

                    if ($discount_amount > $coupon->max_discount) {

                        $discount_amount = $coupon->max_discount;
                    }

                    $cart_total_price = $cart_total_price - $discount_amount;
                    $couponDiscount = $coupon->discount_percentage."% off (upto Nrs.".$coupon->max_discount.")";

                    session()->put("coupon_details", array(
                    										"id" => $coupon->id, 
                    										"code" => $coupon->code, 
                    										"title" => $coupon->name." - ".$couponDiscount, 
                    										"discount_amount" => $discount_amount
                    									));

                    $data = array('status' => 'calculated', 'title' => $coupon->name." - ".$couponDiscount, 'discount_amount' => $discount_amount, 'total_price' => round($cart_total_price, 2));
                }else{

                    $data = array('status' => 'invalid_date');

                }
            }else{
                $data = array('status' => 'invalid_code');
            }

            // echo $cart_total_price;
            // exit();
            session()->put("total_price", $cart_total_price);
            session()->save();
            
            echo json_encode($data);
        }

    }
}
