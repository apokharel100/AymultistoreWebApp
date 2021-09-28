@extends('layouts.app')
@section('title', 'Shopping Cart')
@section('content')
	<div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Cart</h2>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Shopping Cart</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Begin Uren's Cart Area -->
    <div class="kenne-cart-area">
        <div class="container">
            <div class="row">
                <div class="col-12" id="cartTable">
                    @if(count($cart) > 0)
                        <form action="{{ route('update-cart') }}">
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="kenne-product-remove">remove</th>
                                            <th class="kenne-product-thumbnail" width="10%">images</th>
                                            <th class="cart-product-name">Product</th>
                                            <th class="kenne-product-price">Unit Price</th>
                                            <th class="kenne-product-quantity">Quantity</th>
                                            <th class="kenne-product-subtotal">Total</th>
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

                                            switch ($cProd->variation_type) {
                                                case 0:
                                                    $max_order_qty = $cProd->stock_count;
                                                    break;

                                                case 1:
                                                    $max_order_qty = $cProd->product_colors()->where('product_colors.id', $item['color_id'])->first()->stock_count;
                                                    break;

                                                case 2:
                                                    $max_order_qty = $cProd->product_sizes()->where('product_sizes.id', $item['size_id'])->first()->stock_count;
                                                    break;
                                                
                                                default:
                                                    $max_order_qty = 1;
                                                    break;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="kenne-product-remove">
                                                <a href="javascript:void(0)" title="Remove this Item" onclick="cartDelete('{{ $key }}')"><i class="fa fa-trash" title="Remove"></i></a>
                                            </td>
                                            <td class="kenne-product-thumbnail">
                                                <a href="{{ route('product-details',['slug' => $cProd->slug]) }}">
                                                    <img src="{{ asset('storage/products/'.$cProd->slug.'/thumbs/thumb_'.$cProd->image) }}" alt="{{ $cProd->slug }}">
                                                </a>
                                            </td>
                                            <td class="kenne-product-name">
                                                <a href="{{ route('product-details',['slug' => $cProd->slug]) }}">{{ $cProd->title }}</a>

                                                @if($item['color_code'] != NULL)
                                                    <p class="cartdetail" >
                                                        <span>Color: <span class="cart-color" style="background-color: {{ $item['color_code'] }};"></span>
                                                        </span>
                                                        @if($item['size_name'] != NULL)
                                                        <span>Size: <span class="cart-size"><strong>{{ $item['size_name'] }}</strong></span>
                                                        </span>
                                                        @endif
                                                    </p>

                                                @endif
                                            </td>
                                            <td class="kenne-product-price">
                                                <span class="amount">Nrs.{{ (float)$item['sub_total']/(float)$item['ordered_qty'] }}</span>
                                            </td>
                                            <td class="quantity">
                                                
                                                <div class="cart-inc-dec">
                                                    <input class="cart-inc-dec-box ordered_qty" data-cart-key="{{ $key }}" data-product-id="{{ $item['product_id'] }}" data-color-id="{{ $item['color_id'] }}" data-size-id="{{ $item['size_id'] }}" value="{{(int)$item['ordered_qty']}}" type="text" max="{{ $max_order_qty }}">
                                                    <div class="dec update-item-quantity"><i class="fa fa-angle-down"></i></div>
                                                    <div class="inc update-item-quantity"><i class="fa fa-angle-up"></i></div>
                                                </div>
                                            </td>
                                            <td class="product-subtotal"><span class="amount">Nrs.<span id="cartItemSubtotal{{ $key }}">{{ (float)$item['sub_total'] }}</span></span></td>
                                        </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td class="product-subtotal text-right" colspan="5">Sub Total</td>
                                            <td class="product-subtotal">
                                                <span>Nrs.<span class="cart-total-price">{{ $total_price }}</span></span>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    
                                </div>
                                <div class="col-md-5 ml-auto">
                                    <div class="cart-page-total">
                                        <h2>Cart totals</h2>
                                        <ul>
                                            <li>Subtotal <span>Nrs.<span class="cart-total-price">{{ $total_price }}</span></span></li>

                                            <li>
                                                Total 
                                                <span>Nrs.
                                                    <input type="hidden" id="subTotalPrice" value="{{ $total_price }}">
                                                    <span id="grandTotalPrice" class="cart-total-price">{{ $total_price }}</span>
                                                    {{-- @php
                                                        session()->put("total_price", $total_price);
                                                        session()->save();
                                                    @endphp --}}
                                                </span>
                                            </li>
                                        </ul>
                                        <a href="{{ route('checkout') }}">Proceed to checkout</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <h3>No items in the cart!!! </h3>
                    <a href="{{ route('home') }}" class="btn btn-modern btn-rounded btn-yellow mb-2">Continue Shopping</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Uren's Cart Area End Here -->

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


        // Update Cart Quantity
        $('.update-item-quantity').on('click', function () {

            var $button = $(this);
            var ordered_qty_input = $button.parent().find('input');
            var oldValue = ordered_qty_input.val();

            if ($button.hasClass('inc')) {

                var newVal = parseFloat(oldValue) + 1;
                if (newVal > ordered_qty_input.attr('max')) {
                    newVal = ordered_qty_input.attr('max');
                }

            } else {
                // Don't allow decrementing below zero
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            $button.parent().find('input').val(newVal);

            var product_id = ordered_qty_input.data('product-id');
            var color_id = ordered_qty_input.data('color-id');
            var size_id = ordered_qty_input.data('size-id');
            var qty = ordered_qty_input.val();
            var cart_key = ordered_qty_input.data('cart-key');
            update_cart_item_quantity(product_id, color_id, size_id, qty, cart_key);
        });

        $(".ordered_qty").change(function(){

            product_id = $(this).data('product-id');
            color_id = $(this).data('color-id');
            size_id = $(this).data('size-id');
            qty = $(this).val();
            cart_key = $(this).data('cart-key');
            
            update_cart_item_quantity(product_id, color_id, size_id, qty, cart_key);           
        });

        function update_cart_item_quantity(product_id, color_id, size_id, qty, cart_key) {
            // alert(cart_key)
            $.ajax({
                url : "{{ URL::route('update-cart') }}",
                type : "POST",
                data : {
                    '_token': '{{ csrf_token() }}',
                    product_id: product_id,
                    color_id: color_id,
                    size_id: size_id,
                    qty: qty
                },
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function(response){
                    // console.log(response.responseText);
                    var obj = jQuery.parseJSON(response.responseText);

                    if (obj.status=='success') {

                        $('.item-count').html(obj.total_qty);
                        $('.cart-total-price').html(obj.total_price);
                        $("#subTotalPrice").val(obj.total_price);
                        $('#miniCart').load(document.URL + ' #miniCart>*');
                        $('#cartItemSubtotal'+cart_key).html(obj.item_sub_total);

                        // $('#cartTable').load(document.URL + ' #cartTable>*');

                        toastr['success']('Cart Updated!');
                        // alert('Item Added to Cart');
                    }else if(obj.status == 'stockerror') {

                        var stock = obj.stock;
                        var in_cart = obj.in_cart;

                        toastr["error"]("<span>Available : "+stock+"</span><br><span>In Cart : "+in_cart+"</span>", "Out of Stock!");
                        // alert("<span>Available : "+stock+"</span><br><span>In Cart : "+in_cart+"</span>", "Out of Stock!");
                    }

                    $('#modal-loader').hide();
                },
                error : function ($responseObj){
                    $('#modal-loader').hide();
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            }); 

        }
    </script>
@endpush