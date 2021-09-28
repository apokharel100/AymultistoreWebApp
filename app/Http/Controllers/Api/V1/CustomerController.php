<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Services\ApiHelper;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Banner;
use App\Models\SiteSetting;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\AppliedCoupon;

class CustomerController extends Controller
{
    public function wishlist()
    {
    	$customer = User::with('wishlists.product.product_colors.product_sizes')->where('id',Auth::user()->id)->first();
    	$wishlist_products = $customer->wishlists->pluck('product')->all();
    	// return response()->json($wishlist_products);
    	foreach ($wishlist_products as $product) {
        	$product = ApiHelper::execute_products_functionality($product);
        }

        return response()->json(["status" => 200,'message' =>'Customer Wishlist', "data" => array("wishlist_products" => $wishlist_products)]);
    }

    public function add_to_wishlist(Request $request)
    {
        $validationArray =  ['product_id' => ['required','integer'] ];
        
        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()){

            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $product_id = $request->product_id;

        $product = Product::where('id' , $product_id)->first();

        if ($product) {

            
            $customer = Auth::user();
            $productExists = $customer->wishlists()->where('product_id',$product->id)->exists();
            if ($productExists) {
            	return response()->json(["status" => 422, "message" => 'Already Exists', 'data' => ['errors' => ["Product is already in your Wishlist!"]]]);
            }else{
            	$wishlist_product = $customer->wishlists()->create(['product_id' => $product_id]);
            	
            	if ($wishlist_product) {
	                return response()->json(["status" => 200, "message" => 'Product added to Wishlist']);
	            }else{
	                return response()->json(["status" => 500, "message" => 'Database Error!']);
	            }
            }

        }else{
        	return response()->json(["status" => 404, "message" => 'Product Not Found', 'data' => ['errors' => ["Requested Product Not Found!"]]]);
        }
    }

    public function remove_from_wishlist(Request $request)
    {
        $validationArray =  ['product_id' => ['required','integer'] ];
        
        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()){

            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $product_id = $request->product_id;
        $customer = Auth::user();

        $productExists = $customer->wishlists()->where('product_id',$product_id)->exists();

        if (!$productExists) {
        	return response()->json(["status" => 422, "message" => 'Product Not Found', 'data' => ['errors' => ["Product is not available in your Wishlist!"]]]);
        }else{

        	$wishlist_product = $customer->wishlists()->where('product_id' , $product_id)->delete();
        	
        	if ($wishlist_product) {
                return response()->json(["status" => 200, "message" => 'Product removed from Wishlist']);
            }else{
                return response()->json(["status" => 500, "message" => 'Database Error!']);
            }
        }
    }
}
