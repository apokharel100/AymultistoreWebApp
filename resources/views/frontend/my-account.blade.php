@extends('layouts.app')
@section('title', Auth::user()->name.' - Profile')
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
            <div class="container">
                <div class="breadcrumb-content">
                    <h2>My Account</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li class="active">My Account</li>
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
                                    <a class="nav-link active d-flex flex-row justify-content-between align-items-center" href="javascript:void(0)">Dashboard <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.orders') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Orders <i class="fa fa-chevron-right text-right"></i></a>
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
                            <div class="tab-content myaccount-tab-content" id="account-page-tab-content">
                                <div class="tab-pane fade show active" id="account-dashboard" role="tabpanel"
                                    aria-labelledby="account-dashboard-tab">
                                    <div class="myaccount-dashboard">
                                        <p>Hello <b>{{ $customer->name }}</b> , Welcome!
                                        </p>
                                        <hr>
                                        <ul>
                                            <li><strong>Name:</strong> {{ $customer->name }}</li>
                                            <li><strong>Email:</strong> {{ $customer->email }}</li>
                                        </ul>
                                        <hr>
                                        <p>
                                        	From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="{{ route('customer.settings') }}" style="opacity:0.75;"><strong>edit your password and account details</strong></a>.</p>



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