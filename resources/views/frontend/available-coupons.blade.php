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

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="coupon-main all-coupon">
                    <div class="coupon-left">
                        <p><strong>25%</strong>OFF</p>
                    </div>
                    <div class="coupon-center">
                        Get 25% off of all the deals going till the end of this month
                    </div>
                    <div class="coupon-right">
                        <p class="coupon-code">
                            <small>Code</small>
                            GY233A0
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="coupon-main all-coupon">
                    <div class="coupon-left">
                        <p><strong>25%</strong>OFF</p>
                    </div>
                    <div class="coupon-center">
                        Get 25% off of all the deals going till the end of this month
                    </div>
                    <div class="coupon-right">
                        <p class="coupon-code">
                            <small>Code</small>
                            GY233A0
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="coupon-main all-coupon">
                    <div class="coupon-left">
                        <p><strong>25%</strong>OFF</p>
                    </div>
                    <div class="coupon-center">
                        Get 25% off of all the deals going till the end of this month
                    </div>
                    <div class="coupon-right">
                        <p class="coupon-code">
                            <small>Code</small>
                            GY233A0
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="coupon-main all-coupon">
                    <div class="coupon-left">
                        <p><strong>25%</strong>OFF</p>
                    </div>
                    <div class="coupon-center">
                        Get 25% off of all the deals going till the end of this month
                    </div>
                    <div class="coupon-right">
                        <p class="coupon-code">
                            <small>Code</small>
                            GY233A0
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection