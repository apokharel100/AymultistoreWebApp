@extends('layouts.app')
@section('title', Auth::user()->name.' - Saved Addresses')
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Saved Addresses</h2>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Saved Addresses</li>
                </ul>
            </div>
        </div>
    </div>

    <main class="page-content">
        <div class="account-page-area" style="padding-top: 50px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <ul class="nav myaccount-tab-trigger">
                            <li class="nav-item">
                                <a class="nav-link d-flex flex-row justify-content-between align-items-center" href="{{ route('customer.my-account') }}">Dashboard <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.orders') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Orders <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link active d-flex flex-row justify-content-between align-items-center">Addresses <i class="fa fa-chevron-right text-right"></i></a>
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
                            <div class="tab-pane show active">
                                <div class="myaccount-address">
                                    <p>The following addresses will be used on the checkout page by default.</p>
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="small-title"><strong>Billing Adress</strong></h4>
                                            <hr>
                                            @if($billing_address)
                                            <address>
                                                <b>{{ $billing_address->name }}</b><br>
                                                <i>{{ $billing_address->email }}</i>, {{ $billing_address->phone }}<br>
                                                @if($billing_address->apt_ste_bldg != '' )
                                                {{ $billing_address->apt_ste_bldg }}<br>
                                                @endif
                                                {{ $billing_address->street_address }}<br>
                                                {{ $billing_address->city }}, {{ DB::table('states')->where('id', $billing_address->state)->first()->name }}
                                                {{ $billing_address->zip_code }}<br>
                                                {{ DB::table('countries')->where('id', $billing_address->country)->first()->name }}
                                                
                                            </address>
                                            @else
                                                <p>You haven't updated Billing Details</p>
                                            @endif
                                            <hr>
                                            <a href="#" data-toggle="modal" data-target="#billingAddress"
                                                style="color:#ff4433"><strong>Update Billing Address</strong></a>
                                        </div>
                                        <div class="col">
                                            <h4 class="small-title"><strong>Shipping Address</strong></h4>
                                            <hr>
                                            @if($shipping_address)
                                            <address>
                                                <b>{{ $shipping_address->name }}</b><br>
                                                <i>{{ $shipping_address->email }}</i>, {{ $shipping_address->phone }}<br>
                                                @if($shipping_address->apt_ste_bldg != '' )
                                                {{ $shipping_address->apt_ste_bldg }}<br>
                                                @endif
                                                {{ $shipping_address->street_address }}<br>
                                                {{ $shipping_address->city }}, {{ DB::table('states')->where('id', $shipping_address->state)->first()->name }}
                                                {{ $shipping_address->zip_code }}<br>
                                                {{ DB::table('countries')->where('id', $shipping_address->country)->first()->name }}
                                                
                                            </address>
                                            @else
                                                <p>You haven't updated Shipping Details</p>
                                            @endif
                                            <hr>
                                            <a href="#" data-toggle="modal" data-target="#shippingAddress"
                                                style="color:#ff4433"><strong>Edit Shipping Address</strong></a>
                                        </div>
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

    <!-- Modal -->
    <div class="modal fade" id="billingAddress" tabindex="-1" role="dialog" aria-labelledby="billingAddressLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="billingAddressLabel">Update Billing Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('customer.create-update-addresses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="address_type" value="billing">
                        <div class="checkbox-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>Full Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-name" placeholder="Billing Name" value="{{ old('name') ? old('name') : (isset($billing_address->name) ? $billing_address->name : '') }}" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Email Address <span class="required">*</span></label>
                                        <input type="email" class="form-control" id="input-billing-email" placeholder="Billing Email" value="{{ old('email') ? old('email') : (isset($billing_address->email) ? $billing_address->email : '') }}" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Phone Number <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-phone" placeholder="Billing Phone" value="{{ old('phone') ? old('phone') : (isset($billing_address->phone) ? $billing_address->phone : '') }}" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Street Address</label>
                                        <input type="text" class="form-control" id="input-billing-street-address" placeholder="Street Address" value="{{ old('street_address') ? old('street_address') : (isset($billing_address->street_address) ? $billing_address->street_address : '') }}" name="street_address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Apartment #/ Suite / Building </label>
                                        <input type="text" class="form-control" id="input-billing-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('apt_ste_bldg') ? old('apt_ste_bldg') : (isset($billing_address->apt_ste_bldg) ? $billing_address->apt_ste_bldg : '') }}" name="apt_ste_bldg">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Town / City <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-city" placeholder="Billing City" value="{{ old('city') ? old('city') : (isset($billing_address->city) ? $billing_address->city : '') }}" name="city" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Zip Code <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('zip_code') ? old('zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="zip_code" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Country <span class="required">*</span></label>
                                        <select class="form-control billing_shipping_country" style="font-size: 0.8rem;" data-state-input-id="input-billing-state" data-state-id="{{ old('state') ? old('state') : (isset($billing_address->state) ? $billing_address->state : 0) }}" id="input-billing-country" name="country" required>

                                            <option value="" selected disabled> --- Please Select --- </option>
                                            @php
                                                $billingCountry = old('country') ? old('country') : (isset($billing_address->country) ? $billing_address->country : '');
                                            @endphp

                                            @foreach($db_countries as $country)
                                                <option <?=$billingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Region/State <span class="required">*</span></label>
                                        <select class="form-control" style="font-size: 0.8rem;" id="input-billing-state" name="state" required>
                                            <option value="" selected disabled> --- Please Select --- </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="order-button-payment">
                                <input  type="submit" value="Update Address" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="shippingAddress" tabindex="-1" role="dialog" aria-labelledby="shippingAddressLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingAddressLabel">Update Shipping Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('customer.create-update-addresses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="address_type" value="shipping">
                        <div class="checkbox-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>Full Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-shipping-name" placeholder="Shipping Name" value="{{ old('name') ? old('name') : (isset($shipping_address->name) ? $shipping_address->name : '') }}" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Email Address <span class="required">*</span></label>
                                        <input type="email" class="form-control" id="input-shipping-email" placeholder="Shipping Email" value="{{ old('email') ? old('email') : (isset($shipping_address->email) ? $shipping_address->email : '') }}" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Phone Number <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-shipping-phone" placeholder="Shipping Phone" value="{{ old('phone') ? old('phone') : (isset($shipping_address->phone) ? $shipping_address->phone : '') }}" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Street Address</label>
                                        <input type="text" class="form-control" id="input-shipping-street-address" placeholder="Street Address" value="{{ old('street_address') ? old('street_address') : (isset($shipping_address->street_address) ? $shipping_address->street_address : '') }}" name="street_address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Apartment #/ Suite / Building </label>
                                        <input type="text" class="form-control" id="input-shipping-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('apt_ste_bldg') ? old('apt_ste_bldg') : (isset($shipping_address->apt_ste_bldg) ? $shipping_address->apt_ste_bldg : '') }}" name="apt_ste_bldg">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Town / City <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-shipping-city" placeholder="Shipping City" value="{{ old('city') ? old('city') : (isset($shipping_address->city) ? $shipping_address->city : '') }}" name="city" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Zip Code <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('zip_code') ? old('zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="zip_code" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Country <span class="required">*</span></label>
                                        <select class="form-control billing_shipping_country" style="font-size: 0.8rem;" data-state-input-id="input-shipping-state" data-state-id="{{ old('state') ? old('state') : (isset($shipping_address->state) ? $shipping_address->state : 0) }}" id="input-shipping-country" name="country" required>

                                            <option value="" selected disabled> --- Please Select --- </option>
                                            @php
                                                $shippingCountry = old('country') ? old('country') : (isset($shipping_address->country) ? $shipping_address->country : '');
                                            @endphp

                                            @foreach($db_countries as $country)
                                                <option <?=$shippingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Region/State <span class="required">*</span></label>
                                        <select class="form-control" style="font-size: 0.8rem;" id="input-shipping-state" name="state" required>
                                            <option value="" selected disabled> --- Please Select --- </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="order-button-payment">
                                <input  type="submit" value="Update Address" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('post-scripts')
    <script>
        $('.billing_shipping_country').change(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            // alert($('#'+state_input_id).val());
            // return;

            call_ajax_function(country_id, state_id, state_input_id);
            
            // alert($(this).data('state-input-id'));
        });

        $('.billing_shipping_country').each(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            call_ajax_function(country_id, state_id, state_input_id);
        });

        function call_ajax_function(country_id, state_id, state_input_id) {
            $.ajax({
                url : "{{ URL::route('get-states') }}",
                type: "POST",
                data: {
                        '_token' : '{{ csrf_token() }}',
                        country_id : country_id,
                        state_id : state_id
                    },
                beforeSend: function () {

                },
                success: function (response) {
                    
                   $('#'+state_input_id).html(response); 
                }
            });
        }
    </script>
@endpush