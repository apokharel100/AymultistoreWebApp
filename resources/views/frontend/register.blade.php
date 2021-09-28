@extends('layouts.app')
@section('title','Register')
@section('content')
	<div class="breadcrumb-area">
	    <div class="container">
	        <div class="breadcrumb-content">
	            <h2>User Registration</h2>
	            <ul>
	                <li><a href="{{ route('home') }}">Home</a></li>
	                <li class="active">Register User</li>
	            </ul>
	        </div>
	    </div>
	</div>

	<div class="kenne-login-register_area">
	    <div class="container">
	        <div class="row">

	            <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12 offset-lg-3">
	                <form action="{{ url('register') }}" method="POST">
	                	@csrf
	                    <div class="login-form">
	                        <h4 class="login-title">Register</h4>
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Full Name</label>
	                                <input name="name" value="{{ old('name') }}" type="text" placeholder="Full Name" required>
	                            </div>
	                            <div class="col-md-12">
	                                <label>Email Address*</label>
	                                <input name="email" value="{{ old('email') }}" type="email" placeholder="Email Address" required>
	                            </div>
	                            <div class="col-md-6">
	                                <label>Password</label>
	                                <input name="password" type="password" placeholder="Password" required>
	                            </div>
	                            <div class="col-md-6">
	                                <label>Confirm Password</label>
	                                <input name="password_confirmation" type="password" placeholder="Confirm Password" required>
	                            </div>
	                            <div class="col-12">
	                                <button type="submit" class="kenne-register_btn">Register</button>
	                            </div>
	                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection