@extends('layouts.app')
@section('title', $product->title)
@push('post-css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
        integrity="sha256-Vzbj7sDDS/woiFS3uNKo8eIuni59rjyNGtXfstRzStA=" crossorigin="anonymous" />
@endpush
@section('content')
	<div class="breadcrumb-area" style="background-image: url('{{ asset('frontend/assets/images/files/page-title.jpg') }}');">
	    <div class="container">
	        <div class="breadcrumb-content">
	            <h2>{{ $product->title }}</h2>
	            <ul>
	                <li><a href="{{ route("home") }}">Home</a></li>
	                {{-- <li><a href="shop.html">Featured Prducts</a></li> --}}
	                <li class="active">{{ $product->title }}</li>
	            </ul>
	        </div>
	    </div>
	</div>

	<div class="sp-area sp-gallery_area sp-gallery-right_area sp-sticky_area">
	    <div class="container-fluid">
	        <div class="has-padding">
	        	<div class="sp-nav">
		            <div class="modal-inner-area sp-area row">
		                <div class="col-lg-7">
		                    <div class="sp-gallery sp-sticky_gallery">
		                    	<div class="row">

		                    		<div class="col-lg-6">
		                    		    <div class="lg-image img-hover_effect">
		                    		        <a href="{{ asset('storage/products/'.$product->slug.'/'.$product->image) }}" data-fancybox="gallery">
		                    		            <img class="img-full" src="{{ asset('storage/products/'.$product->slug.'/thumbs/small_'.$product->image) }}" alt="{{ $product->slug }}">
		                    		        </a>
		                    		    </div>
		                    		</div>

		                    		 @php
		                    		     $images = Storage::files('public/products/'.$product->slug.'/');
		                    		 @endphp
		                    		 @for ($i = 0; $i < count($images); $i++)
		                    		 	@if(strpos($images[$i], $product->image) != true)
		                    		 	<div class="col-lg-6">
		                    		 	    <div class="lg-image img-hover_effect">
		                    		 	        <a href="{{ asset('storage/products/'.$product->slug.'/'.basename($images[$i]))}}" data-fancybox="gallery">
		                    		 	            <img class="img-full" src="{{ asset('storage/products/'.$product->slug.'/thumbs/small_'.basename($images[$i]))}}" alt="{{ $product->slug }}"/>
		                    		 	        </a>
		                    		 	    </div>
		                    		 	</div>
		                    		 	@endif
		                    		@endfor

		                    	</div>
		                    </div>
		                </div>
		                <div id="sticky-sidebar" class="col-lg-5">
		                    <div class="sp-content product-content-sticky">
		                        <div class="sp-heading">
		                            <h5>{{ $product->title }}</h5>
		                        </div>
		                        <div class="rating-box">
		                            <ul>
		                                <li><i class="ion-android-star"></i></li>
		                                <li><i class="ion-android-star"></i></li>
		                                <li><i class="ion-android-star"></i></li>
		                                <li class="silver-color"><i class="ion-android-star"></i></li>
		                                <li class="silver-color"><i class="ion-android-star"></i></li>
		                            </ul>
		                        </div>
		                        <div class="price-box">
		                        	<span class="new-price new-price-2">
		                        		Nrs.{{ $product->discounted_price != NULL || $product->discounted_price != 0 ? $product->discounted_price : $product->price }}
		                        	</span>
		                        	@if($product->discounted_price != NULL || $product->discounted_price != 0)
		                        		<span class="old-price">NRs.{{ $product->price }}</span>
		                        	@endif
		                        </div>

		                        @if ($product->product_colors->count() > 0) 
		                        <div class="color-list_area">
		                            <div class="options-wrapper">

		                                <div class="option-1">
		                                    <span class="sub-title">Color</span>
		                                    <div class="color-list" id="color-picker">
		                                        @foreach ($product->product_colors as $color_key => $color) 

		                                            @if ($color_key == 0) 
		                                                @php $firstColor = $color; @endphp
		                                            @endif

		                                            <a href="javascript:void(0)" class="single-color {{ $color_key==0 ? 'active' : '' }}" data-has-sizes="{{ $color->product_sizes->count() > 0 ? 1 : 0 }}" data-color-id="{{ $color->id }}" data-stock-count="{{ $color->stock_count }}" data-swatch-color="{{ $color->color_details->code }}">
		                                                <span style="background-color: {{ $color->color_details->code }}" class="bg-red_color"></span>
		                                            </a>
		                                        @endforeach    
		                                    </div>

		                                </div>
		                                @if ($firstColor->product_sizes->count() > 0) 
		                                    <div class="option-2">
		                                        <span class="sub-title">Sizes</span>
		                                        <div class="range-picker" id="size-picker">

		                                            @foreach ($firstColor->product_sizes as $size_key => $size) 

		                                                @if ($size_key == 0) 
		                                                    @php $firstSize = $size; @endphp
		                                                @endif
		                                                <div class="{{ $size_key==0 ? 'active' : '' }}" data-size-id="{{ $size->id }}" data-stock-count="{{ $size->stock_count }}">{{ $size->size_details->title }}</div>
		                                            @endforeach    
		                                        </div>
		                                    </div>
		                                @endif
		                                
		                            </div>
		                        </div>
		                        @endif

		                        @php
		                        	switch ($product->variation_type) {
		                        	    case 0:
		                        	        $max_order_qty = $product->stock_count;
		                        	        break;

		                        	    case 1:
		                        	        $max_order_qty = $firstColor->stock_count;
		                        	        break;

		                        	    case 2:
		                        	        $max_order_qty = $firstSize->stock_count;
		                        	        break;
		                        	    
		                        	    default:
		                        	        $max_order_qty = 1;
		                        	        break;
		                        	}
		                        @endphp

		                        <div class="d-flex flex-row align-items-end">
		                            <div class="quantity">
		                                <label>Quantity</label>
		                                <div class="cart-plus-minus">
		                                    <input id="ordered-qty" class="cart-plus-minus-box" value="1" type="text" max="{{ $max_order_qty }}" required>
		                                    <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
		                                    <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
		                                </div>
		                            </div>
		                            &nbsp;&nbsp;&nbsp;
		                            <div class="kenne-group_btn">
		                                <ul>
		                                    {{-- <li><a href="cart.html" class="add-to_cart">Buy Now</a></li> --}}
		                                    <li>
		                                    	<a id="add-to-cart" data-product-id="{{ $product->id }}" data-variation-type="{{ $product->variation_type }}" href="javascript:void(0)" class="add-to_cart">Add To Cart</a>
		                                    </li>

		                                    <li>
		                                    	<a id="add-to-wishlist" class="btn-add-to-wishlist" data-product-id="{{ $product->id }}" href="javascript:void(0)"><i class="ion-android-favorite-outline"></i></a>
		                                    </li>
		                                </ul>
		                            </div>
		                        </div>
		                        <div class="kenne-tag-line">
		                            <h6>Tags:</h6>
		                            <small><i>{{ $product->tags }}</i></small>
		                        </div>
		                        <div class="kenne-social_link">
		                            <ul>
		                                <li class="facebook">
		                                    <a href="https://www.facebook.com" data-toggle="tooltip" target="_blank"
		                                        title="Facebook">
		                                        <i class="fab fa-facebook"></i>
		                                    </a>
		                                </li>
		                                <li class="twitter">
		                                    <a href="https://twitter.com" data-toggle="tooltip" target="_blank"
		                                        title="Twitter">
		                                        <i class="fab fa-twitter-square"></i>
		                                    </a>
		                                </li>
		                                <li class="youtube">
		                                    <a href="https://www.youtube.com" data-toggle="tooltip" target="_blank"
		                                        title="Youtube">
		                                        <i class="fab fa-youtube"></i>
		                                    </a>
		                                </li>
		                                <li class="google-plus">
		                                    <a href="https://www.plus.google.com/discover" data-toggle="tooltip"
		                                        target="_blank" title="Google Plus">
		                                        <i class="fab fa-google-plus"></i>
		                                    </a>
		                                </li>
		                                <li class="instagram">
		                                    <a href="https://rss.com" data-toggle="tooltip" target="_blank"
		                                        title="Instagram">
		                                        <i class="fab fa-instagram"></i>
		                                    </a>
		                                </li>
		                            </ul>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
	            <!-- Begin Product Tab Area Two -->
	            <div class="product-tab_area-2">
	                <div class="container">
	                    <div class="row">
	                        <div class="col-lg-12">
	                            <div class="sp-product-tab_nav">
	                                <div class="product-tab">
	                                    <ul class="nav product-menu">
	                                        <li>
	                                        	<a class="active" data-toggle="tab" href="#description"><span>Description</span></a>
	                                        </li>
	                                        {{-- <li><a data-toggle="tab" href="#specification"><span>Specification</span></a></li> --}}
	                                        <li><a data-toggle="tab" href="#reviews"><span>Reviews (1)</span></a></li>
	                                    </ul>
	                                </div>
	                                <div class="tab-content uren-tab_content">
	                                    <div id="description" class="tab-pane active show" role="tabpanel">
	                                        <div class="product-description">
	                                            {!! $product->long_description !!}
	                                        </div>
	                                    </div>
	                                    <div id="reviews" class="tab-pane" role="tabpanel">
	                                        <div class="tab-pane active" id="tab-review">
	                                            <form class="form-horizontal" id="form-review">
	                                                <div id="review">
	                                                    <table class="table table-striped table-bordered">
	                                                        <tbody>
	                                                            <tr>
	                                                                <td style="width: 50%;">
	                                                                    <strong>Customer</strong>
	                                                                </td>
	                                                                <td class="text-right">26/10/19</td>
	                                                            </tr>
	                                                            <tr>
	                                                                <td colspan="2">
	                                                                    <p>Good product! Thank you very much</p>
	                                                                    <div class="rating-box">
	                                                                        <ul>
	                                                                            <li><i class="ion-android-star"></i>
	                                                                            </li>
	                                                                            <li><i class="ion-android-star"></i>
	                                                                            </li>
	                                                                            <li><i class="ion-android-star"></i>
	                                                                            </li>
	                                                                            <li><i class="ion-android-star"></i>
	                                                                            </li>
	                                                                            <li><i class="ion-android-star"></i>
	                                                                            </li>
	                                                                        </ul>
	                                                                    </div>
	                                                                </td>
	                                                            </tr>
	                                                        </tbody>
	                                                    </table>
	                                                </div>
	                                                <h2>Write a review</h2>
	                                                <div class="form-group required">
	                                                    <div class="col-sm-12 p-0">
	                                                        <label>Your Email <span
	                                                                class="required">*</span></label>
	                                                        <input class="review-input" type="email"
	                                                            name="con_email" id="con_email" required>
	                                                    </div>
	                                                </div>
	                                                <div class="form-group required second-child">
	                                                    <div class="col-sm-12 p-0">
	                                                        <label class="control-label">Share your opinion</label>
	                                                        <textarea class="review-textarea" name="con_message"
	                                                            id="con_message"></textarea>
	                                                        <div class="help-block"><span
	                                                                class="text-danger">Note:</span> HTML is
	                                                            not
	                                                            translated!</div>
	                                                    </div>
	                                                </div>
	                                                <div class="form-group last-child required">
	                                                    <div class="col-sm-12 p-0">
	                                                        <div class="your-opinion">
	                                                            <label>Your Rating</label>
	                                                            <span>
	                                                                <select class="star-rating">
	                                                                    <option value="1">1</option>
	                                                                    <option value="2">2</option>
	                                                                    <option value="3">3</option>
	                                                                    <option value="4">4</option>
	                                                                    <option value="5">5</option>
	                                                                </select>
	                                                            </span>
	                                                        </div>
	                                                    </div>
	                                                    <div class="kenne-btn-ps_right">
	                                                        <button class="kenne-btn">Continue</button>
	                                                    </div>
	                                                </div>
	                                            </form>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- Product Tab Area Two End Here -->
	            <!-- Begin Product Tab Area -->
	            <div class="product-tab_area">
	                <div class="container">
	                    <div class="row">
	                        <div class="col-lg-12">
	                            <div class="section-title">
	                                <h3>You May Also Like</h3>
	                                <a href="#" class="main-btn">View All</a>
	                            </div>
	                        </div>
	                        <div class="col-lg-12">
	                            <div class="kenne-element-carousel slider-nav product-tab_arrow" data-slick-options='{
		                            "slidesToShow": 5,
		                            "slidesToScroll": 1,
		                            "infinite": false,
		                            "arrows": true,
		                            "dots": false,
		                            "spaceBetween": 30
		                            }' data-slick-responsive='[
		                            {"breakpoint":992, "settings": {
		                            "slidesToShow": 3
		                            }},
		                            {"breakpoint":768, "settings": {
		                            "slidesToShow": 2
		                            }},
		                            {"breakpoint":575, "settings": {
		                            "slidesToShow": 2,
		                            "spaceBetween": 10
		                            }}
		                        ]'>

	                                @foreach($related_products as $key => $rProd)
	                                <div class="product-item">
	                                    <div class="single-product">
	                                        <div class="product-img">
	                                            <a href="{{ route('product-details',['slug' => $rProd->slug]) }}">
	                                                <img class="primary-img" src="{{ asset('storage/products/'.$rProd->slug.'/thumbs/thumb_'.$rProd->image) }}" alt="{{ $rProd->slug }}">
	                                            </a>
	                                            {{-- <span class="sticker-2">Hot</span> --}}
	                                            <div class="add-actions">
	                                                <ul>
	                                                    {{-- <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $rProd->id }}">
	                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
	                                                    </li> --}}
	                                                    <li>
	                                                        <a class="btn-add-to-wishlist" data-product-id="{{ $rProd->id }}" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
	                                                    </li>
	                                                    {{-- <li>
	                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
	                                                    </li> --}}
	                                                </ul>
	                                            </div>
	                                        </div>
	                                        <div class="product-content">
	                                            <div class="product-desc_info">
	                                                <h3 class="product-name">
	                                                    <a href="{{ route('product-details',['slug' => $rProd->slug]) }}">{{ $rProd->title }}</a>
	                                                </h3>
	                                                <div class="price-box">
	                                                    <span class="new-price">Nrs.{{ $rProd->discounted_price != NULL || $rProd->discounted_price != 0 ? $rProd->discounted_price : $rProd->price }}</span>
	                                                    @if($rProd->discounted_price != NULL || $rProd->discounted_price != 0)
	                                                    <span class="old-price">${{ $rProd->price }}</span>
	                                                    @endif
	                                                </div>
	                                                <div class="rating-box">
	                                                    <ul>
	                                                        <li><i class="ion-ios-star"></i></li>
	                                                        <li><i class="ion-ios-star"></i></li>
	                                                        <li><i class="ion-ios-star"></i></li>
	                                                        <li class="silver-color"><i class="ion-ios-star-half"></i></li>
	                                                        <li class="silver-color"><i class="ion-ios-star-outline"></i></li>
	                                                    </ul>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                @endforeach

	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- Product Tab Area End Here -->
	        </div>
	    </div>
	</div>
@endsection

@push('post-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
        integrity="sha256-yt2kYMy0w8AbtF89WXb2P1rfjcP/HTHLT7097U8Y5b8=" crossorigin="anonymous"></script>
@endpush