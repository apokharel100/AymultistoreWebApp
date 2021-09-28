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
                <li class="active"><i class="fa fa-eyedropper"></i> Discount Coupons</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div id="listDiscount Coupon">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">View All Discount Coupons |  
                            <a href="{{ route('admin.discount-coupons.create') }}" class="btn btn-primary" title="Add New Discount Coupon">
                                <i class="fa fa-plus"></i> Add Discount Coupon
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SN.</th>
                                        <th>Coupon Name</th>
                                        <th>Coupon Code</th>
                                        <th>Start Date/Time</th>
                                        <th>Expire Date/Time</th>
                                        <th>Min Spend</th>
                                        <th>Max Discount</th>
                                        <th>Discount %</th>
                                        <th>Display</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($discount_coupons as $key => $discount_coupon)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td>
                                            <b>{{ $discount_coupon->name }}</b>&nbsp;|&nbsp; 
                                            <small>
                                                <i>
                                                    @if($discount_coupon->display == 1)
                                                    
                                                        <i style="color: green;" class="fa fa-eye"></i>
                                                    
                                                    @else
                                                    
                                                        <i style="color: red;" class="fa fa-eye-slash"></i>
                                                    @endif

                                                </i>
                                            </small>
                                            @if($discount_coupon->expire_date < date('Y-m-d'))
                                            <span class="badge badge-danger">Expired</span>
                                            @endif

                                        </td>
                                        <td>
                                            {{ $discount_coupon->code }}
                                        </td>
                                        <td>
                                            {{ $discount_coupon->start_date.' '. date('g:i A',strtotime($discount_coupon->start_time)) }}  
                                        </td>
                                        <td>
                                            {{ $discount_coupon->expire_date.' ' .date('g:i A',strtotime($discount_coupon->expire_time)) }}  
                                        </td>
                                        <td>
                                            {{ $discount_coupon->min_spend }}  
                                        </td>

                                        <td>
                                            {{ $discount_coupon->max_discount }}  
                                        </td>

                                        <td>
                                            {{ $discount_coupon->discount_percentage }}  
                                        </td>

                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" data-discount-coupon-id="{{ $discount_coupon->id }}" class="lv-btn display-btn" <?=$discount_coupon->display == 1 ? 'checked' : '' ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        
                                        <td>
                                            <span class="content-right">

                                                <a href="{{ route('admin.discount-coupons.edit', base64_encode($discount_coupon->id)) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                                
                                                
                                                <a href="#delete"
                                                   data-toggle="modal"
                                                   data-id="{{ $discount_coupon->id }}"
                                                   id="delete{{ $discount_coupon->id }}"
                                                   title="Delete" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="delete_menu('{{ base64_encode($discount_coupon->id) }}')"><i class="fa fa-trash  "></i>
                                               </a>
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="submit" class="btn btn-block btn-default">Save All</button>
                                        </td>
                                    </tr>
                                </tfoot> --}}
                            </table>
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

    <script>

        /*Delete Menu*/
        function delete_menu(id) {
            var conn = './discount-coupons/delete/' + id;
            $('#delete a').attr("href", conn);
        }
        /*Delete Menu Ends*/
        
    </script>
    <!--    Initialize Multi Select   -->

    <!-- DELETE MODAL STARTS -->
    <div class="modal fade modal-danger" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Discount Coupon</h4>
                </div>
                <div class="modal-body">
                    <p>Are You Sure&hellip;?</p>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer">
                        <a class="btn btn-outline" href="" onclick="">Delete</a>
                        <a data-dismiss="modal" class="btn btn-outline pull-left" href="#">Cancel</a>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- DELETE MODAL ENDS -->
@endsection