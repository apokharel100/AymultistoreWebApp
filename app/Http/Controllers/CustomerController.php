<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Validator;


class CustomerController extends Controller
{
	use HasRoles;

    public function my_account()
    {
    	$customer = User::where('id',Auth::user()->id)->first();
    	$billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
    	$shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();
    	// $orders = $customer->customer_orders()->orderBy('created_at','desc')->limit(4)->get();
    	// $wishlists = $customer->wishlists;

    	// dd($wishlists);
    	return view('frontend.my-account', compact('customer', 'billing_address', 'shipping_address'));
    }

    public function customer_addresses()
    {
        $customer = User::where('id', Auth::user()->id)->first();
        $billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
        $shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();

        $db_countries = DB::table('countries')->get();


        // dd($billing_address);
        return view('frontend.customer-addresses',compact('customer', 'billing_address', 'shipping_address','db_countries'));
    }

    public function account_settings()
    {
    	$customer = User::where('id', Auth::user()->id)->first();

    	// dd($billing_address);
    	return view('frontend.account-settings',compact('customer'));
    }

   	public function update_account_settings(Request $request)
   	{
   		
   		
   		$validator = Validator::make($request->all(), [
			"name" => 'required|max:255'
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		// dd($_POST);

		$user = User::findOrFail(Auth::user()->id);
		$user->name = $request->name;
		$userDetailsSaved = $user->save();

		if ($userDetailsSaved) {

			if ($request->old_password != 0) {
				
				if (Hash::check($request->old_password, $user->password)) {

				    $validatedData = $request->validate([
				        'password' => ['required', 'string', 'min:6', 'confirmed'],
				    ]);

				    $user->password = Hash::make($request['password']);
				    $user->save();

				    return redirect()->back()->with('status', 'Password updated Successfully!');

				} else {
				    return redirect()->back()->with('error', 'Your Current Password Doesnot Match!!');
				}

			}

			return redirect()->back()->with('status','Your Account Information has been updated Succcessfully!');
		}

   	}

   	public function create_update_addresses(Request $request)
   	{
   		
   		// dd($_POST);
   		$validator = Validator::make($request->all(), [
			"address_type" => 'required',
			"name" => 'required',
			"email" => 'required',
			"phone" => 'required',
			"street_address" => 'required',
			"city" => 'required',
			"zip_code" => 'required',
			"country" => 'required',
			"state" => 'required'
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		// dd($_POST);

		$user = User::findOrFail(Auth::user()->id);

		$addressArray = array("name" => $request->name,
							  "email" => $request->email,
							  "phone" => $request->phone,
							  "street_address" => $request->street_address,
							  "apt_ste_bldg" => $request->apt_ste_bldg,
							  "city" => $request->city,
							  "zip_code" => $request->zip_code,
							  "country" => $request->country,
							  "state" => $request->state
							);
		if ($request->address_type == 'billing') {
			$addressSaved = $user->customer_addresses()->updateOrCreate(['address_type' => 1], $addressArray);
		}else{
			$addressSaved = $user->customer_addresses()->updateOrCreate(['address_type' => 2], $addressArray);
		}

		if ($addressSaved) {
			return redirect()->back()->with('status','Address updated Succcessfully!');
		}
		

   	}


   	public function orders()
   	{
   		$customer = User::where('id',Auth::user()->id)->first();
    	$orders = $customer->customer_orders()->orderBy('created_at','desc')->paginate(15);
    	$order_status = Order::order_status();
    	
    	return view('frontend.customer-orders',compact('customer','orders', 'order_status'));
   	}

   	public function view_order($order_no)
   	{
   		$order = Order::where([['customer_id', Auth::user()->id],['order_no', base64_decode($order_no)]])->first();
   		
   		if (!$order) {
   			return redirect()->back()->with('error','Order Detail Not Found.');
   		}
   		$billing_details = json_decode($order->billing_details);
   		$shipping_details = json_decode($order->shipping_details);
   		$order_status = Order::order_status();

   		return view('frontend.view-order-details',compact('order','billing_details','shipping_details','order_status'));
   	}


   	public function wishlist()
   	{
   		$customer = User::where('id', Auth::user()->id)->first();
    	$wishlists = $customer->wishlists;
    	
    	return view('frontend.wishlists',compact('customer','wishlists'));
   	}

   	public function add_to_wishlist(Request $request)
   	{
   		
   		if (!Auth::check()){
   			
   			$data = array('status' => 'login-error');	
   			echo json_encode($data);
   			exit();
   		}
   		

		if (Auth::user()->hasRole(['customer'])) {

	   		if ($request->product_id) {

	            $product_id = $request->product_id;
	            $productExists = Wishlist::where([['customer_id',Auth::user()->id],['product_id', $product_id]])->first();

	            if ($productExists) {

	            	// $productExists = Wishlist::where([['customer_id', Auth::user()->id],['product_id',$product_id]])->delete();	            	
	            	$data = array('status'=> 'exist');
	            	echo json_encode($data);
	            	exit();

	            }else{

	            	$wishlist_product = Wishlist::create(['customer_id' => Auth::user()->id,'product_id' => $product_id]);
	            	
	            	$data = array('status'=> 'success');
	            	echo json_encode($data);
	            	exit();
	            }
	        }else{

	        	$data = array('status'=> 'error');
	        	echo json_encode($data);
	        	exit();
	        }
	    }else{

	    	$data = array('status'=> 'not-a-customer');
   			echo json_encode($data);
   			exit();
	    }
   	}

   	public function remove_from_wishlist(Request $request){
        if (@$request->action == 'delete') {

            $wishlist_id = $request->wishlist_id;
            $customer = User::where('id', Auth::user()->id)->firstOrFail();
            $wishlist = $customer->wishlists()->where('id',$wishlist_id)->delete();

            if ($wishlist) {

                $data = array('status'=> 'removed');
                echo json_encode($data);
                
            }
        }
    }

}
