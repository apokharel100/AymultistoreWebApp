<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('artisan/{command}', function($command){
    $com = explode('-', $command);
    if (isset($com[0]) && isset($com[1])) {
        \Artisan::call($com[0].':'.$com[1]);
        return redirect()->back()->with('success_status', "Artisan Command executed Successfully!");
    }else{
        return redirect()->back()->with('error', "Invalid Artisan Command, Please check it Properly!");
    }
});


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('p/categories/{slug}',[App\Http\Controllers\HomeController::class, 'parent_category_childs'])->name('parent-category-childs');
Route::get('category/{slug}/products',[App\Http\Controllers\HomeController::class, 'category_products'])->name('category-products');
Route::get('product/{slug}', [App\Http\Controllers\HomeController::class, 'product_details'])->name('product-details');
Route::get('products/{type}/all',[App\Http\Controllers\HomeController::class, 'products'])->name('products');
// Route::get('products/trending/all',[App\Http\Controllers\HomeController::classs, 'trending_products'])->name('trending-products');
// Route::get('products/new/all',[App\Http\Controllers\HomeController::classs, 'new_products'])->name('new-products');

Route::post('get-product-details', [App\Http\Controllers\HomeController::class, 'get_product_details'])->name('get-product-details');
Route::post('get-related-sizes', [App\Http\Controllers\HomeController::class, 'get_related_sizes'])->name('get-related-sizes');

Route::get('blogs', [App\Http\Controllers\HomeController::class, 'blogs'])->name('blogs');
Route::get('blog/{slug}', [App\Http\Controllers\HomeController::class, 'blog_details'])->name('blog-details');

Route::post('add-to-cart', [App\Http\Controllers\CartController::class, 'add_to_cart'])->name('add-to-cart');
Route::get('cart', [App\Http\Controllers\CartController::class, 'cart'])->name('cart');
Route::post('update-cart', [App\Http\Controllers\CartController::class, 'update_cart'])->name('update-cart');
Route::post('delete-cart-item', [App\Http\Controllers\CartController::class, 'delete_cart_item'])->name('delete-cart-item');
Route::post('apply-coupon', [App\Http\Controllers\CartController::class, 'apply_coupon'])->name('apply-coupon');

