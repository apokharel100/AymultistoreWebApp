@extends('layouts.app')
@section('title', Auth::user()->name.' - Orders')
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
            <div class="container">
                <div class="breadcrumb-content">
                    <h2>Orders</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                        <li class="active">Orders</li>
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
                                    <a class="nav-link active d-flex flex-row justify-content-between align-items-center">Orders <i class="fa fa-chevron-right text-right"></i></a>
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
                            <div class="tab-content myaccount-tab-content">
                                <div class="tab-pane show active">
                                    <div class="myaccount-orders">
                                        <h4 class="small-title">MY ORDERS</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th>ORDER #</th>
                                                        <th>Ordered Date</th>
                                                        <th>Status</th>
                                                        <th>Total Price</th>
                                                        <th></th>
                                                    </tr>
                                                    @foreach($orders as $key => $order)
                                                    <tr>
                                                        <td class="text-center">
                                                            <a href="{{ route('customer.view-order', base64_encode($order->order_no)) }}">
                                                                <strong>#{{ $order->order_no }}</strong>
                                                            </a>
                                                        </td>
                                                        <td><small>{{ date('jS M, Y H:i:s', strtotime($order->created_at)) }}</small></td>
                                                        <td>
                                                            <small class="label label-{{ $order_status[$order->status][1] }}" >
                                                                {{ $order_status[$order->status][0] }}
                                                            </small>
                                                        </td>
                                                        <td>Nrs.{{ $order->total_price }}</td>
                                                        <td>
                                                            <a class="kenne-btn kenne-btn_sm" href="{{ route('customer.view-order', base64_encode($order->order_no)) }}">
                                                                <span>View</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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