@extends('layouts.app')
@section('title', Auth::user()->name.' - Order #'.$order->order_no)
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
            <div class="container">
                <div class="breadcrumb-content">
                    <h2>Order - #{{ $order->order_no }}</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                        <li class="active">Order - #{{ $order->order_no }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <main class="page-content">
            <div class="account-page-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <ul class="nav myaccount-tab-trigger">
                                <li class="nav-item">
                                    <a href="{{ route('customer.my-account') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Dashboard <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active d-flex flex-row justify-content-between align-items-center">Order <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.addresses') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Addresses <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.settings') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Account Details <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.wishlist') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Wishlist<i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link d-flex flex-row justify-content-between align-items-center">Logout <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-9">
                            <a href="{{ route('customer.orders') }}">
                                <i class="fa fa-chevron-left"></i> Go Back to My Orders
                            </a>
                            <hr>
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="kenne-product-thumbnail"><strong>images</strong></th>
                                            <th class="cart-product-name"><strong>Product</strong></th>
                                            <th class="kenne-product-price"><strong>Unit Price</strong></th>
                                            <th class="kenne-product-quantity"><strong>Quantity</strong></th>
                                            <th class="kenne-product-subtotal"><strong>Total</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                        @endphp
                                        @foreach($order->ordered_products as $key => $ordered_product)
                                            @php
                                            
                                            $product = \App\Models\Product::where("id", $ordered_product->product_id)->first();

                                            $totalPrice += $ordered_product->sub_total;

                                            @endphp
                                            <tr style="{{ !isset($product) ? 'background-color: #ead7ce;'  : '' }}">
                                                <td class="kenne-product-thumbnail">
                                                    @if(isset($product))
                                                            
                                                        <a target="_blank" href="{{ route('product-details', ['slug' => $product->slug]) }}" >

                                                            <img src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}" class="img-thumbnail" alt="{{ $product->slug }}" width="50">

                                                        </a>

                                                    @else

                                                        <img src="https://place-hold.it/100x100/eeeef5?text=Image%20Unavailable&fontsize=8&italic&bold" width="50">

                                                    @endif
                                                </td>
                                                <td class="kenne-product-name" style="line-height: 1.2">
                                                    @if(isset($product))

                                                        <a target="_blank" href="{{ url('product/'.$product->slug) }}">
                                                            <b>{{ $product->title }}</b> 
                                                        </a>
                                                        <br>
                                                        <small>{{ $product->sku }}</small>
                                                    @else
                                                        <b>{{ $ordered_product->product_title }}</b> 

                                                    @endif
                                                    
                                                    <br>
                                                    @if($ordered_product->size_id != 0)
                                                        <p style="display: inline-block; font-size: 12px; margin-bottom: 0px;">
                                                            Size : {{ strtoupper($ordered_product->size_name) }}
                                                        </p>
                                                        <br>
                                                    @endif

                                                    @if($ordered_product->color_id != 0)
                                                        <span style="font-size: 12px;">
                                                            Color : 
                                                            <p style="margin-bottom: 0; display: inline-block; height: 12px; width: 12px; background: {{ $ordered_product->color_code }};"></p>

                                                            <small style="display: inline-block; color: #778787; ">
                                                                {{ strtoupper($ordered_product->color_name) }}
                                                            </small>
                                                        </span>
                                                    @endif
                                                    <br>

                                                    @if(!isset($product))
                                                        <i style="font-size: 11px;">Product has been Deleted</i>
                                                    @endif
                                                </td>
                                                <td class="kenne-product-price">
                                                    <span class="amount">Nrs.{{ $ordered_product->sub_total/$ordered_product->quantity }}</span>
                                                </td>
                                                <td class="quantity">
                                                    <span>{{(int)$ordered_product->quantity}}</span>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="amount">Nrs.{{ $ordered_product->sub_total }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ml-auto">
                                    <div class="cart-page-total">
                                        <h2>Order totals</h2>
                                        <ul>
                                            <li>
                                                Subtotal <span>Nrs.{{ number_format($totalPrice, 2) }}</span>
                                            </li>
                                            @if(isset($order->applied_coupon))
                                                <li>
                                                    {{ $order->applied_coupon->coupon_title }} 
                                                    <span>- Nrs.{{ number_format($order->applied_coupon->discount_amount, 2) }}</span>
                                                </li>
                                                @php
                                                    $totalPrice = $totalPrice - $order->applied_coupon->discount_amount;
                                                @endphp
                                            @endif
                                            <li>
                                                Grand Total <span>Nrs.{{  number_format($totalPrice ,2)  }}</span>
                                            </li>
                                            <hr>
                                            <li>Order Status <span>{{ $order_status[$order->status][0] }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kenne's Account Page Area End Here -->
        </main>

@endsection