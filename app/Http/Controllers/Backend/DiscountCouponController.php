<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreDiscountCouponRequest;
use App\Http\Requests\UpdateDiscountCouponRequest;

class DiscountCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discount_coupons = DiscountCoupon::all();
        return view('backend.discount-coupons.list-discount-coupons',compact('discount_coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.discount-coupons.create-update-discount-coupons');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountCouponRequest $request)
    {

        $insertArray = array(
                'name' => $request->name,
                'display' => isset($request->display) ? $request->display : 0,
                'code' => $request->code,
                'min_spend' => $request->min_spend,
                'max_discount' => $request->max_discount,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'expire_date' => $request->expire_date,
                'expire_time' => $request->expire_time,
                'created_by' => Auth::user()->name
        );

        $discount_coupon_created = DiscountCoupon::create($insertArray);

        if ($discount_coupon_created) {

            return redirect()->route('admin.discount-coupons.index')->with('status','Discount Coupon has been Added Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount_coupon = DiscountCoupon::find(base64_decode($id));
        return view('backend.discount-coupons.create-update-discount-coupons',compact('discount_coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountCouponRequest $request, DiscountCoupon $discount_coupon)
    {
        // dd($_POST);
        $updateArray = array(
                'name' => $request->name,
                'display' => isset($request->display) ? $request->display : 0,
                'code' => $request->code,
                'min_spend' => $request->min_spend,
                'max_discount' => $request->max_discount,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'expire_date' => $request->expire_date,
                'expire_time' => $request->expire_time,
                'updated_by' => Auth::user()->name,
                'updated_at' => date('Y-m-d h:i:s')
        );

        $discount_coupon_updated = $discount_coupon->update($updateArray);

        if ($discount_coupon_updated) {

            return redirect()->route('admin.discount-coupons.index')->with('status','Discount Coupon has been Updated Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
