@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ url('login') }}" method="POST">
            	@csrf
                <div class="login-form">
                    <h4 class="login-title">Login</h4>
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <label>Email Address*</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="col-12 mb--20">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="col-md-8">
                            <div class="check-box">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember_me">Remember me</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                        	@if (Route::has('password.request'))
                        	    <div class="forgotton-password_info">
                                    <a href="{{ route('password.request') }}"> Forgotten password?</a>
                                </div>
                        	@endif
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="kenne-login_btn">Login</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
