<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Color;
use App\Models\Size;
use App\Models\Blog;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cart = session()->get('cart');
        // dd($cart);
        // session()->flush();
        // session()->save();
        $banners = Banner::where('display',1)->get();
        $popular_categories = Category::where([['display',1],['popular',1],['child',0]])->inRandomOrder()->limit(12)->get();
        $featured_products = Product::where([['display',1],['featured',1]])->inRandomOrder()->limit(20)->get();
        $new_arrivals = Product::where('display',1)->orderBy('created_at')->limit(20)->get();
        $blogs = Blog::where([['display',1],['featured',1]])->orderBy('created_at','desc')->get();

        return view('frontend.home',compact('banners','popular_categories','featured_products','new_arrivals','blogs'));
    }

    public function product_details($slug)
    {
        $product = Product::where([['display',1],['slug',$slug]])->firstOrFail();
        $related_products = $product->categories->pluck('products')->flatten()->where('id','!=',$product->id)->unique('id')->all();

        return view('frontend.product-details', compact('product','related_products'));
    }

    public function category_products($slug)
    {
        $category = Category::where('slug',$slug)->firstOrFail();
        $category_products = $category->products()->inRandomOrder()->paginate(12);
        // dd($products);

        return view('frontend.category-products', compact('category','category_products'));
    }

    public function products($type)
    {
        if ($type == 'featured') {

            $products = Product::where([['display',1],['featured',1]])->inRandomOrder()->get();
            $page_title = 'Featured Products';
        }elseif ($type == 'new-arrivals') {

            $products = Product::where('display',1)->orderBy('created_at')->paginate(12);
            $page_title = 'New Arrivals';
        }elseif ($type == 'trending') {

            $products = Product::where('display',1)->orderBy('created_at')->paginate(12);
            $page_title = 'Trending Products';
        }elseif ($type == 'best-selling') {

            $products = Product::where('display',1)->orderBy('created_at')->paginate(12);
            $page_title = 'Best Selling Products';
        }
        
        return view('frontend.products-all', compact('products','page_title'));
    }

    public function login()
    {
        if (!Auth::check()) {

            return view('frontend.login');

        } else {

            if (Auth::user()->status == '1') {

                if (Auth::user()->hasRole(['super-admin'])) {

                    return redirect()->route('admin.dashboard')->with('error', 'Already Logged In as a Administrator!');

                } else {

                    return redirect('/')->with('error', 'You are Already Logged In as Customer!');
                }
            } else {
                Auth::logout();
            }

        }
    }

    public function register()
    {
        if (!Auth::check()) {
            return view('frontend.register');
        } else {

            if (Auth::user()->status == '1') {

                if (Auth::user()->hasRole(['super-admin'])) {

                    return redirect()->route('admin.dashboard')->with('error', 'Already Logged In as a Administrator!');

                } else {

                    return redirect('/')->with('error', 'You are Already Logged In as Customer!');
                }
            } else {
                Auth::logout();
            }

        }
    }
    
    public function check_user_email_availability(Request $request)
    {
        $checkUserEmailAvailability = User::where('email', $request->email)->doesntExist();

        if ($checkUserEmailAvailability) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_product_details(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $pResponse = '';
        if ($product) {
            

            $pResponse .= '<div class="col-lg-5">
                                <img src="'.asset("storage/products/".$product->slug."/thumbs/small_".$product->image).'" alt="'.$product->slug.'"> 
                            </div>
                            <div class="col-xl-7 col-lg-6">
                                <div class="sp-content">
                                    <div class="sp-heading">
                                        <h5 style="padding-bottom: 0px;">
                                            <a href="'.route('product-details',['slug' => $product->slug]).'">'.$product->title.'</a>
                                        </h5>
                                    </div>
                                    <div class="rating-box">
                                        <ul>
                                            <li><i class="ion-android-star"></i></li>
                                            <li><i class="ion-android-star"></i></li>
                                            <li><i class="ion-android-star"></i></li>
                                            <li class="silver-color"><i class="ion-android-star"></i></li>
                                            <li class="silver-color"><i class="ion-android-star"></i></li>
                                        </ul>
                                    </div>
                                    <div class="price-box">';
                                        if($product->discounted_price != NULL || $product->discounted_price != 0){
                                            $pResponse .= '<span class="new-price new-price-2">Nrs.'.$product->discounted_price.'</span>
                                                            <span class="old-price">Nrs.'.$product->price.'</span>';
                                        }else{
                                            $pResponse .= '<span class="new-price new-price-2">Nrs.'.$product->price.'</span>';
                                        }
                                        
                                    $pResponse .= '</div>';
                                    if ($product->product_colors->count() > 0) {
                                        $pResponse .= '<div class="color-list_area">
                                        <div class="options-wrapper">

                                            <div class="option-1">
                                                <span class="sub-title">Color</span>
                                                <div class="color-list" id="color-picker">';
                                                    
                                                    foreach ($product->product_colors as $color_key => $color) {
                                                        
                                                        if ($color_key == 0) {
                                                            $firstColor = $color;
                                                        }

                                                        $hasSizes = $color->product_sizes->count() > 0 ? 1 : 0;
                                                        $colorActiveStatus = $color_key == 0 ? 'active' : '';

                                                        $pResponse .= '<a href="javascript:void(0)" class="single-color '.$colorActiveStatus.'" data-has-sizes="'.$hasSizes.'" data-color-id="'.$color->id.'" data-stock-count="'.$color->stock_count.'" data-swatch-color="'.$color->color_details->code.'">
                                                            <span style="background-color: '.$color->color_details->code.'" class="bg-red_color"></span>
                                                        </a>';
                                                    }
                                                    
                                                    
                                                $pResponse .= '</div>
                                            </div>';
                                            if ($firstColor->product_sizes->count() > 0) {
                                                $pResponse .= '<div class="option-2">
                                                    <span class="sub-title">Sizes</span>
                                                    <div class="range-picker" id="size-picker">';

                                                        foreach ($firstColor->product_sizes as $size_key => $size) {
                                                            if ($size_key == 0) {
                                                                $firstSize = $size;
                                                            }
                                                            $sizeActiveStatus = $size_key==0 ? 'active' : '';
                                                            $pResponse .= '<div class="'.$sizeActiveStatus.'" data-size-id="'.$size->id.'" data-stock-count="'.$size->stock_count.'">'.$size->size_details->title.'</div>';
                                                        }
                                                        
                                                        
                                                    $pResponse .= '</div>
                                                </div>';
                                            }
                                            
                                        $pResponse .= '</div>
                                    </div>';
                                    }

                                    switch ($product->variation_type) {
                                        case 0:
                                            $max_order_qty = $product->stock_count;
                                            break;

                                        case 1:
                                            $max_order_qty = $firstColor->stock_count;
                                            break;

                                        case 2:
                                            $max_order_qty = $firstSize->stock_count;
                                            break;
                                        
                                        default:
                                            $max_order_qty = 1;
                                            break;
                                    }

                                    $pResponse .= '<div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input id="ordered-qty" class="cart-plus-minus-box" value="1" type="text" max="'.$max_order_qty.'" required>
                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="kenne-group_btn">
                                        <ul>
                                            <li><a id="add-to-cart" data-product-id="'.$product->id.'" data-variation-type="'.$product->variation_type.'" href="javascript:void(0)" class="add-to_cart">Add To Cart</a></li>
                                            <li><a id="add-to-wishlist" href="javascript:void(0)"><i class="ion-android-favorite-outline"></i></a></li>
                                        </ul>
                                    </div>

                                    <div class="kenne-tag-line">
                                        <h6>Tags: <small><i>'.$product->tags.'</i></small></h6>
                                    </div>

                                    
                                </div>
                            </div>';

            $response = array('product_details' => $pResponse);

        }else{

            $response = array('error' => 'error');
        }

        echo json_encode($response);
    }

    public function get_related_sizes(Request $request)
    {
        $color_id = $request->color_id;
        $product_color = ProductColor::find($color_id);
        $sResponse = '';
        if ($product_color) {
            foreach ($product_color->product_sizes as $size_key => $size) {

                if ($size_key == 0) {
                    $firstSize = $size;
                }
                
                $sizeActiveStatus = $size_key==0 ? 'active' : '';
                $sResponse .= '<div class="'.$sizeActiveStatus.'" data-size-id="'.$size->id.'" data-stock-count="'.$size->stock_count.'">'.$size->size_details->title.'</div>';
            }

            $response = array('related_sizes' => $sResponse, 'max_order_qty' => $firstSize->stock_count);

        }else{

            $response = array('error' => 'error');
        }

        echo json_encode($response);
    }

    public function get_states(Request $request)
    {
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $responseText = "<option value='' disabled selected>Select State/Region</option>";
        
        $states = DB::table('states')->where('country_id', $country_id)->get();
        
        foreach ($states as $stat) {

            if ($stat->id == $state_id) {

                $selectFlag = 'selected';
            }else{
                $selectFlag = '';
            }

            $responseText .= "<option ".$selectFlag." value='".$stat->id."' >".$stat->name."</option>";
        }

        return $responseText;
    }

    public function blogs()
    {
        $blogs = Blog::where('display',1)->orderBy('created_at','desc')->get();
        return view('frontend.blogs', compact('blogs'));
    }

    public function about_us()
    {
        
    }
}
