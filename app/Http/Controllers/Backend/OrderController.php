<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
use App\Services\ModelHelper;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        $order_status = Order::order_status();

        return view('backend.orders.list-orders', compact('orders','order_status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $order = Order::where('order_no', $order_no)->firstOrFail();
        $order_status = Order::order_status();
        $billing_details = json_decode($order->billing_details);
        $shipping_details = json_decode($order->shipping_details);

        return view('backend.orders.view-order-details', compact('order', 'order_status', 'billing_details', 'shipping_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function change_order_status(Request $request)
    {
        $order = Order::where('id', $request->id)->first();
        
        $order->status = $request->status;
        $statusChanged = $order->save();

        if ($statusChanged) {
            
            $data = array('status'=> 'success');
            $ordered_products = $order->ordered_products()->update(['status' => $request->status]);

        }else{

            $data = array('status'=> 'error');
        }

        echo json_encode($data);

    }
}
