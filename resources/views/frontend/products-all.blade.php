@extends('layouts.app')
@section('title', $page_title)
@push('post-css')
    <style>
        .infinite-load {
            margin: 20px 0;
            display: flex;

            justify-content: center;
            align-items: center;
        }

        @media only screen and (max-width: 600px) {
            body {
                margin-bottom: 0 !important;
            }
        }
    </style>
@endpush
@section('content')
	<div class="breadcrumb-area" style="background-image: url('./assets/images/files/page-title.jpg');">
	    <div class="container">
	        <div class="breadcrumb-content">
	            <h2>{{ $page_title }}</h2>
	            <ul>
	                <li><a href="{{ route('home') }}">Home</a></li>
	                <li class="active">{{ $page_title }}</li>
	            </ul>
	        </div>
	    </div>
	</div>

	<div class="kenne-content_wrapper">
	    <div class="container">
	    	@if($products->count() > 0)
	        <div class="row">
	            <div class="col-xl-3 col-lg-4 order-2 order-lg-1">
	                <div class="kenne-sidebar-catagories_area">
	                    <div class="kenne-sidebar_categories">
	                        <div class="kenne-categories_title first-child">
	                            <h5>Filter by price</h5>
	                        </div>
	                        <div class="price-filter">
	                            <div id="slider-range"></div>
	                            <div class="price-slider-amount">
	                                <div class="label-input">
	                                    <label>price : </label>
	                                    <input type="text" id="amount" name="price" placeholder="Add Your Price" />
	                                    <button class="filter-btn">Filter</button>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="kenne-sidebar_categories category-module">
	                        <div class="kenne-categories_title">
	                            <h5>Product Categories</h5>
	                        </div>
	                        <div class="sidebar-categories_menu">
	                            <ul>
	                                <li class="has-sub"><a href="javascript:void(0)">Topwear<i
	                                            class="ion-ios-plus-empty"></i></a>
	                                    <ul>
	                                        <li><a href="javascript:void(0)">Shirts</a></li>
	                                        <li><a href="javascript:void(0)">T-Shirts</a></li>
	                                        <li><a href="javascript:void(0)">Casual Shirts</a></li>
	                                        <li><a href="javascript:void(0)">Blazers</a></li>
	                                        <li><a href="javascript:void(0)">Suits</a></li>
	                                        <li><a href="javascript:void(0)">Vests</a></li>
	                                        <li><a href="javascript:void(0)">Traditional</a></li>
	                                        <li><a href="javascript:void(0)">Formal Wear</a></li>
	                                    </ul>
	                                </li>
	                                <li class="has-sub"><a href="javascript:void(0)">Sportswear <i
	                                            class="ion-ios-plus-empty"></i></a>
	                                    <ul>
	                                        <li><a href="javascript:void(0)">Daylesford</a></li>
	                                        <li><a href="javascript:void(0)">Delaware</a></li>
	                                        <li><a href="javascript:void(0)">Fayence</a></li>
	                                        <li><a href="javascript:void(0)">Mable</a></li>
	                                        <li><a href="javascript:void(0)">Mobo</a></li>
	                                        <li><a href="javascript:void(0)">Pippins</a></li>
	                                    </ul>
	                                </li>

	                                <li class="has-sub"><a href="javascript:void(0)">Bottoms <i
	                                            class="ion-ios-plus-empty"></i></a>
	                                    <ul>
	                                        <li><a href="javascript:void(0)">Jeans</a></li>
	                                        <li><a href="javascript:void(0)">Shorts</a></li>
	                                        <li><a href="javascript:void(0)">Trousers</a></li>
	                                        <li><a href="javascript:void(0)">Skirts</a></li>
	                                    </ul>
	                                </li>
	                                <li class="has-sub"><a href="javascript:void(0)">Accessories <i
	                                            class="ion-ios-plus-empty"></i></a>
	                                    <ul>
	                                        <li><a href="javascript:void(0)">Candleholders</a></li>
	                                        <li><a href="javascript:void(0)">Candles</a></li>
	                                        <li><a href="javascript:void(0)">Clocks</a></li>
	                                        <li><a href="javascript:void(0)">Floor Mirrors</a></li>
	                                        <li><a href="javascript:void(0)">Lamps & Lighting</a></li>
	                                        <li><a href="javascript:void(0)">Rugs</a></li>
	                                        <li><a href="javascript:void(0)">Runners</a></li>
	                                        <li><a href="javascript:void(0)">Wall Decor</a></li>
	                                        <li><a href="javascript:void(0)">Wall Mirrors</a></li>
	                                    </ul>
	                                </li>
	                                <li><a href="javascript:void(0)">Cosmetic</a></li>
	                                <li><a href="javascript:void(0)">Uncategorized</a></li>
	                            </ul>
	                        </div>
	                    </div>
	                    <div class="kenne-sidebar_categories">
	                        <div class="kenne-categories_title">
	                            <h5>Color</h5>
	                        </div>
	                        <ul class="sidebar-checkbox_list">
	                            <li>
	                                <a href="javascript:void(0)">Black (1)</a>
	                            </li>
	                            <li>
	                                <a href="javascript:void(0)">Blue (1)</a>
	                            </li>
	                            <li>
	                                <a href="javascript:void(0)">Gold (3)</a>
	                            </li>
	                        </ul>
	                    </div>
	                </div>
	            </div>

	            <div class="col-xl-9 col-lg-8 order-1 order-lg-2">
	                <div class="shop-toolbar">
	                    
	                    <div class="product-view-mode">
	                        <a class="active grid-3" data-target="gridview-3" data-toggle="tooltip" data-placement="top" title="Grid View"><i class="fa fa-th"></i></a>
	                        <a class="list" data-target="listview" data-toggle="tooltip" data-placement="top" title="List View"><i class="fa fa-th-list"></i></a>
	                    </div>

	                    <div class="product-item-selection_area">
	                        <div class="product-short">
	                            <label class="select-label">Sort By:</label>
	                            <select class="nice-select myniceselect">
	                                <option value="1">Default sorting</option>
	                                <option value="2">Name, A to Z</option>
	                                <option value="3">Name, Z to A</option>
	                                <option value="4">Price, low to high</option>
	                                <option value="5">Price, high to low</option>
	                                <option value="5">Rating (Highest)</option>
	                                <option value="5">Rating (Lowest)</option>
	                                <option value="5">Model (A - Z)</option>
	                                <option value="5">Model (Z - A)</option>
	                            </select>
	                        </div>
	                    </div>
	                </div>
	                <div class="infinite-scroll">
		                <div class="shop-product-wrap grid gridview-4 row">
		                	@foreach($products as $key => $catProd)
		                    <div class="col-lg-3 col-md-3 col-sm-6">
		                        <div class="product-item">
	                                <div class="single-product">
	                                    <div class="product-img">
	                                        <a href="{{ route('product-details',['slug' => $catProd->slug]) }}">
	                                            <img class="primary-img" src="{{ asset('storage/products/'.$catProd->slug.'/thumbs/thumb_'.$catProd->image) }}" alt="{{ $catProd->slug }}">
	                                        </a>
	                                        {{-- <span class="sticker-2">Hot</span> --}}
	                                        <div class="add-actions">
	                                            <ul>
	                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $catProd->id }}">
	                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
	                                                </li>
	                                                <li>
	                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
	                                                </li>
	                                                @if($catProd->variation_type != 0)
		                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $catProd->id }}">
		                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
		                                                </li>
		                                            @else

		                                                <li onclick="add_to_cart('{{ $catProd->id }}', '{{ $catProd->variation_type }}', 1, 0, 0)">
		                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
		                                                </li>
		                                            @endif
	                                            </ul>
	                                        </div>
	                                    </div>
	                                    <div class="product-content">
	                                        <div class="product-desc_info">
	                                            <h3 class="product-name">
	                                                <a href="{{ route('product-details',['slug' => $catProd->slug]) }}">{{ $catProd->title }}</a>
	                                            </h3>
	                                            <div class="price-box">
	                                                <span class="new-price">Nrs.{{ $catProd->discounted_price != NULL || $catProd->discounted_price != 0 ? $catProd->discounted_price : $catProd->price }}</span>
	                                                @if($catProd->discounted_price != NULL || $catProd->discounted_price != 0)
	                                                <span class="old-price">Nrs.{{ $catProd->price }}</span>
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
		                        <div class="list-product_item">
		                            <div class="single-product">
		                                <div class="product-img">
		                                    <a href="{{ route('product-details',['slug' => $catProd->slug]) }}">
	                                            <img class="primary-img" src="{{ asset('storage/products/'.$catProd->slug.'/thumbs/thumb_'.$catProd->image) }}" alt="{{ $catProd->slug }}">
	                                        </a>
		                                </div>
		                                <div class="product-content">
		                                    <div class="product-desc_info">
		                                        <div class="price-box">
		                                            <span class="new-price">Nrs.{{ $catProd->discounted_price != NULL || $catProd->discounted_price != 0 ? $catProd->discounted_price : $catProd->price }}</span>
	                                                @if($catProd->discounted_price != NULL || $catProd->discounted_price != 0)
	                                                <span class="old-price">Nrs.{{ $catProd->price }}</span>
	                                                @endif
		                                        </div>
		                                        <h6 class="product-name">
		                                        	<a href="{{ route('product-details',['slug' => $catProd->slug]) }}">{{ $catProd->title }}</a>
		                                        </h6>
		                                        <div class="rating-box">
		                                            <ul>
		                                                <li><i class="ion-ios-star"></i></li>
		                                                <li><i class="ion-ios-star"></i></li>
		                                                <li><i class="ion-ios-star"></i></li>
		                                                <li class="silver-color"><i class="ion-ios-star-half"></i></li>
		                                                <li class="silver-color"><i class="ion-ios-star-outline"></i>
		                                                </li>
		                                            </ul>
		                                        </div>
		                                        <div class="product-short_desc">
		                                            {!! $catProd->short_description !!}
		                                        </div>
		                                    </div>
		                                    <div class="add-actions">
		                                        <ul>
		                                            <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $catProd->id }}">
	                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
	                                                </li>
	                                                <li>
	                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
	                                                </li>
	                                                @if($catProd->variation_type != 0)
		                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $catProd->id }}">
		                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
		                                                </li>
		                                            @else

		                                                <li onclick="add_to_cart('{{ $catProd->id }}', '{{ $catProd->variation_type }}', 1, 0, 0)">
		                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
		                                                </li>
		                                            @endif
		                                        </ul>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                    @endforeach
		                </div>
		                <ul class="pagination">
		                	
	                    {{ $products->links() }}
		                </ul>
	                        
	                </div>
	            </div>
	        </div>
	        @else
	        <div class="row">
	        	<div class="col-xl-12 col-lg-12 text-center">
	        		<p>Sorry, No Products in this Category</p>
	        		<a class="" href="{{ route('home') }}">Keep Shopping!</a>
	        	</div>
	        </div>
	        @endif
	    </div>
	</div>

	@include('frontend.quick-view-product-modal')
@endsection

@push('post-scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function () {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="infinite-load"><img class="center-block" src="{{ asset('frontend/images/ajax-loader.gif') }}" alt="Loading..." /></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function () {
                    $('ul.pagination').remove();
                    
                    $(".my-rating").starRating({
                        initialRating: 4,
                        strokeColor: '#894A00',
                        strokeWidth: 0,
                        starSize: 25,
                        readOnly: true
                    });


                }
            });
        });

    </script>
@endpush