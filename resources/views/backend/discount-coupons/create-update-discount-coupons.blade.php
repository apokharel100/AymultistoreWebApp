@extends('backend.layouts.headerfooter')
@section ('title', 'Discount Coupons')
@section('content')
    
    <!-- Discount Coupon Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Discount Coupon Header (Page header) -->
        <section class="content-header">
            <h1>
                Discount Coupons
                <small>
                    List | Add | Update | Delete Discount Coupons
                </small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.discount-coupons.index') }}"><i class="fa fa-rebel"></i> Discount Coupons</a></li>
                    <li class="active">{{ request()->routeIs('admin.discount-coupons.edit') ? 'Update Discount Coupon' : 'Add New Discount Coupon' }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add || Edit Discount Coupon's Details</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ request()->routeIs('admin.discount-coupons.edit') ? route('admin.discount-coupons.update',$discount_coupon) : route('admin.discount-coupons.store') }}" enctype="multipart/form-data">
                    
                            @csrf

                            @if(request()->routeIs('admin.discount-coupons.edit'))
                                @method('PUT')
                            @endif
                            
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-1">

                                            <span class="input-group-addon"><i class="fa fa-text-width"></i>  Coupon Name</span>
                                            <input type="text" class="form-control" value="{{ old('name') ? old('name') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->name : '')  }}" name="name" placeholder="Enter Coupon Name Here.." required />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; Code</span>
                                            <input type="text" name="code" class="form-control"  required value="{{ old('code') ? old('code') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->code : '')  }}" placeholder="eg: DASHAIN2020">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; Min Spend</span>
                                            <input type="text" name="min_spend" class="form-control"  required value="{{ old('min_spend') ? old('min_spend') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->min_spend : '')  }}" placeholder="eg: 2000">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; Max Discount</span>
                                            <input type="text" name="max_discount" class="form-control"  required value="{{ old('max_discount') ? old('max_discount') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->max_discount : '')  }}" placeholder="eg: 500">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; Discount %</span>
                                            <input type="text" name="discount_percentage" class="form-control"  required value="{{ old('discount_percentage') ? old('discount_percentage') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->discount_percentage : '')  }}" placeholder="eg: 50">
                                            <span class="input-group-addon">%</span>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            <span class="input-group-addon">
                                                @php 
                                                    $display =  old('display') ? old('display') : (request()->routeIs('admin.products.edit') ? $product->display : 1 );
                                                @endphp
                                                <input type="checkbox" name="display" value="1" {{ $display == 1 ? 'checked' : '' }}>
                                            </span>
                                            <input type="button " class="form-control bg-indigo text-muted" value="Display" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; Start Date Time</span>
                                            <input type="text" class="form-control" value="{{ old('start_date') ? old('start_date') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->start_date : '')  }}" name="start_date" placeholder="Start Date" required id="startDate" />

                                            <input type="time" class="form-control" value="{{ old('start_time') ? old('start_time') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->start_time : '')  }}" name="start_time" required />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-text-width"></i> &nbsp; End Date Time</span>
                                            <input type="text" class="form-control" value="{{ old('expire_date') ? old('expire_date') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->expire_date : '')  }}" name="expire_date" placeholder="End Date" required id="endDate" />

                                            <input type="time" class="form-control" value="{{ old('expire_time') ? old('expire_time') : (request()->routeIs('admin.discount-coupons.edit') ? $discount_coupon->expire_time : '')  }}" name="expire_time" required />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="box-footer">

                                <?php if (request()->routeIs('admin.discount-coupons.edit')) { ?>
                                    <a href="{{ route('admin.discount-coupons.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submitEdit" class="btn btn-primary pull-right">UPDATE DISCOUNT COUPON
                                    </button>
                                <?php } else { ?>
                                    <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submit" class="btn btn-success pull-right">SAVE DISCOUNT COUPON
                                    </button>
                                <?php } ?>
                            </div>
                            
                        </form>
                        <!-- form ends -->
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>
    

@endsection

@push('scripts')
    
    <script>
        $("#startDate").datepicker({
            startDate:"today",
            autoclose:true,
            format:"yyyy-mm-dd"
        });

        if ($("#discountCouponId").val() != '') {
            $("#endDate").datepicker({
                startDate: $("#startDate").val(),
                autoclose:true,
                format:"yyyy-mm-dd"
            });        
        }
        $("#startDate").change(function(){
            if($('#endDate').val() < $(this).val()){
                $("#endDate").val('');
            }
            
            $("#endDate").datepicker('destroy').datepicker({
                startDate: $(this).val(),
                autoclose:true,
                format:"yyyy-mm-dd"
            });
        });
    </script>
@endpush