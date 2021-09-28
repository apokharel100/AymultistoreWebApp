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
use App\Models\Banner;
use App\Models\SiteSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\DiscountCoupon;
use App\Models\AppliedCoupon;

class ApiController extends Controller
{
    public function settings(){
        
        $setting = SiteSetting::find('1');
        return response()->json([
        					"status" => 200,
    						'message' =>'Site Setting Datas', 
    						"data" => [
						 			"setting" => $setting, 
						 			'attributes' => [
				 								'logo_url' => asset('setting/logo'), 
				 								'favicon_url' => asset('setting/favicon'), 
				 								'og_image_url' => asset('setting/og_image')
				 								
				 							]
						 			]
    						]);
    }

	public function banners(){

        $banners = Banner::where('display',1)->get();
        return response()->json([
        					"status" => 200, 
        					'message' =>'Banners Fetched', 
        					"data" => [
        							"banners" => $banners, 
        							'attributes' => [
        										'main_image_url' => asset('storage/banners'), 
        										'thumb_image_url' => asset('storage/banners/thumbs/'), 
        										'image_variations' => ['thumb_']
        									]
        						]
        				]);
    }

    public function categories()
    {
    	$categories = ModelHelper::getFullListFromDB('categories');
    	return response()->json([
    						"status" => 200, 
    						'message' =>'Categories Fetched', 
    						"data" => [
    								"categories" => $categories,
    								"attributes" => [
        										'main_image_url' => asset('storage/categories'), 
        										'thumb_image_url' => asset('storage/categories/thumbs/'), 
        										'image_variations' => ['thumb_','small_']
        									]
    							]
    					]);
    }

    public function main_categories()
    {
    	$categories =  Category::select('title','slug','child','image',)->where('parent_id', 0)->orderBy('order_item')->get();

    	return response()->json([
    						"status" => 200, 
    						'message' =>'Main Categories Fetched', 
    						"data" => [
    								"categories" => $categories,
    								"attributes" => [
        										'main_image_url' => asset('storage/categories'), 
        										'thumb_image_url' => asset('storage/categories/thumbs/'), 
        										'image_variations' => ['thumb_','small_']
        									]
    							]
    					]);
    }

    public function child_categories($params = null)
    {

    	if($params) {
            $params = explode('/', $params);
            $slug = last($params);
        }else{
        	return response()->json(["status" => 404, "message" => 'Category Not Found']);
        }

    	$category = Category::where('slug' , $slug)->first();

		if (!$category) {
			return response()->json(["status" => 404, "message" => 'Category Not Found']);
		}

		$child_categories =  Category::select('title','slug','child','image')->where('parent_id', $category->id)->orderBy('order_item')->get();

    	return response()->json([
    						"status" => 200, 
    						'message' =>'Main Categories Fetched', 
    						"data" => [
    								"categories" => $child_categories,
    								"attributes" => [
        										'main_image_url' => asset('storage/categories'), 
        										'thumb_image_url' => asset('storage/categories/thumbs/'), 
        										'image_variations' => ['thumb_','small_']
        									]
    							]
    					]);
    }    

    public function products()
	{
		$products = Product::with('brand','product_colors.sizes')->where('display',1)->inRandomOrder()->get();

		$per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 20;
		$pageDetails = ProductPaginator::get_current_page(abs($per_page));
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

		$products = ProductPaginator::paginate_products($products, $currentPage, $perPage);

		foreach ($products as $product) {

			$product = $this->execute_products_functionality($product);

		}

		return response()->json(["status" => 200, 'message' =>'Products Fetched', "data" => array("products" => $products)]);
	}

	public function featured_products()
	{
		$products = Product::with('brand','product_colors.sizes')->where([['display',1],['featured',1]])->inRandomOrder()->get();

		$per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 20;
		$pageDetails = ProductPaginator::get_current_page(abs($per_page));
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

		$products = ProductPaginator::paginate_products($products, $currentPage, $perPage);

		foreach ($products as $product) {
			$product = $this->execute_products_functionality($product);
		}

		return response()->json(["status" => 200, 'message' =>'Products Fetched', "data" => array("products" => $products)]);
	}

	public function category_products($slug)
	{
		$category = Category::where('slug' , $slug)->first();
		
		if (!$category) {
			return response()->json(["status" => 404, "message" => 'Category Not Found']);
		}

		// ===============================================================================
		$per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 20;

		$pageDetails = ProductPaginator::get_current_page(abs($per_page));

        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        // ===============================================================================

        $products =  $category->products;
        unset($category->products);
        $products = ProductPaginator::paginate_products($products, $currentPage, $perPage);

        foreach ($products as $product) {
        	$product = $this->execute_products_functionality($product);
        	unset($product->pivot);
        }

        $category->products = $products;
        // return response()->json($category->products);
        return response()->json(["status" => 200, 'message' =>'Category Products Fetched', "data" => array("category" => $category)]);
	}


	public function product_details($slug)
	{
		$product = Product::with('brand','product_colors.product_sizes')->where('slug' , $slug)->first();

		if (!$product) {
			return response()->json(["status" => 404, "message" => 'Product Not Found']);
		}else{

			$product = $this->execute_products_functionality($product);
			
			return response()->json(["status" => 200, 'message' =>'Product Details Fetched', "data" => array("product" => $product)]);
		}
	}

	public static function execute_products_functionality($product)
	{
		$gallery_images = array();
	    $product->discounted_price = $product->discounted_price == NULL ? 0 : $product->discounted_price;
		// array_push($gallery_images, $product->image);

		$images = Storage::files('public/products/'.$product->slug.'/');

		for ($i=0; $i < count($images); $i++) { 
			if (basename($images[$i]) != $product->image) {
				array_push($gallery_images, basename($images[$i]));
			}
			
		}

		$product->gallery_images = $gallery_images;
		$product->attributes = [
							'main_image_url' => asset('storage/products/'.$product->slug."/"), 
							'image_variation_url' => asset('storage/products/'.$product->slug."/"),
							'image_variations' => ['small_','thumb_']
						];
		
		$categoryNames = array();
		$productCategories = $product->categories;

		foreach ($productCategories as $key => $prodCat) {
			array_push($categoryNames, $prodCat->title);
		}

		$product->category_names = $categoryNames;

		unset($product->categories);

		// if ($product->product_colors->count() > 0) {
		// 	foreach ($product->product_colors as $color_key => $color) {

		// 	}
		// }

		return $product;

	}

	public function coupons()
	{
		$coupons = DiscountCoupon::where([['display',1],['start_date','<=', date('Y-m-d')],['expire_date','>=', date('Y-m-d')]])->get();

		return response()->json(["status" => 200, 'message' =>'Discount Coupons Fetched', "data" => array("coupons" => $coupons)]);
	}


    public function countries()
	{
		$countries = DB::table('countries')->get();

		return response()->json(["status" => 200, 'message' =>'Countries Fetched', "data" => array("countries" => $countries)]);
	}

	public function states()
	{
		$states = DB::table('states')->get();

		return response()->json(["status" => 200, 'message' =>'States Fetched', "data" => array("states" => $states)]);
	}

	public function states_by_country_id($country_id)
	{
		$states = DB::table('states')->where('country_id',$country_id)->get();

		return response()->json(["status" => 200, 'message' =>'States By Country Id Fetched', "data" => array("states" => $states)]);
	}

}
