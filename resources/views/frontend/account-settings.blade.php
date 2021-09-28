@extends('layouts.app')
@section('title', Auth::user()->name.' - Account Information')
@section('content')
	<div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <h2>Account Details</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li class="active">Account Details</li>
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
                                    <a class="nav-link d-flex flex-row justify-content-between align-items-center" href="javascript:void(0)">Dashboard <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.orders') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Orders <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.addresses') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Addresses <i class="fa fa-chevron-right text-right"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customer.settings') }}" class="nav-link active d-flex flex-row justify-content-between align-items-center">Account Details <i class="fa fa-chevron-right text-right"></i></a>
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
                                <div class="tab-pane show active">
                                    <div class="myaccount-details">
                                        <form action="{{ route('customer.update-account-settings') }}" class="kenne-form" method="POST">
                                            @csrf
                                            <div class="kenne-form-inner">
                                                <div class="single-input single-input-half">
                                                    <label for="account-details-firstname">First Name*</label>
                                                    <input name="name" type="text" id="account-details-firstname" value="{{ $customer->name }}" required>
                                                </div>
                                                <div class="single-input single-input-half">
                                                    <label for="account-details-email">Email*</label>
                                                    <input type="email" id="account-details-email" value="{{ $customer->email }}" disabled>
                                                </div>
                                                
                                                <div class="single-input">
                                                    <label for="account-details-oldpass">Current Password</label>
                                                    <input name="old_password" type="password" id="account-details-oldpass">
                                                    <small>Leave Blank to leave unchanged</small>
                                                </div>
                                                <div class="single-input">
                                                    <label for="account-details-newpass">New Password </label>
                                                    <input name="password" type="password" id="account-details-newpass">
                                                </div>
                                                <div class="single-input">
                                                    <label for="account-details-confpass">Confirm New Password</label>
                                                    <input name="password_confirmation" type="password" id="account-details-confpass">
                                                </div>
                                                
                                                <div class="single-input">
                                                    <button class="kenne-btn kenne-btn_dark" type="submit"><span>SAVE CHANGES</span></button>
                                                </div>
                                            </div>
                                        </form>
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