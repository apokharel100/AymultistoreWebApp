@extends('backend.layouts.headerfooter')
@section ('title', 'Products')
@section('content')

	<!-- Product Wrapper. Contains page content -->
	<div class="content-wrapper">
	    <!-- Product Header (Page header) -->
	    <section class="content-header">
	        <h1>
	            Products
	            <small>
	                List | Add | Update | Delete Products
	            </small>
	        </h1>
	        <ol class="breadcrumb">
	            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	            <li class="active"><i class="fa fa-shopping-cart"></i> Products</li>
	        </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">

	        <!-- Default box -->
	        <div id="listProduct">
	            <div class="box box-default box-solid">
	                <div class="box-header with-border">
	                    <h3 class="box-title">View All Products |  
	                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" title="Add New Product">
	                            <i class="fa fa-plus"></i> Add Product
	                        </a>
	                    </h3>

	                    <div class="box-tools pull-right">
	                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
	                            <i class="fa fa-minus"></i>
	                        </button>
	                    </div>
	                </div>

	                <div class="box-body">
	                	<table id="products-list" class="table table-bordered table-striped">
	                		<thead>
	                			<tr>
	                				<th width="4%">SN.</th>
	                				<th width="40%">Product Name</th>
	                				<th width="">Price</th>
	                				<th width="">Categories</th>
	                				<th width="">Stock</th>
	                				<th width="10%">Action</th>
	                			</tr>
	                		</thead>
	                		<tbody>
	                			@foreach($products as $key => $product)
	                			<tr>
	                				<td class="text-center">{{ $key+1 }}</td>
	                				<td>
	                					<img width="5%" class="img-thumbnail" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}">
	                					<b>{{ $product->title }}</b>&nbsp;|&nbsp; 
	                					<small>{{ $product->sku }}</small>
	                					<small>
	                					    <i>
	                					        @if($product->display == 1)
	                					        
	                					            <i style="color: green;" class="fa fa-eye"></i>
	                					        
	                					        @else
	                					        
	                					            <i style="color: red;" class="fa fa-eye-slash"></i>
	                					        @endif

	                					        @if($product->featured == 1)
	                					        
	                					            <i style="color: #a8a800;" class="fa fa-star"></i>
	                					        
	                					        @endif

	                					    </i>
	                					</small>

	                				</td>
	                				<td>
	                					Nrs. {{ $product->discounted_price == '' || $product->discounted_price == 0 ? $product->price : $product->discounted_price }}</td>
	                				<td>
	                					@foreach($product->categories as $pCat)
	                						<span class="label label-default">{{ $pCat->title }}</span>
	                					@endforeach

	                					{{-- @if($product->display == 1)
	                						<span class="label label-success">Active</span>
	                					@else
	                						<span class="label label-default">InActive</span>
	                					@endif --}}
	                				</td>
	                				<td>
	                					@if($product->stock_status == 1)
	                						<span class="label label-success">Available</span>
	                					@else
	                						<span class="label label-danger">Out of Stock</span>
	                					@endif
	                				</td>
	                				
	                				<td>
	                					<span class="content-right">
	                					    <a href="{{ url('admin') }}" class="btn btn-sm btn-success" title="View Product on Website"><i class="fa fa-share"></i></a>

	                					    <a href="{{ route('admin.products.edit', base64_encode($product->id)) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
	                					    
	                					    
	                					    <a href="#delete"
	                					       data-toggle="modal"
	                					       data-id="{{ $product->id }}"
	                					       id="delete{{ $product->id }}"
	                					       title="Delete" 
	                					       class="btn btn-sm btn-danger"
	                					       onclick="delete_product('{{ base64_encode($product->id) }}')"><i class="fa fa-trash  "></i>
	                					   </a>
	                					</span>
	                				</td>
	                			</tr>
	                			@endforeach
	                		</tbody>
	                	</table>
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
                    <h4 class="modal-title">Delete Products</h4>
                </div>
                <div class="modal-body">
                    <p>Deleting this file will also delete all the Products, Images and all associated data.</p>
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

		$('#products-list').DataTable({
		    "paging": true,
		    "lengthChange": true,
		    "searching": true,
		    "ordering": true,
		    "info": true,
		    "autoWidth": false,
		    "lengthMenu": [25, 50, 100, 500 ]
		});

        function delete_product(id) {
            var conn = './products/delete/' + id;
            $('#delete a').attr("href", conn);
        }
        /*Delete Menu Ends*/

	</script>
@endpush