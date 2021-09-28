@extends('backend.layouts.headerfooter')
@section ('title',  request()->routeIs('admin.products.edit') ? $product->title : 'Create New Product')
@push('post-css')
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
     <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/multiselect/css/multi-select.css') }}">
@endpush

@section('content')
	<?php
		function get_category_title($item, $itemTitle){

			$parent_category = \App\Models\Category::where('id', $item->parent_id)->first();
			
			if ($item->parent_id != 0) {

				$itemTitle = $parent_category->title.' → '.$itemTitle;
				$itemTitle = get_category_title($parent_category, $itemTitle);
			}

			return $itemTitle;

		}

        function displayCategories($list, $product_categories, $arrow_count = 0){
            foreach ($list as $item){

                // $parent = DB::table('categories')->where('id', $item->parent_id)->first();
                // $arrowText = $item->parent_id != 0 ? $parent->title : '';
                //$arrowText = '';
                //for ($i=0; $i < $arrow_count; $i++) { 
                  //  $arrowText .= '--';
                //}
                //$item->title = $arrowText.' '.$item->title;
                
                	
                $categoryTitle = get_category_title($item, $item->title);
                
                $selected = '';
                if ( in_array($item->id , $product_categories)) {
                    $selected = "selected";
                }
                
                if (property_exists($item, "children")){
                    $arrow_count++;
                    ?>
                    <optgroup label="<?=$categoryTitle ?>">
	                    <?php
	                    displayCategories( $item->children, $product_categories, $arrow_count);
	                    ?>
	                </optgroup>
                    <?php 
                    $arrow_count--;
                }else{
                	?>
                	<option <?=$item->child == 1 ? 'disabled' : 'style="color:#eb525d;"' ?> {{ $selected }} value="{{$item->id}}">
                		{{ $categoryTitle}}
                	</option>
                	<?php
                }
            }
        }

    ?>
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
	            <li><a href="{{ route('admin.products.index') }}"><i class="fa fa-shopping-cart"></i> Products</a></li>
	            <li class="active">{{ request()->routeIs('admin.products.edit') ? 'Update Product' : 'Add New Product' }}</li>
	        </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">

	        <div class="row">
	            <div class="col-md-12" id="addProduct">
	                <div class="box box-default box-solid">
	                    <div class="box-header with-border">

	                        <h3 class="box-title">Add || Edit Product's Details</h3>

	                    </div>
	                    <!-- /.box-header -->
	                    <!-- form start -->
	                    <form method="POST" action="{{ request()->routeIs('admin.products.edit') ? route('admin.products.update',$product) : route('admin.products.store') }}" enctype="multipart/form-data">
	                
	                        @csrf

	                        @if(request()->routeIs('admin.products.edit'))
	                            @method('PUT')
	                        @endif
	                        
	                        <div class="box-body">
	                            <div class="row">
	                            	<div class="col-md-4">
	                            	    <div class="input-group">
	                            	        <span class="input-group-addon"><i class="fa fa-info"></i> Product Title</span>
	                            	        <input type="text" class="form-control" value="{{ old('title') ? old('title') : (request()->routeIs('admin.products.edit') ? $product->title : '') }}" name="title" placeholder="Product Title" required/>
	                            	    </div>
	                            	</div>

	                            	<div class="col-md-4">
	                            	    <div class="input-group">
	                            	    	@php 
                            	    			$display =  old('display') ? old('display') : (request()->routeIs('admin.products.edit') ? $product->display : 1 );

                            	    			$featured =  old('featured') ? old('featured') : (request()->routeIs('admin.products.edit') ? $product->featured : 0 );
                            	    		@endphp
	                            	        <span class="input-group-addon">
	                            	            <input name="display" {{ $display == 1 ? 'checked' : '' }} value="1" type="checkbox">
	                            	        </span>
	                            	        <input type="text" value="Display" class="form-control" readonly="readonly">
	                            	        <span class="input-group-addon">
	                            	            <input name="featured" {{ $featured == 1 ? 'checked' : '' }} value="1" type="checkbox">
	                            	        </span>
	                            	        <input type="text" value="Featured" class="form-control" readonly="readonly">
	                            	    </div>
	                            	</div>

	                            	<div class="col-md-4">
	                            	    <div class="input-group">
	                            	        <span class="input-group-addon"><i class="fa fa-info"></i> SKU</span>
	                            	        <input type="text" class="form-control" value="{{ old('sku') ? old('sku') : (request()->routeIs('admin.products.edit') ? $product->sku : '') }}" name="sku" placeholder="Product's Stock Keeping Unit" required/>
	                            	    </div>
	                            	</div>

                            		<div class="col-md-4">
                            			<div class="input-group">
                            				<span class="input-group-addon">
                            					<span class="input-group-text"><i class="fa fa-money"></i> Price</span>
                            				</span>
                            				<input type="text" class="form-control form-control-sm do-discount-calculation" id="priceInput" name="price" placeholder="0.00" onkeypress="return isDecimalNumber(event,this)" value="{{ old('price') ? old('price') : (request()->routeIs('admin.products.edit') ? $product->price : '') }}" required>
                            			</div>
                            		</div>
                            		<div class="col-md-4">
                            			<div class="input-group">
                            				<span class="input-group-addon">
                            					<span class="input-group-text"><i class="fa fa-money"></i> Discounted Price</span>
                            				</span>
                            				<input type="text" class="form-control do-discount-calculation" id="discountedPriceInput" name="discounted_price"  value="{{ old('discounted_price') ? old('discounted_price') : (request()->routeIs('admin.products.edit') ? $product->discounted_price : '') }}" placeholder="0.00" onkeypress="return isDecimalNumber(event,this)">
                            			</div>
                            		</div>
                            		<div class="col-md-4">
                            			<div class="input-group">
                            				<span class="input-group-addon">
                            					<span class="input-group-text">Discount %</span>
                            				</span>
                            				<input type="text" class="form-control" id="discountPercentInput" readonly placeholder="0.00">
                            				<span class="input-group-addon">
                            					<span class="input-group-text"> %</span>
                            				</span>
                            			</div>
                            		</div>
                            	    
                            	    @php

                            	    	$display = old('display') ? old('display') : (request()->routeIs('admin.products.edit') ? $product->display : 1);

                            	    	$stock_status = old('stock_status') ? old('stock_status') : (request()->routeIs('admin.products.edit') ? $product->stock_status : 1);

                            	    	$brand_id = old('brand_id') ? old('brand_id') : (request()->routeIs('admin.products.edit') ? $product->brand_id : '');
                            	    @endphp

                            	    <div class="col-md-4">
                            	    	<style type="text/css">
                            	    		.bootstrap-tagsinput input{
                            	    			width: 315px !important;
                            	    		}
                            	    	</style>
                            	    	<div class="input-group">
                            	    		<span class="input-group-addon">
                            	                <span class="input-group-text"><i class="fa fa-bars"></i>&nbsp; Tags </span>
                            	            </span>
                            	    	    <input name="tags" type="text" id="tags" class="form-control form-control-inverse" placeholder="eg: shirts, pants" value="{{ old('tags') ? old('tags') : (request()->routeIs('admin.products.edit') ? $product->tags : '') }}">
                            	    	</div>
                            	    </div>

                            	    <div class="col-md-4">
                            	        <div class="input-group">
                            	            <span class="input-group-addon">
                            	                <span class="input-group-text"><i class="fa fa-database"></i>&nbsp; Stock </span>
                            	            </span>
                            	            <select class="form-control" name="stock_status" required>
                            	                <option value="1" {{ $stock_status == 1 ? 'selected' : '' }}>Available</option>
                            	                <option value="0" {{ $stock_status == 0 ? 'selected' : '' }}>Out of Stock</option>
                            	            </select>
                            	        </div>
                            	    </div>

                            	    <div class="col-md-4">
                            	        <div class="input-group">
                            	            <span class="input-group-addon">
                            	                <span class="input-group-text"><i class="fa fa-building"></i>&nbsp; Brand </span>
                            	            </span>
                            	            <select class="form-control" name="brand_id" required>
                            	            	<option value="0">None</option>
                            	            	@foreach($brands as $brand)
                            	                	<option value="{{ $brand->id }}" {{ $brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->title }}</option>
                            	                @endforeach
                            	            </select>
                            	        </div>
                            	    </div>

                            	    

	                            	<div class="col-md-12">
	                            		@if(request()->routeIs('admin.products.edit'))
	                            		
	                            		    @php
	                            		        $product_categories = $product->category_products()->pluck('category_id')->all();
	                            		        // dd($category_products);
	                            		    @endphp

	                            		    <div class="row">
	                            		        <div class="col-md-12">
	                            		            <div class="input-group">
	                            		                <span class="input-group-addon">
	                            		                    <i class="fa fa-anchor"></i> Select Categories
	                            		                </span>
	                            		                <select id="productIds" name="category_id[]" class="form-control" multiple data-allow-clear="true">
	                            		                	{{ displayCategories($categories, $product_categories) }}
	                            		                    {{-- @foreach($categories as $category)
	                            		                        <option {{ in_array($category->id, $product_categories) ? 'selected' : '' }}  value="{{ $category->id }}">{{ $category->title }} </option>
	                            		                    @endforeach --}}
	                            		                </select>
	                            		            </div>
	                            		        </div>
	                            		    </div>
	                            		@elseif(request()->routeIs('admin.products.create'))
	                            		    <div class="row">
	                            		        <div class="col-md-12">
                            		            <div class="input-group">
	                            		                <span class="input-group-addon">
	                            		                    <i class="fa fa-anchor"></i> Selecte Categories
	                            		                </span>
	                            		                <select id="productIds" name="category_id[]" class="form-control" multiple data-allow-clear="true">
	                            		                    {{ displayCategories($categories, []) }}
	                            		                </select>
	                            		            </div>
	                            		        </div>
	                            		    </div>
	                            		@endif
	                            	</div>

                            	    {{-- <div class="col-md-4">
                            	        <div class="input-group">
                            	            <span class="input-group-addon">
                            	                <span class="input-group-text"><i class="fa fa-database"></i>&nbsp; Stock Count</span>
                            	            </span>
                            	            <input type="text" class="form-control" name="stock_count" placeholder="Available Quantity"  value="{{ old('stock_count') ? old('stock_count') : (request()->routeIs('admin.products.edit') ? $product->stock_count : '') }}">
                            	        </div>
                            	        <hr>
                            	    </div> --}}
	                            </div>
	                            <div class="box box-default box-solid">
	                                <div class="box-header with-border">
                                		Enter <b>Color, Sizes, SKU </b> & <b> Stock Quantities</b> for the product.
	                                </div>
	                                <div class="box-body">
	                                	<div class="row">
	                                		<div class="col-md-4">
	                                			<div class="form-group mb-3">
	                                				@php
	                                				    $variationType = old('variation_type') ? old('variation_type') : (request()->routeIs('admin.products.edit') ? $product->variation_type : '');
	                                				    $variationNameArray = array("Has None", "Has Colors Only", "Has Colors & Sizes Both");
	                                				@endphp

	                                				<select id="variationType" class="form-control form-control-inverse" name="variation_type">
	                                				    @if(request()->routeIs('admin.products.edit'))
	                                				        <option data-flag="1" value="{{ $variationType }}" {{ $variationType }}> {{ $variationNameArray[$variationType] }} </option>
	                                				    @else

	                                				    <option data-flag="{{ request()->routeIs('admin.products.edit') ? 1 : 0 }}" value="0" {{ $variationType == 0 ? 'selected' : '' }}> Has None </option>

	                                				    <option data-flag="{{ request()->routeIs('admin.products.edit') ? 1 : 0 }}" value="1" {{ $variationType == 1 ? 'selected' : '' }}> Has Colors Only </option>

	                                				    <option data-flag="{{ request()->routeIs('admin.products.edit') ? 1 : 0 }}" value="2" {{ $variationType == 2 ? 'selected' : '' }}> Has Colors & Sizes Both </option>
	                                				    @endif
	                                				</select>

	                                			</div>
	                                		</div>
	                                	</div>
	                                	<hr class="mt-0">
	                                	<div class="row ">
	                                	    <div class="col-lg-12 variationContent">
	                                	        <div class="row" > 
	                                	            
	                                	            <div id="dynamic_field" class="col-md-12">
	                                	                <!-- Dynamic stock field Here -->
	                                	            </div>
	                                	        </div> 
	                                	    </div>
	                                	</div>
	                                </div>
	                                <div class="box-footer">
	                                	<div class="row">
	                                	    <div class="col-md-2">
	                                	        
	                                	        <button type="button" name="add" id="addNewColorBtn" class="btn btn-success">Add New Color</button>
	                                	        
	                                	    </div>
	                                	</div> 
	                                </div>
	                            </div>

	                            <div class="box box-primary box-solid">
	                            	<div class="box-header with-border">
	                            		Add <b>Images</b> for the product.
	                            	</div>
	                            	<div class="box-body">
	                            		<div class="row">
	                            		    <div class="col-md-4">
	                            		        <div class="input-group">
	                            		            <span class="input-group-addon">
	                            		                <i class="fa fa-image"></i> Featured Image
	                            		            </span>
	                            		            <input class="btn btn-info btn-flat" type="file" name="image" {{ request()->routeIs('admin.products.edit') ? '' : 'required' }}>
	                            		        </div>
	                            		    </div>

	                            		    <div class="col-md-8">
	                            		        <div class="input-group">
	                            		            <span class="input-group-addon">
	                            		                <i class="fa fa-image"></i> Gallery Images
	                            		            </span>
	                            		            <input class="btn btn-info btn-flat" type="file" name="other_images[]" multiple>
	                            		        </div>
	                            		    </div>
	                            		    <div class="col-lg-6">
	                            		        <small>
	                            		            <b>Featured & Gallery Image resolution:</b> 1200x1200px image recommended.
	                            		        </small>
	                            		    </div>
	                            		</div>
	                            		{{-- <div class="row imageDiv">
	                            		    <div class="col-md-2">
	                            		        <h4>Featured Image</h4>
	                            		    </div>
	                            		    <div class="col-md-8">
	                            		        <h4 class="pull-right">Gallery Images</h4>
	                            		    </div>
	                            		</div> --}}
	                            		<div class="row imageDiv" style="margin-left: 0px; margin-right: 0px;">
	                            		    <?php if (request()->routeIs('admin.products.edit')): ?>

	                            		        <div class="col-lg-4 col-md-3 col-xs-6" style="min-height:100px;">
	                            		            <img class="img-responsive" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}" width="60%" alt="N/a"/>
	                            		        </div>

	                            		        <div class="col-lg-8 col-md-9 col-xs-6 ">
	                            		            <div class="row ">
	                            		                <?php
	                            		                    $images = Storage::files('public/products/'.$product->slug.'/');
	                            		                ?>
	                            		                @for ($i = 0; $i < count($images); $i++)
	                            		                    @if(strpos($images[$i], $product->image) != true)
	                            		                    <div class="col-md-3" style="padding-bottom: 5px; margin-right: 5px; max-width: 100px;" id="gallery_image_{{$i}}">
                                                                                
                                                                <a href="#delete_image" data-toggle="modal"
                                                                   data-photo=""
                                                                   onclick="delete_image('{{ $product->slug }}', '{{ basename($images[$i]) }}', 'gallery_image_{{$i}}')"
                                                                   id="" title="Delete Image">
                                                                    <i style="position: absolute; top: -9px; padding: 4px; color: red;border-radius: 50%; opacity: 1;" class="close fa fa-times"></i>
                                                                </a>
		                            		                    <a data-fancybox="{{ $product->title }}" href="{{ asset('storage/products/'.$product->slug.'/'.basename($images[$i])) }}" data-sub-html="{{ $product->title }}">

		                            		                    	<img src="{{ asset('storage/').str_replace('public/products/'.$product->slug.'/','/products/'.$product->slug.'/thumbs/thumb_',$images[$i])}}" alt="no-image" style="max-width: 100px; padding-bottom: 4px;">
		                            		                    </a>
		                            		                </div>
	                            		                    @endif
	                            		                @endfor
	                            		            </div>
	                            		        </div>
	                            		    <?php endif ?>
	                            		</div>
	                            	</div>
	                            </div>

	                            <div class="box box-primary box-solid">
	                            	<div class="box-header with-border">
	                            		Add <b>Description</b> for the product.
	                            	</div>
	                            	<div class="box-body">
		                                <div class="row">
		                                	<div class="col-md-12">
			                                    <div class="input-group">
			                                        <label class="input-group-addon" for="content"> <i class="fa fa-file"></i>
			                                        	Short Description
			                                        </label>
			                                        <textarea name="short_description" class="ckeditor" rows="10" cols="80" placeholder="Short Content">
			                                            {{  request()->routeIs('admin.products.edit') ? $product->short_description : '' }}
			                                        </textarea>
			                                    </div>
			                                </div>
		                                </div>
		                                <div class="row">
		                                	<div class="col-md-12">
			                                    <div class="input-group">
			                                        <label class="input-group-addon" for="content"> <i class="fa fa-file"></i>
			                                        	Long Description
			                                        </label>
			                                        <textarea name="long_description" class="ckeditor" rows="10" cols="80" placeholder="Long Content">{{  request()->routeIs('admin.products.edit') ? $product->long_description : '' }}</textarea>
			                                    </div>
			                                </div>
		                                </div>
		                            </div>
	                            </div>
	                        </div>

	                        <div class="box-footer">

	                            <?php if (request()->routeIs('admin.products.edit')) { ?>
	                                <a href="{{ route('admin.products.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
	                                <button type="submit" name="submitEdit" class="btn btn-primary pull-right">Update Product
	                                </button>
	                            <?php } else { ?>
	                                <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
	                                <button type="submit" name="submit" class="btn btn-success pull-right">Save Product
	                                </button>
	                            <?php } ?>
	                        </div>
	                        
	                    </form>
	                    
	                </div>
	                
	            </div>

	        </div>

	    </section>
	    <!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<div class="modal fade modal-danger" id="deleteVariation">
	    <div class="modal-dialog " role="document">
	        <div class="modal-content bg-default">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Delete Variation</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	                <p>Are you Sure?</p>
	                <small id="warningMessage"></small>
	            </div>
	            <div class="modal-footer ">
	                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
	                <button id="confirmDeleteVariation" data-variation-id="" data-flag="" data-variation-div-id="" class="btn btn-round btn-danger">Delete</button>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal fade modal-danger" id="delete_image">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-default">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Gallery Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure...!!</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <button id="confirmDeleteGalleryImage" data-slug="" data-gallery-image="" data-gallery-image-id="" class="btn btn-round btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		// <---------- Product Tags ---------->
        $('#tags').tagsinput('items');

        var i=1;
        var j=0;

        // <---------- Add Product Variations ---------->
        $('#addNewColorBtn').click(function(){  
            // alert(i);
            // return;
            var variation_type = $("#variationType").val();

            if (variation_type == 1) {
            	var i = $(".color-only-variation").last().data('cumulative-count');
            }else if(variation_type == 2){
            	var i = $(".color-size-variation").last().data('cumulative-count');
            }

            if (i == null) {
            	i=0;
            }

            
            i++;

            $.ajax({
                url : "{{ URL::route('admin.products.add-extra-variation-fields') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        variation: variation_type,
                        cIndex: i,
                    },
                cache : false,
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        if ($("#variationType").val() == 1) {
                            
                            $("#colorOnlyVariation").append($response.responseText);

                            var color_only_variations_count = $(".color-only-variation").length;

                            if (color_only_variations_count > 1) {
                                $('.remove-color-btn').show();
                            }

                        }else if($("#variationType").val() == 2){

                            $('#dynamic_field').append($response.responseText);

                            var color_size_variations_count = $(".color-size-variation").length;

                            if (color_size_variations_count > 1) {
                                $('.remove-color-size-btn').show();
                            }

                            $('.color-size-variation').each(function(){
                            	var color_index = i;

                            	var size_variation_class =  'size-variation-'+color_index;
                            	var size_variation_count = $("."+size_variation_class).length;

                            	if (size_variation_count <= 1) {
                            	    $('.remove-size-btn-'+color_index).hide();
                            	}
                            });
                        }

                        $('.select2').select2();
                        // $('.variation-color-picker').colorpicker({
                        //     useAlpha: false,
                        //     format: "hex"
                        // });
                    }else{
                    	toastr['error']('Something went wrong!','Error');
                    }
                    $('#modal-loader').hide();
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                    $('#modal-loader').hide();
                }
            }); 
        }); 


        // <---------- Remove Color Function ---------->
        function remove_color(color_index) {
	
    		var color_id = 'color-'+color_index;
    		$("#"+color_id).remove();

    		var color_only_variations_count = $(".color-only-variation").length;
    		if (color_only_variations_count <= 1) {
    		    $('.remove-color-btn').hide();
    		}
        }


        // <---------- Remove Size Functions ---------->
        function remove_size(color_index, size_index){

        	var size_id = 'size-'+color_index+'-'+size_index;
        	$("#"+size_id).remove();

        	var size_variation_class =  'size-variation-'+color_index;
        	var size_variation_count = $("."+size_variation_class).length;

        	if (size_variation_count <= 1) {
        	    $('.remove-size-btn-'+color_index).hide();
        	}
        }

        // <---------- Remove Color & Sizes Both Functions ---------->
        function remove_color_size(color_index) {
	
    		var color_id = 'color-size-'+color_index;
    		$("#"+color_id).remove();

    		var color_size_variations_count = $(".color-size-variation").length;
    		if (color_size_variations_count <= 1) {
    		    $('.remove-color-size-btn').hide();
    		}
        }

        // <---------- Remove DB Variations  ---------->
        function delete_variation(that) {
        	
        	var identifier = $(that).data('identifier');
        	var color_id = $(that).data('color-id');
        	var color_key = $(that).data('color-key');

        	if (identifier == 'size') {

        		var size_id = $(that).data('size-id');
        		var size_key = $(that).data('size-key');
                $("#warningMessage").html('Once You Delete it, it cannot be recovered back!');

            }else{

            	var size_id = '';
        		var size_key = '';
                $("#warningMessage").html('Once You Delete it, it cannot be recovered back! <br> Deleting this Color, It all Sizes will also be deleted!');
            }

            $("#confirmDeleteVariation").attr('data-identifier', identifier);
            $("#confirmDeleteVariation").attr('data-color-key', color_key);
            $("#confirmDeleteVariation").attr('data-color-id', color_id);
            $("#confirmDeleteVariation").attr('data-size-key', size_key);
            $("#confirmDeleteVariation").attr('data-size-id', size_id);
        }

        // <---------- Delete Product Variations  ---------->

        $("#confirmDeleteVariation").click(function(){

        	$("#deleteVariation").modal('hide');

        	var identifier = $(this).attr('data-identifier');
        	var color_key = $(this).attr('data-color-key');
        	var color_id = $(this).attr('data-color-id');
        	var size_key = $(this).attr('data-size-key');
        	var size_id = $(this).attr('data-size-id');
        	var variation_id = identifier == 'size' ? size_id : color_id;
        	var variation_div_id = identifier == 'size' ? 'size-'+color_key+'-'+size_key :  (identifier == 'color' ? 'color-'+color_key : 'color-size-'+color_key);

        	var message = identifier == 'size' ? 'Product Size ' : 'Product Color ';

        	$.ajax({
        	    url : "{{ URL::route('admin.products.delete-variation') }}",
        	    type : "POST",
        	    data :{ '_token': '{{ csrf_token() }}',
        	            variation_id: variation_id,
        	            identifier: identifier
        	        },
        	    cache : false,
        	    beforeSend : function (){
        	        $('#modal-loader').show();
        	    },
        	    complete : function($response, $status){

        	        if ($status != "error" && $status != "timeout") {
        	            
        	            var obj = jQuery.parseJSON($response.responseText);

        	            if (obj.message == 'success') {
        	                $("#"+variation_div_id).remove();
        	            	toastr['success'](message+'Deleted Successfully!','Deleted!');

        	            	if (identifier == 'color') {

	                            var color_only_variations_count = $(".color-only-variation").length;

	                            if (color_only_variations_count <= 1) {
	                                $('.remove-color-btn').hide();
	                            }

	                            // alert(color_only_variations_count);
	                        }else if(identifier == 'color-size'){
	                        	
	                            
	                            var color_size_variations_count = $(".color-size-variation").length;

	                            if (color_size_variations_count <= 1) {
	                                $('.remove-color-size-btn').hide();
	                            }

	                        }else if(identifier == 'size'){

	                        	var size_variation_class =  'size-variation-'+color_key;
	                        	var size_variation_count = $("."+size_variation_class).length;

	                        	if (size_variation_count <= 1) {
	                        	    $('.remove-size-btn-'+color_key).hide();
	                        	}
	                        }

        	            }else{
        	            	toastr['error']('Something went wrong!','Error');
        	            }
        	            
        	        }
        	        $('#modal-loader').hide();
        	    },
        	    error : function ($responseObj){
        	        alert("Something went wrong while processing your request.\n\nError => "
        	            + $responseObj.responseText);
        	        $('#modal-loader').hide();
        	    }
        	}); 

        });

        // <---------- Add Sizes Only  ---------->
        function add_size_variation(color_size_index){
            
            var j = $(".size-variation-"+color_size_index).last().data('cumulative-count');
            console.log(j);
            j++;

            $.ajax({
                url : "{{ URL::route('admin.products.get-size-fields') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        color_index: color_size_index,
                        size_index: j,
                    },
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {

                        $('#color-sizes-'+color_size_index).append($response.responseText);

                        var size_variation_class =  'size-variation-'+color_size_index;
                        var size_variation_count = $("."+size_variation_class).length;

                        if (size_variation_count > 1) {
                            $('.remove-size-btn-'+color_size_index).show();
                        }

                        $('.select2').select2();

                    }else{
                        toastr['error']('Something went wrong!','Error');
                    }
                    $('#modal-loader').hide();
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                    $('#modal-loader').hide();
                }
            }); 
        };

        if ($("#productId").val() != '') {
            i = $('#parentVariationCount').val();
            j = $('#childVariationCount').val();
        }
        get_variation_fields($("#variationType").val(), '{{ request()->routeIs('admin.products.edit') ? $product->id : 0 }}', $("#variationType option:selected").data('flag'));

        if ($("#variationType").val() >= 1) {
            
            $(".variationContent").show();
            $("#addNewColorBtn").show();
        }else{
            $("#addNewColorBtn").hide();
        }

        $("#variationType").change(function(){

            get_variation_fields(this.value, '{{ request()->routeIs('admin.products.edit') ? $product->id : 0 }}', $('option:selected',this).data('flag'));

            if (this.value >= 1) {
                $("#addNewColorBtn").show();
            }else{
                $("#addNewColorBtn").hide();
            }
        });

        function get_variation_fields(variationType, product_id, flag){

            $.ajax({
                url : "{{ URL::route('admin.products.get-variation-fields') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        variation: variationType,
                        product_id : product_id,
                        flag: flag,
                    },
                beforeSend : function (){
                    $('#modal-loader').show();
                    $(".variationContent").slideUp();
                    $('#dynamic_field').empty();
                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {
                        $(".variationContent").slideDown(500);
                        $('#dynamic_field').append($response.responseText);

                        if (variationType == 1) {

                        	var color_only_variations_count = $(".color-only-variation").length;

                            if (color_only_variations_count <= 1) {
                                $('.remove-color-btn').hide();
                            }

                        }else{

                        	var color_size_variations_count = $(".color-size-variation").length;

                            if (color_size_variations_count <= 1) {
                                $('.remove-color-size-btn').hide();
                            }

                            $('.color-size-variation').each(function(){
                            	var color_index = $(this).data('cumulative-count');

                            	var size_variation_class =  'size-variation-'+color_index;
                            	var size_variation_count = $("."+size_variation_class).length;

                            	if (size_variation_count <= 1) {
                            	    $('.remove-size-btn-'+color_index).hide();
                            	}
                            });
                        }

                        $('.select2').select2();
                        // $('.variation-color-picker').colorpicker({
                        //     useAlpha: false,
                        //     format: "hex"
                        // });

                    }else{
                        toastr['error']('Something went wrong!','Error');
                    }

                    $('#modal-loader').hide();
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                    $('#modal-loader').hide();
                }
            }); 
        }

        // <---------- Discount Calcualtions ---------->
        $(".do-discount-calculation").keyup(function(){
            calculate_discount_percentage();
        });

        calculate_discount_percentage();

        function calculate_discount_percentage() {
            var price = $("#priceInput").val() == '' ? 0 : $("#priceInput").val();
            var discounted_price = $("#discountedPriceInput").val();

            discount_percent = ((price - discounted_price)/price)*100;
            discount_percent = discount_percent.toFixed(2);
            
            if (discounted_price != '' && price != '') {

                $("#discountPercentInput").val(discount_percent);
            }else{
                $("#discountPercentInput").val('');
            }
        }

        // <---------- Delete Image Modal ---------->
        function delete_image(slug, image, galleryImageId) {

            $("#confirmDeleteGalleryImage").attr('data-slug', slug);
            $("#confirmDeleteGalleryImage").attr('data-image', image);
            $("#confirmDeleteGalleryImage").attr('data-gallery-image-id', galleryImageId);
        }

        // <---------- Delete Product Gallery Image ---------->
        jQuery("#confirmDeleteGalleryImage").click(function(){

            $("#delete_image").modal('hide');

            var slug = $(this).attr('data-slug');
            var image = $(this).attr('data-image');
            var gallery_image_id = String($(this).attr('data-gallery-image-id'));

            $.ajax({
                url : "{{ URL::route('admin.products.delete-gallery-image') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        slug: slug,
                        image: image
                    },
                cache : false,
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {
                        
                        var obj = jQuery.parseJSON($response.responseText);

                        if (obj.message == 'success') {
                            $("#"+gallery_image_id).remove();
                        }else{
                            toastr['error']('Something went wrong!','Error');
                        }
                        $('#modal-loader').hide();
                        // $("#pageUrl").html($response.responseText);
                        
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                    $('#modal-loader').hide();
                }
            }); 
        });
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>  
	<script src="{{ asset('backend/plugins/quicksearch-master/jquery.quicksearch.js') }}"></script>
    <script src="{{ asset('backend/plugins/multiselect/js/jquery.multi-select.js') }}"></script>

	<script>
        $(function () {
            // $("#productIds").multiSelect();
            $('#productIds').multiSelect({
                keepOrder: true,
                selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search to Select'>",
                selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search to Remove'>",
                selectableOptgroup: true,
                afterInit: function(ms){
                    var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
        });

    </script>

	{{-- <script>
		lightGallery("#gallery-images",{
		    selector: '.light-link'
		});
	</script> --}}
@endpush