Route::get('checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::post('place-order', [App\Http\Controllers\OrderController::class, 'place_order'])->name('place-order');

Route::post('add-to-wishlist', [App\Http\Controllers\CustomerController::class, 'add_to_wishlist'])->name('add-to-wishlist');

Route::get('user/login', [App\Http\Controllers\HomeController::class, 'login'])->name('user.login');
Route::get('user/register', [App\Http\Controllers\HomeController::class, 'register'])->name('user.register');

Route::post('get-states',[App\Http\Controllers\HomeController::class, 'get_states'])->name('get-states');

Route::name('customer.')->middleware(['role:customer'])->prefix('customer/')->group(function () {

    Route::get('account/profile',[App\Http\Controllers\CustomerController::class, 'my_account'])->name('my-account');
    Route::get('settings',[App\Http\Controllers\CustomerController::class, 'account_settings'])->name('settings');
    Route::post('settings/update',[App\Http\Controllers\CustomerController::class, 'update_account_settings'])->name('update-account-settings');
    Route::get('addresses',[App\Http\Controllers\CustomerController::class, 'customer_addresses'])->name('addresses');

    Route::post('create-update-addresses',[App\Http\Controllers\CustomerController::class, 'create_update_addresses'])->name('create-update-addresses');

    Route::get('orders',[App\Http\Controllers\CustomerController::class, 'orders'])->name('orders');
    Route::get('view/order/{order_no}',[App\Http\Controllers\CustomerController::class, 'view_order'])->name('view-order');

    Route::get('wishlist',[App\Http\Controllers\CustomerController::class, 'wishlist'])->name('wishlist');
    Route::post('remove-from-wishlist', [App\Http\Controllers\CustomerController::class, 'remove_from_wishlist'])->name('remove-from-wishlist');
    Route::get('available-coupons',[App\Http\Controllers\CustomerController::class, 'available_coupons'])->name('available-coupons');
});

Route::get('about-us', [App\Http\Controllers\HomeController::class, 'about_us'])->name('about-us');
Route::get('contact-us', [App\Http\Controllers\HomeController::class, 'contact_us'])->name('contact-us');
Route::get('terms-and-conditions', [App\Http\Controllers\HomeController::class, 'terms_and_conditions'])->name('terms-and-conditions');
Route::get('privacy-policy', [App\Http\Controllers\HomeController::class, 'privacy_policy'])->name('privacy-policy');

Auth::routes(['verify' => true]);
    
Route::middleware(['auth','role:super-admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [\App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');
    Route::post('users/update-password', [\App\Http\Controllers\Backend\UserController::class, 'update_password'])->name('users.update-password');
    Route::get('users/get_states/{cName}', [\App\Http\Controllers\Backend\UserController::class, 'get_states'])->name('users.get-states');

    Route::post('categories/set_order', [\App\Http\Controllers\Backend\CategoryController::class, 'set_order'])->name('categories.order');
    Route::get('categories/delete/{id}', [\App\Http\Controllers\Backend\CategoryController::class, 'destroy'])->name('categories.delete');

    Route::get('colors/delete/{id}', [\App\Http\Controllers\Backend\ColorController::class, 'destroy'])->name('colors.delete');
    Route::post('colors/bulk-update', [\App\Http\Controllers\Backend\ColorController::class, 'bulk_update'])->name('colors.bulk-update');
    Route::post('size/update-size', [\App\Http\Controllers\Backend\SizeController::class, 'update_size'])->name('sizes.update-size');


    Route::get('brands/delete/{id}', [\App\Http\Controllers\Backend\BrandController::class, 'destroy'])->name('brands.delete');
    Route::get('banners/delete/{id}', [\App\Http\Controllers\Backend\BannerController::class, 'destroy'])->name('banners.delete');

    Route::get('blogs/delete/{id}', [\App\Http\Controllers\Backend\BlogController::class, 'destroy'])->name('blogs.delete');
    Route::post('blogs/delete-gallery-image', [\App\Http\Controllers\Backend\BlogController::class, 'delete_gallery_image'])->name('blogs.delete-gallery-image');

    Route::prefix('products')->name('products.')->group(function(){
        Route::post('set_order', [\App\Http\Controllers\Backend\ProductController::class, 'set_order'])->name('order');
        Route::get('delete/{id}', [\App\Http\Controllers\Backend\ProductController::class, 'destroy'])->name('delete');

        Route::post('add-extra-variation-fields',[\App\Http\Controllers\Backend\ProductController::class, 'add_extra_variation_fields'])->name('add-extra-variation-fields');

        Route::post('get-variation-fields',[\App\Http\Controllers\Backend\ProductController::class, 'get_variation_fields'])->name('get-variation-fields');

        Route::post('get-size-fields',[\App\Http\Controllers\Backend\ProductController::class, 'get_size_fields'])->name('get-size-fields');

        Route::post('/delete-variation', [\App\Http\Controllers\Backend\ProductController::class, 'delete_variation'])->name('delete-variation');

        Route::post('/delete-gallery-image', [\App\Http\Controllers\Backend\ProductController::class, 'delete_gallery_image'])->name('delete-gallery-image');

        Route::post('generate-product-slug',[\App\Http\Controllers\Backend\ProductController::class, 'generate_product_slug'])->name('generate-product-slug');

    });

    Route::get('discount-coupons/delete/{id}', [\App\Http\Controllers\Backend\BannerController::class, 'destroy'])->name('discount-coupons.delete');

    Route::post('orders/change-order-status', [\App\Http\Controllers\Backend\OrderController::class, 'change_order_status'])->name('orders.change-order-status');

    Route::resources([
        'roles' => Backend\RolePermissionController::class,
        'users' => Backend\UserController::class,
        'products' => Backend\ProductController::class,
        'categories' => Backend\CategoryController::class,
        'brands' => Backend\BrandController::class,
        'banners' => Backend\BannerController::class,
        'blogs' => Backend\BlogController::class,
        'colors' => Backend\ColorController::class,
        'sizes' => Backend\SizeController::class,
        'discount-coupons' => Backend\DiscountCouponController::class,
        'orders' => Backend\OrderController::class,
    ]);

    Route::get('/setting', [\App\Http\Controllers\Backend\SiteSettingController::class, 'index'])->name('setting');
    
    Route::put('/setting/update/{setting}', [\App\Http\Controllers\Backend\SiteSettingController::class, 'update'])->name('setting.update');

});