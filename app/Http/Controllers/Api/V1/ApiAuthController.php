<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\ApiToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; 
use App\Notifications\SignupActivate;
use Spatie\Permission\Traits\HasRoles;

class ApiAuthController extends Controller
{
    public function register (Request $request) {

        if ($request->google_token != '') {
            
            $validationArray =  ['google_token' => 'required|string'];

        }elseif ($request->facebook_token != '') {

            $validationArray =  ['facebook_token' => 'required|string'];

        }elseif ($request->apple_token != '') {

            $validationArray =  ['apple_token' => 'required|string'];

        }else{
            $validationArray =  [
                                'name' => 'required|string|max:255',
                                'email' => 'required|string|email|max:255|unique:users',
                                'password' => 'required|string|min:6|confirmed'
                            ];
        }

        // return $validationArray;
        
        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()){

            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        // return $validationArray;

        if ($request->google_token != '' || $request->facebook_token != '' || $request->apple_token != '') {

            if ($request->google_token != '') {

                $user = User::where('google_token', $request->google_token);
                
                if (isset($request->email)) {
                    $user = $user->orWhere('email', $request->email);
                }
                
                $user = $user->first();
                

            }elseif ($request->facebook_token != '') {
                
                $user = User::where('facebook_token', $request->facebook_token);
                
                if (isset($request->email)) {
                    $user = $user->orWhere('email', $request->email);
                }
                
                $user = $user->first();
                
                
            }elseif ($request->apple_token != '') {
                
                $user = User::where('apple_token', $request->apple_token);

                if (isset($request->email)) {
                    $user = $user->orWhere('email', $request->email);
                }
                
                $user = $user->first();
                // return $user;
            }

            if (isset($user)) {

                if (isset($request['fb_token']) || isset($request['ios_token'])) {

                    $user->api_tokens()->create(['fb_token' => @$request['fb_token'], 'ios_token' => @$request['ios_token']]);
                }

                $token = $user->createToken('Login User')->accessToken;
                if (isset($request->name)) {
                    $user->name = $request->name;
                }

                if (isset($request->email)) {
                    $user->email = $request->email;
                }
                $user->save();
                $user->token = $token;

                $user = (object)$this->setData($user->toArray());
                    
                // $user->wishlist = json_decode($user->wishlist);
                // $user->coupons = json_decode($user->coupons);

                $response = ['status' => 200, 'message' => 'User Logged In!', 'data' =>['user' => $user]];

                return response($response);
            }
        }


        if ($request->google_token != '') {
            
            $request['google_token'] = $request['google_token'];
            $request['email_verified_at'] = date('Y-m-d H:i:s');
        }elseif ($request->facebook_token != '' ) {
            
            $request['facebook_token'] = @$request['facebook_token'];
            $request['email_verified_at'] = date('Y-m-d H:i:s');
        }elseif ($request->apple_token != '') {

            $request['apple_token'] = @$request['apple_token']; 
            $request['email_verified_at'] = date('Y-m-d H:i:s');
        }else{

            do{

               $randomToken = rand(100000,999999);

            }while(!empty(User::where('otp', $randomToken)->first()));
            
            $request['password'] = Hash::make($request['password']);
            $request['otp'] = $randomToken;
        }

        // $password = isset($request['password']) ? $request['password'] : 'customer@123'; // set default password
        // return $request['google_token'];
        $request['remember_token'] = Str::random(10);
        $request['status'] = 1;
        // $request['role'] = 0;
        // $request['wishlist'] = '[]';
        // $request['coupons'] = '[]';
        // return $request->toArray();

        $user = User::create($request->toArray());

        $user->assignRole('customer');
        
        
        // $user->notify(new SignupActivate($user));
        

        if (isset($request['fb_token']) || isset($request['ios_token'])) {
            $user->api_tokens()->create(['fb_token' => @$request['fb_token'], 'ios_token' => @$request['ios_token']]);
        }
        
        $token = $user->createToken('Register User')->accessToken;
        $user = User::find($user->id);
        $user->token = $token;

        $user = (object)$this->setData($user->toArray());
        
        

        $response = ['status' => 200, 'message' =>"User Registered", 'data' => ['user' => $user]];
        return response($response);
    }

