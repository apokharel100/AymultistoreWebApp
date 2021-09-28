@extends('layouts.app')
@section('title', 'Check Out')
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Checkout</h2>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Checkout</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="checkout-area">
        <div class="container">
            <form action="{{ route('place-order') }}" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-12">
                        
                        <div class="checkbox-form">
                            <h3>Billing Details</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Full Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-name" placeholder="Billing Name" value="{{ old('billing_name') ? old('billing_name') : (isset($billing_address->name) ? $billing_address->name : '') }}" name="billing_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Email Address <span class="required">*</span></label>
                                        <input type="email" class="form-control" id="input-billing-email" placeholder="Billing Email" value="{{ old('billing_email') ? old('billing_email') : (isset($billing_address->email) ? $billing_address->email : '') }}" name="billing_email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Phone Number <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-phone" placeholder="Billing Phone" value="{{ old('billing_phone') ? old('billing_phone') : (isset($billing_address->phone) ? $billing_address->phone : '') }}" name="billing_phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Street Address <span class="required">*</span><span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-street-address" placeholder="Street Address" value="{{ old('billing_street_address') ? old('billing_street_address') : (isset($billing_address->street_address) ? $billing_address->street_address : '') }}" name="billing_street_address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Apartment #/ Suite / Building </label>
                                        <input type="text" class="form-control" id="input-billing-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('billing_apt_ste_bldg') ? old('billing_apt_ste_bldg') : (isset($billing_address->apt_ste_bldg) ? $billing_address->apt_ste_bldg : '') }}" name="billing_apt_ste_bldg">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Town / City <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-city" placeholder="Billing City" value="{{ old('billing_city') ? old('billing_city') : (isset($billing_address->city) ? $billing_address->city : '') }}" name="billing_city" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Zip Code <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('billing_zip_code') ? old('billing_zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="billing_zip_code" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Country <span class="required">*</span></label>
                                        <select class="form-control billing_shipping_country" style="font-size: 0.8rem;" data-state-input-id="input-billing-state" data-state-id="{{ old('billing_state') ? old('billing_state') : (isset($billing_address->state) ? $billing_address->state : 0) }}" id="input-billing-country" name="billing_country" required>

                                            <option value="" selected disabled> --- Please Select --- </option>
                                            @php
                                                $billingCountry = old('billing_country') ? old('billing_country') : (isset($billing_address->country) ? $billing_address->country : '');
                                            @endphp

                                            @foreach($db_countries as $country)
                                                <option <?=$billingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label class="customer-details">Region/State <span class="required">*</span></label>
                                        <select class="form-control" style="font-size: 0.8rem;" id="input-billing-state" name="billing_state" required>
                                            <option value="" selected disabled> --- Please Select --- </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="different-address">
                                <div class="ship-different-title">
                                    <h3>
                                        <label class="customer-details">Ship to a different address?</label>
                                        <input name="different_address" value="1" id="ship-box" type="checkbox" {{ old('different_address') == 1 ? 'checked' : ( Auth::check() ? isset($shipping_address) ? '' : 'checked' : '') }}>
                                    </h3>
                                </div>
                                <div id="ship-box-info" class="row">

                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Full Name <span class="required">*</span></label>
                                            <input type="text" class="form-control shipping-address" id="input-shipping-name" placeholder="Shipping Name" value="{{ old('shipping_name') ? old('shipping_name') : (isset($shipping_address->name) ? $shipping_address->name : '') }}" name="shipping_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Email Address <span class="required">*</span></label>
                                            <input type="email" class="form-control shipping-address" id="input-shipping-email" placeholder="Shipping Email" value="{{ old('shipping_email') ? old('shipping_email') : (isset($shipping_address->email) ? $shipping_address->email : '') }}" name="shipping_email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Phone Number <span class="required">*</span></label>
                                            <input type="text" class="form-control shipping-address" id="input-shipping-phone" placeholder="Shipping Phone" value="{{ old('shipping_phone') ? old('shipping_phone') : (isset($shipping_address->phone) ? $shipping_address->phone : '') }}" name="shipping_phone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Street Address</label>
                                            <input type="text" class="form-control shipping-address" id="input-shipping-street-address" placeholder="Street Address" value="{{ old('shipping_street_address') ? old('shipping_street_address') : (isset($shipping_address->street_address) ? $shipping_address->street_address : '') }}" name="shipping_street_address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Apartment #/ Suite / Building </label>
                                            <input type="text" class="form-control shipping-address" id="input-shipping-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('shipping_apt_ste_bldg') ? old('shipping_apt_ste_bldg') : (isset($shipping_address->apt_ste_bldg) ? $shipping_address->apt_ste_bldg : '') }}" name="shipping_apt_ste_bldg">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Town / City <span class="required">*</span></label>
                                            <input type="text" class="form-control shipping-address" id="input-shipping-city" placeholder="Shipping City" value="{{ old('shipping_city') ? old('shipping_city') : (isset($shipping_address->city) ? $shipping_address->city : '') }}" name="shipping_city" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Zip Code <span class="required">*</span></label>
                                            <input type="text" class="form-control shipping-address" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('shipping_zip_code') ? old('shipping_zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="shipping_zip_code" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Country <span class="required">*</span></label>
                                            <select class="form-control shipping-address billing_shipping_country" style="font-size: 0.8rem;" data-state-input-id="input-shipping-state" data-state-id="{{ old('shipping_state') ? old('shipping_state') : (isset($shipping_address->state) ? $shipping_address->state : 0) }}" id="input-shipping-country" name="shipping_country" required>

                                                <option value="" selected disabled> --- Please Select --- </option>
                                                @php
                                                    $shippingCountry = old('shipping_country') ? old('shipping_country') : (isset($shipping_address->country) ? $shipping_address->country : '');
                                                @endphp

                                                @foreach($db_countries as $country)
                                                    <option <?=$shippingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label class="customer-details">Region/State <span class="required">*</span></label>
                                            <select class="form-control shipping-address" style="font-size: 0.8rem;" id="input-shipping-state" name="shipping_state" required>
                                                <option value="" selected disabled> --- Please Select --- </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-notes">
                                    <div class="checkout-form-list checkout-form-list-2">
                                        <label class="customer-details">Order Notes</label>
                                        <textarea name="additional_message" id="checkout-mess" cols="30" rows="10" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old("additional_message") ? old("additional_message") : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="coupon-all">
                                            <div class="coupon">
                                                <input id="couponCode" class="input-text"  placeholder="Coupon code" type="text">
                                                <input id="applyCoupon" class="button"  value="Apply coupon" type="button">
                                                @if($available_coupons->count() > 0)
                                                    <div class="clearfix"></div>
                                                    <a href="javascript:void(0)" id="show-coupons" class="d-block">
                                                        <p class="text-right my-2"><i>Show Available Coupons</i></p>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="your-order">
                            <h3>Your order</h3>
                            <div class="your-order-table table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-left customer-details"><strong>Product</strong></th>
                                            <th class="text-right customer-details"><strong>Unit Price</strong></th>
                                            <th class="text-right customer-details"><strong>Total</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_price = 0;
                                        @endphp
                                        @foreach($cart as $key => $item)
                                            @php
                                                $cProd = \App\Models\Product::where("id", $item["product_id"])->first();
                                                $total_price += $item['sub_total'];
                                            @endphp
                                            <tr class="cart_item">
                                                <td style="padding: 0.5rem;" class="cart-product-name text-left" width="50%">
                                                    <a href="{{ route('product-details',['slug' => $cProd->slug]) }}">
                                                        <strong>{{ $cProd->title }}</strong>
                                                    </a>
                                                    <small class="product-quantity">× {{ $item['ordered_qty'] }}</small>

                                                    @if($item['color_code'] != NULL)
                                                        <p class="cartdetail" style="justify-content: left; margin-bottom: 0px;">
                                                            <span>Color: <span class="cart-color" style="background-color: {{ $item['color_code'] }};"></span>
                                                            </span>
                                                            @if($item['size_name'] != NULL)
                                                            <span>Size: <span class="cart-size"><strong>{{ $item['size_name'] }}</strong></span>
                                                            </span>
                                                            @endif
                                                        </p>

                                                    @endif
                                                </td>
                                                <td class="text-right" style="padding: 0.5rem;">Nrs.{{ (float)$item['sub_total']/(float)$item['ordered_qty'] }}</td>
                                                <td style="padding: 0.5rem;" class="cart-product-total text-right">
                                                    <span class="amount">Nrs.{{ (float)$item['sub_total'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="cart-subtotal">
                                            <th class="text-right" colspan="2">Sub Total</th>
                                            <td class="text-right">
                                                <span>Nrs.<span class="cart-total-price">{{ $total_price }}</span></span>
                                            </td>
                                        </tr>
                                        <tr id="couponDetails" style="display: {{ isset($coupon_details['title']) ? '' : 'none'; }};">
                                            <th class="text-right" colspan="2">
                                                <span id="couponTitle">{{ isset($coupon_details['title']) ? $coupon_details['title'] : ''; }}</span> 
                                            </th> 
                                            <td class="text-right">
                                                <span>
                                                    Nrs.<span id="discountAmount">{{ isset($coupon_details['discount_amount']) ? $coupon_details['discount_amount'] : ''; }}</span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th class="text-right" colspan="2">Grand Total</th>
                                            <td class="text-right">
                                                <strong>
                                                    <input type="hidden" id="subTotalPrice" value="{{ $total_price }}">
                                                    <span>Nrs.<span id="grandTotalPrice" class="cart-total-price">{{ $total_price }}</span></span>
                                                    @php
                                                        session()->put('total_price',$total_price);
                                                    @endphp
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="payment-method">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4>Payment Options</h4>
                                        
                                        @for($i=1; $i<=count($payment_methods); $i++)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment-method-{{ $i }}" value="{{ $i }}" {{ $i == 1 ? 'checked' : 'disabled' }}>
                                            <label class="form-check-label" for="payment-method-{{ $i }}">
                                                {{ $payment_methods[$i] }}
                                                {!! $i != 1 ? '<small><i>(Coming Soon)</i></small>' : '' !!}
                                            </label>
                                        </div>
                                        @endfor

                                    </div>
                                </div>
                                <div class="payment-accordion">
                                    <div class="order-button-payment">
                                        <input value="Place order" type="submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="coupons-all">
        <div class="container-fluid">
            <h6 class="text-white">Available Coupon Codes <span class="close-coupon float-right"><i class="fa fa-times"></i></span></h6>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    @foreach($available_coupons as $key => $coupon)
                    <div class="coupon-main">
                        <div class="coupon-left">
                            <p><strong>{{ $coupon->discount_percentage }}%</strong>OFF</p>
                        </div>
                        <div class="coupon-center">
                            <p>Get an exclusive discont of {{ $coupon->discount_percentage }}% upto Nrs.{{ $coupon->max_discount }} off with this coupon on any purchase over Nrs.{{ $coupon->min_spend }}</p>
                        </div>
                        <div class="coupon-right">
                            <p class="coupon-code">
                                <small>Code</small>
                                {{ $coupon->code }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@push('post-scripts')
    <script>
        // Apply Coupon
        $('#applyCoupon').click(function (e) {
            e.preventDefault();

            if ($("#couponCode").val() == '') {
                toastr['error']('Please enter valid Coupon Code!');
                return;
            }

            $.ajax({
                url : "{{ URL::route('apply-coupon') }}",
                type: "POST",
                data: {
                        '_token' : '{{ csrf_token() }}',
                        code : $("#couponCode").val(),
                        sub_total : $("#subTotalPrice").val(),
                        action: 'apply_coupon'
                    },
                beforeSend: function () {

                },
                success: function (response) {
                    console.log("success");
                    console.log("response " + response);
                    var obj = jQuery.parseJSON(response);

                    if (obj.status == 'calculated') {   

                        toastr['success']('Coupon Code Applied Successfully!');

                        $('#couponTitle').html(obj.title);
                        $('#discountAmount').html(obj.discount_amount);
                        $('#grandTotalPrice').html(obj.total_price);
                        $("#couponDetails").show();
                        
                    }else if(obj.status == 'invalid_date'){
                        toastr['error']('Coupon is already expired!');

                    }else if(obj.status == 'invalid_code'){

                        
                        toastr['error']('Invalid Coupon Code');
                    }else if(obj.status == 'already_used'){

                        
                        toastr['error']('Coupon Already Used!');
                    }else if(obj.status == 'no_coupons'){

                        
                        toastr['error']('Sorry! All the Coupons have been Used up!');
                    }else if(obj.status == 'auth_failed'){

                        
                        toastr['error']('Please Login to Apply Coupon!');
                    }else if(obj.status == 'min_spend_invalid'){

                        
                        toastr['error']('Please Spend upto Nrs.'+obj.min_spend +' to apply this code!');
                    };
                }
            });

        });

        if ($('#ship-box').is(':checked')){
            $("#ship-box-info").slideDown();
            $('.shipping-address').attr('disabled',false);
            $('.shipping-address').attr('required',true);
        }else {
            $("#ship-box-info").slideUp();
            $('.shipping-address').attr('disabled',true);
            $('.shipping-address').attr('required',false);

        }

        $('#ship-box').click(function () {
            // $("#ship-box-info").slideToggle();

            if (!this.checked) {
                $("#ship-box-info").slideUp();
                $('.shipping-address').attr('disabled',true);
                $('.shipping-address').attr('required',false);

                // alert($('#cust_region').val());
                $('#input-shipping-name').val($('#input-billing-name').val());
                $('#input-shipping-email').val($('#input-billing-email').val());
                $('#input-shipping-phone').val($('#input-billing-phone').val());
                $('#input-shipping-street-address').val($('#input-billing-street-address').val());
                $('#input-shipping-apt-ste-bldg').val($('#input-billing-apt-ste-bldg').val());
                $('#input-shipping-city').val($('#input-billing-city').val());
                $('#input-shipping-zip-code').val($('#input-billing-zip-code').val());
                $('#input-shipping-country').val($('#input-billing-country').val());
                
                call_ajax_function($('#input-billing-country').val(), $('#input-billing-state').val(), 'input-shipping-state');
                // $('#input-shipping-state').val($('#input-billing-state').val());
                
            } else {
                $("#ship-box-info").slideDown();
                $('.shipping-address').attr('disabled',false);
                $('.shipping-address').attr('required',true);
                @guest
                    $('#input-shipping-name').val('{{ old('billing_name') }}');
                    $('#input-shipping-email').val('{{ old('billing_email') }}');
                    $('#input-shipping-phone').val('{{ old('billing_phone') }}');
                    $('#input-shipping-street-address').val('{{ old('billing_street_address') }}');
                    $('#input-shipping-apt-ste-bldg').val('{{ old('billing_apt_ste_bldg') }}');
                    $('#input-shipping-city').val('{{ old('billing_city') }}');
                    $('#input-shipping-zip-code').val('{{ old('billing_zip_code') }}');
                    $('#input-shipping-country').val('{{ old('billing_state') }}');
                    $('#input-shipping-state').val('{{ old('billing_country') }}');
                @else
                    $('#input-shipping-name').val('{{ isset($shipping_address) ? $shipping_address->name : '' }}');
                    $('#input-shipping-email').val('{{ isset($shipping_address) ? $shipping_address->email : '' }}');
                    $('#input-shipping-phone').val('{{ isset($shipping_address) ? $shipping_address->phone : '' }}');
                    $('#input-shipping-street-address').val('{{ isset($shipping_address) ? $shipping_address->street_address : '' }}');
                    $('#input-shipping-apt-ste-bldg').val('{{ isset($shipping_address) ? $shipping_address->apt_ste_bldg : '' }}');
                    $('#input-shipping-city').val('{{ isset($shipping_address) ? $shipping_address->city : '' }}');
                    $('#input-shipping-zip-code').val('{{ isset($shipping_address) ? $shipping_address->zip_code : '' }}');
                    $('#input-shipping-country').val('{{ isset($shipping_address) ? $shipping_address->country : '' }}');

                    call_ajax_function('{{ isset($shipping_address) ? $shipping_address->country : 0}}', '{{ isset($shipping_address) ? $shipping_address->state : 0 }}', 'input-shipping-state');
                @endguest
                
            }
        });

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

        $("#show-coupons").click(function(e){
            e.preventDefault();
            $("body").addClass("show-coupons");
            $("#coupons-all").addClass("show");
        });
        
        $(".close-coupon").click(function(e){
            e.preventDefault();
            $("body").removeClass("show-coupons");
            $("#coupons-all").removeClass("show");
        });
    </script>
@endpush