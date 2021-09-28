@extends('backend.layouts.headerfooter')
@section ('title', 'Order - #'.$order->order_no)
@section('content')

	<!-- Order Wrapper. Contains page content -->
	<div class="content-wrapper">
	    <!-- Order Header (Page header) -->
	    <section class="content-header">
	        <h1>
	            Order - #{{ $order->order_no }}
	        </h1>
	        <ol class="breadcrumb">
	            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	            <li><a href="{{ route('admin.orders.index') }}"><i class="fa fa-opencart"></i> Orders</a></li>
	            <li class="active">#{{ $order->order_no }}</li>
	        </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">

	        <!-- Default box -->
	        <div id="listOrder">
	            <div class="box box-default box-solid">
	                <div class="box-header with-border">
	                    <h3 class="box-title">View Order Details</h3>

	                    <div class="box-tools pull-right">
	                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
	                            <i class="fa fa-minus"></i>
	                        </button>
	                    </div>
	                </div>

	                <div class="box-body">
	                	<div class="row">
	                	    <div class="col-md-12">
	                	        <div class="box box-default box-solid">

	                	            <div class="box-header with-border">
	                	                <h5 class="box-title">
	                	                    <h3 class="box-title">Product Details</h3>

	                	                    <div class="btn-group pull-right" role="group">

	                	                        <button id="status-all" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                	                            Change Status for all product <span class="caret"></span>
	                	                        </button>

	                	                        <ul class="dropdown-menu" aria-labelledby="status-all">
	                	                            @for($i = 0; $i < count($order_status); $i++)
	                	                                <li>
	                	                                	<a href="javascript:void(0)" class="order-status-btn" data-order-id="{{ $order->id }}" data-status="{{ $i }}" href="">{{ $order_status[$i][0] }}</a>
	                	                                </li>
	                	                            @endfor

	                	                        </ul>
	                	                    </div>
	                	                </h5>
	                	            </div>
	                	            <div class="box-body">

	                	                <div class="table-responsive">
	                	                    <table class="table header-border table-hover" id="orderTable">
	                	                        <thead>
	                	                            <tr>
	                	                                
	                	                                <th>SN</th>
	                	                                <th></th>
	                	                                <th><strong>Product Name</strong></th>
	                	                                <th><strong>Quantity</strong></th>
	                	                                <th class="text-right"><strong>Price</strong></th>
	                	                                <th class="text-right"><strong>Sub Total</strong></th>
	                	                                <th class="text-center"><strong>Status</strong></th>
	                	                                {{-- <th>Action</th> --}}
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

	                	                            <tr>
	                	                                <th>{{ $key+1 }}.</th>
	                	                                <td class="text-center">

	                	                                    @if(isset($product))
	                	                                    
	                	                                        <a target="_blank" href="{{ route('product-details', ['slug' => $product->slug]) }}" >

	                	                                        	<img src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}" class="img-thumbnail" alt="{{ $product->slug }}" width="50">

	                	                                        </a>

	                	                                    @else

	                	                                        <img src="https://place-hold.it/100x100/eeeef5?text=Image%20Unavailable&fontsize=8&italic&bold" width="50">

	                	                                    @endif
	                	                                </td>
	                	                                
	                	                                <td class="text-left" >
	                	                                	
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
	                	                                    	<p style="display: inline-block; font-size: 16px;">
	                	                                    		Size : {{ strtoupper($ordered_product->size_name) }}
	                	                                    	</p>
	                	                                    	<br>
	                	                                    @endif

	                	                                    @if($ordered_product->color_id != 0)
		                	                                    Color : 
		                	                                    <p style="margin-bottom: 0; display: inline-block; height: 16px; width: 16px; background: {{ $ordered_product->color_code }};"></p>

		                	                                    <small style="display: inline-block; color: #778787; ">
		                	                                    	{{ strtoupper($ordered_product->color_name) }}
		                	                                    </small>
	                	                                    @endif
	                	                                    <br>

	                	                                    @if(!isset($product))
	                	                                        <i style="font-size: 11px;">Product has been Deleted</i>
	                	                                    @endif
	                	                                    


	                	                                </td>

	                	                                <td class="text-center" >
	                	                                    <b>{{(int)$ordered_product->quantity}}</b>
	                	                                </td>

	                	                                <td class="text-right" >
	                	                                    <strong>
	                	                                        Nrs.{{ $ordered_product->sub_total/$ordered_product->quantity }}
	                	                                    </strong>
	                	                                </td>
	                	                                
	                	                                <td class="text-right" >
	                	                                    <strong>
	                	                                        Nrs.{{ $ordered_product->sub_total }}
	                	                                    </strong>
	                	                                </td>

	                	                                <td class="ordered-product-status text-center" id="orderedProductStatus{{ $ordered_product->id }}" width="10%">
	                	                                    <small class="label label-{{ $order_status[$ordered_product->status][1] }}" >
	                	                                        {{ $order_status[$ordered_product->status][0] }}
	                	                                    </small>
	                	                                </td>

	                	                            </tr>
	                	                            
	                	                        @endforeach
	                	                        <tr>
	                	                            <td colspan="5" class="text-right">Sub Total </td>
	                	                            <th class="text-right">Nrs.{{ number_format($totalPrice, 2) }}</th>
	                	                            <td colspan="3"></td>
	                	                        </tr>

	                	                        @if(isset($order->applied_coupon))
	                	                            <tr>
	                	                                <td colspan="5" class="text-right">
	                	                                	{{ $order->applied_coupon->coupon_title }} 
	                	                                </td>
	                	                                <th class="text-right">
	                	                                	- Nrs.{{ number_format($order->applied_coupon->discount_amount, 2) }}
	                	                                </th>
	                	                                <td colspan="3"></td>
	                	                            </tr>

	                	                            @php
	                	                                $totalPrice = $totalPrice - $order->applied_coupon->discount_amount;
	                	                            @endphp
	                	                        @endif

	                	                        <tr>
	                	                            <td colspan="5" class="text-right"> Grand Total </td>
	                	                            <th class="text-right">Nrs.{{  number_format($totalPrice ,2)  }}</th>
	                	                            <td colspan="3"></td>
	                	                        </tr>
	                	                        </tbody>
	                	                    </table>
	                	                </div>
	                	            </div>
	                	        </div>
	                	    </div>
	                	</div>
	                	<div class="row" style="font-size: 12px !important;">

	                	    <div class="col-md-6">
	                	        <div class="box box-default box-solid">
	                	            <div class="box-header with-border">
	                	                <h5 class="text-white text-center">Billing Details</h5>
	                	            </div>
	                	            <div class="box-body ">
	                	                <strong>{{ $billing_details->billing_name }}</strong><br>
	                	                {{ $billing_details->billing_email }}<br>
	                	                {{ $billing_details->billing_phone }}<br>

	                	                @if($billing_details->billing_apt_ste_bldg != '')
	                	                {{ $billing_details->billing_apt_ste_bldg }}<br>
	                	                @endif
	                	                
	                	                {{ $billing_details->billing_street_address }}<br>
	                	                
	                	                {{ $billing_details->billing_city }},
	                	                
	                	                {{ DB::table('states')->where('id',$billing_details->billing_state)->first()->name }}
	                	                
	                	                {{ $billing_details->billing_zip_code }}<br>
	                	                
	                	                {{ DB::table('countries')->where('id',$billing_details->billing_country)->first()->name }}

	                	            </div>
	                	        </div>
	                	    </div>
	                	    <div class="col-md-6">
	                	        <div class="box box-default box-solid">
	                	            <div class="box-header with-border">
	                	                <h5 class="text-white text-center">Shipping Details</h5>
	                	            </div>
	                	            <div class="box-body">
	                	                <strong>{{ $shipping_details->shipping_name }}</strong><br>
	                	                {{ $shipping_details->shipping_email }}<br>
	                	                {{ $shipping_details->shipping_phone }}<br>

	                	                @if($shipping_details->shipping_apt_ste_bldg != '')
	                	                {{ $shipping_details->shipping_apt_ste_bldg }}<br>
	                	                @endif

	                	                {{ $shipping_details->shipping_street_address }}<br>
	                	                
	                	                {{ $shipping_details->shipping_city }},
	                	                
	                	                {{ DB::table('states')->where('id', $shipping_details->shipping_state)->first()->name }}

	                	                {{ $shipping_details->shipping_zip_code }}<br>

	                	                {{ DB::table('countries')->where('id', $shipping_details->shipping_country)->first()->name }}
	                	                
	                	            </div>
	                	        </div>
	                	    </div>
	                	</div>
	                </div>
	                <!-- /.box-body -->
	            </div>
	            <!-- /.box -->
	        </div>

	    </section>
	    <!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<!-- DELETE MODAL STARTS -->
    <div class="modal fade modal-danger" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Orders</h4>
                </div>
                <div class="modal-body">
                    <p>Deleting this file will also delete all the Orders, Images and all associated data.</p>
                    <p>Are You Sure&hellip;?</p>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer">
                        <a class="btn btn-outline" href="" onclick="">Delete</a>
                        <a data-dismiss="modal" class="btn btn-outline pull-left" href="javascript:void(0)">Cancel</a>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- DELETE MODAL ENDS -->
@endsection

@push('scripts')
	<script>
		$(".order-status-btn").click(function(){
		    var status = $(this).data('status');
		    var order_id = $(this).data('order-id');

		    $.ajax({
		        url : "{{ URL::route('admin.orders.change-order-status') }}",
		        type : "POST",
		        data :{ '_token': '{{ csrf_token() }}',
		                id: order_id,
		                status: status
		            },
		        beforeSend: function(){                

		        },
		        success : function(response)
		        {
		            console.log("response "+ response);
		            var obj = jQuery.parseJSON( response);

		            if (obj.status == 'success') {
		                
		                
		                $('#orderTable').load(document.URL + ' #orderTable');
		                
		                if (status == 0) {
		                	toastr['warning']('Order Status changed to Pending!');
		                }else if (status == 1) {
		                	toastr['info']('Order is On Process!');
		                }else if (status == 2) {
		                	toastr['success']('Order Delivered Successfully!');
		                }else if (status == 3) {
		                	toastr['error']('Order is Cancelled!');
		                }
		                

		            }else {

		                toastr['error']('Something went wrong!');
		                

		            };
		        }
		    });
		});

	</script>
@endpush