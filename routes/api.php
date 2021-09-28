<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1','middleware' => ['cors', 'json.response']], function () {
	
	Route::post('/login', 'ApiAuthController@login')->name('login.api');
	Route::post('/register','ApiAuthController@register')->name('register.api');

	Route::get('/settings','ApiController@settings')->name('settings.api');
    Route::get('/banners','ApiController@banners')->name('banners.api');

    Route::get('/categories','ApiController@categories')->name('categories.api');

    Route::get('/products','ApiController@products')->name('products.api');
    Route::get('/product/{slug}','ApiController@product_details')->name('product_details.api');
    Route::get('/featured-products','ApiController@featured_products')->name('featured-products.api');
    Route::get('/category-products/{slug}','ApiController@category_products')->name('category-products.api');

    Route::get('categories/main', 'ApiController@main_categories')->name('main-categories.api');
    Route::get('categories/main/{params?}', 'ApiController@child_categories')->where('params', '(.*)')->name('child-categories.api');

    Route::get('/coupons','ApiController@coupons')->name('coupons.api');
    Route::get('/countries','ApiController@countries')->name('countries.api');
    Route::get('/states','ApiController@states')->name('states.api');
    Route::get('/states/{country_id}','ApiController@states_by_country_id')->name('states-by-country-id.api');

    Route::post('/checkout','CheckoutController@checkout')->name('checkout.api');

});


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1', 'middleware' => ['cors', 'json.response','auth:api']], function(){

	Route::get('/user','ApiAuthController@user')->name('user.api');
	// Route::post('/update-profile','CustomerController@update_profile')->name('update-profile.api');
	// ROute::post('/update-password','CustomerController@update_password')->name('update-password.api');

	Route::get('/wishlist','CustomerController@wishlist')->name('wishlist.api');
	Route::post('/add-to-wishlist','CustomerController@add_to_wishlist')->name('add-to-wishlist.api');
	Route::post('/remove-from-wishlist','CustomerController@remove_from_wishlist')->name('remove-from-wishlist.api');

	// Route::get('/order-history','CustomerController@order_history')->name('order-history.api');
	// Route::get('/order-details/{id}','CustomerController@order_details')->name('order-details.api');

	Route::post('/apply-coupon','CheckoutController@apply_coupon')->name('apply-coupon.api');

	Route::get('resend-verification-mail','ApiAuthController@resend_verification_mail')->name('resend-verification-mail');
	Route::post('verify-account','ApiAuthController@verify_account')->name('verify-account.api');
	Route::post('/logout', 'ApiAuthController@logout')->name('logout.api');

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