    public function login (Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails())
        {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }
        
        $user = User::where('email', $request->email)->first();

        if ($user) {

            if ($user->role != $request->role) {

                $response = ['status' => 404, "message" =>'Role mismatch'];
                return response($response);

            }
            
            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken('Login User')->accessToken;

                
                $user->token = $token;
                

                // return response()->json($tempUser);

                if (isset($request['fb_token']) || isset($request['ios_token'])) {
                    $user->api_tokens()->create(['fb_token' => @$request['fb_token'], 'ios_token' => @$request['ios_token']]);
                }

                $user = (object)$this->setData($user->toArray());

                
                
                $response = ['status' => 200, 'message' => 'User Logged In!', 'data' =>['user' => $user]];

                return response($response);
            } else {

                $response = ["status" => 401, "message" => "Password mismatch"];
                return response($response);
            }
        } else {
            $response = ['status' => 404,"message" =>'User does not exist'];
            return response($response);
        }
    }

    protected function setData($value)
    {
        array_walk_recursive($value, function (&$item, $key) {
            
            $item = null === $item ? '' : $item;

            // if ($key == "city" || $key == "price") {
            //     $item = $item == '' ? null : (int)$item;
            // }

            if ($key == "city_details") {
                $city_details = array("id" => 0,  "name"=> "","price"=> 0,"display"=> 0);
                $item = $item == '' ? $city_details : $item;
            }
            
            if (is_numeric($key)) {
                $item = (int)$item;
            }

        });

        $this->data = $value;
        return $this->data;
    }

    public function logout (Request $request) {
        $token = Auth::user()->token();
        $token->revoke();
        $response = ['status' => 200, 'message' => 'You have been successfully logged out!'];
        return response($response);
    }

    public function user(Request $request){
        $user = Auth::user();
        // $user->orders_count = $user->customer_orders->count();
        $user->token = $request->bearerToken();

        // foreach ($user->customer_orders as $key => $order) {
        //     # code...
        // }

        $user = (object)$this->setData($user->toArray());

        
        return response()->json(['status' =>200, 'message' => "User Details", 'data' =>['user' => $user]]);
    }
    
    public function resend_verification_mail(Request $request)
    {
        $user = Auth::user();
        
        if ($user->email_verified_at != '') {

            return response(['status' => 422, 'message' => 'Already Verified', 'data' => [ 'errors' => ['User Account is already Verified']]]);

        }

        do{

           $randomToken = rand(100000,999999);

        }while(!empty(User::where('otp', $randomToken)->first()));

        $user->otp = $randomToken;
        $user->save();

        // $user->notify(new SignupActivate($user));

        return response()->json(['status' => 200, 'message' => 'Verification Token sent to Email']);
    }
    
    public function verify_account(Request $request)
    {

        if (Auth::user()->email_verified_at != NULL) {
            return response()->json(['status' => 200, 'message' => 'Already Verified']);
        }

        $validationArray =  [
                                'token' => 'required|string'
                            ];
        
        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()){

            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $verifyUser = User::where([['otp', $request->token], ['email', Auth::user()->email]])->first();

        if (!$verifyUser){

            return response()->json(['status' => 404, 'message' => 'Invalid Token.']);
        }
        
        if (Carbon::parse($verifyUser->updated_at)->addMinutes(30)->isPast()) {

            
            return response()->json(['status' => 404, 'message' => 'Token Expired.']);

        }

        $user = User::where('email', $verifyUser->email)->first();        

        if (!$user){
            return response()->json(['status' => 404, 'message' => 'We can\'t find a user with that e-mail address.']);        
        }

        $user->otp = '';
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();        

        // $user->wishlist = json_decode($user->wishlist);
        // $user->coupons = json_decode($user->coupons);
        // $user->orders_count = $user->orders->count();
        // $user->city_name = isset($user->city_details) ? $user->city_details->name : '';
        $user->token = $request->bearerToken();

        $user = (object)$this->setData($user->toArray());

        return response()->json(['status' => 200, 'message' => 'User Verified Successfully', 'data' =>['user' => $user]]);
    }
}
