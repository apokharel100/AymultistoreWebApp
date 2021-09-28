@extends('layouts.app')
@section('title','Home')
@section('content')
    <!-- Begin Slider Area Two -->
    <div class="slider-area slider-area-2">

        <div class="kenne-element-carousel home-slider home-slider-2 transparent-arrow" data-slick-options='{
            "slidesToShow": 1,
            "slidesToScroll": 1,
            "infinite": true,
            "arrows": true,
            "dots": false,
            "autoplay" : true,
            "fade" : true,
            "autoplaySpeed" : 7000,
            "pauseOnHover" : false,
            "pauseOnFocus" : false
            }' data-slick-responsive='[
            {"breakpoint":768, "settings": {
            "slidesToShow": 1
            }},
            {"breakpoint":575, "settings": {
            "slidesToShow": 1
            }}
            ]'>
            @foreach($banners as $key => $banner)
            <div class="slide-item bg-3 animation-style-01" style="background-image: url('{{ asset('storage/banners/'.$banner->image) }}');">
                <a href="{{ $banner->link }}" class="full-link"></a>
                <div class="slider-progress"></div>
            </div>
            @endforeach

        </div>

    </div>
    <!-- Slider Area Two End Here -->

    <div class="categories_area">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="category-slider">

                        @foreach($popular_categories as $popularCat)
                        <div class="c">
                            <a href="{{ route('category-products',['slug' => $popularCat->slug]) }}">
                                <div class="category-wrapper">
                                    <div class="cat-img">
                                        <img src="{{ asset('storage/categories/thumbs/small_'.$popularCat->image) }}" class="img-fluid" alt="{{ $popularCat->slug }}">
                                    </div>
                                    <h5 class="cat-title text-center">
                                        {{ $popularCat->title }}
                                    </h5>
                                </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Begin Product Area -->
    <div class="product-area ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Featured Products</h3>
                        <a href="{{ route('products',['type' => 'featured']) }}" class="main-btn">View All</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product-arrow"></div>
                    <div class="kenne-element-carousel product-slider slider-nav" data-slick-options='{
                            "slidesToShow": 5,
                            "slidesToScroll": 1,
                            "infinite": false,
                            "arrows": true,
                            "dots": false,
                            "spaceBetween": 30,
                            "appendArrows": ".product-arrow"
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
                        @foreach($featured_products as $key => $fProd)
                        <div class="product-item">
                            <div class="single-product">
                                <div class="product-img">
                                    <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">
                                        <img class="primary-img" src="{{ asset('storage/products/'.$fProd->slug.'/thumbs/thumb_'.$fProd->image) }}" alt="{{ $fProd->slug }}">
                                    </a>
                                    {{-- <span class="sticker-2">Hot</span> --}}
                                    <div class="add-actions">
                                        <ul>
                                            <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $fProd->id }}">
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
                                            </li>
                                            <li>
                                                <a class="btn-add-to-wishlist" data-product-id="{{ $fProd->id }}" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
                                            </li>

                                            @if($fProd->variation_type != 0)
                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $fProd->id }}">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @else

                                                <li onclick="add_to_cart('{{ $fProd->id }}', '{{ $fProd->variation_type }}', 1, 0, 0)">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="product-desc_info">
                                        <h3 class="product-name">
                                            <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">{{ $fProd->title }}</a>
                                        </h3>
                                        <div class="price-box">
                                            <span class="new-price">Nrs.{{ $fProd->discounted_price != NULL || $fProd->discounted_price != 0 ? $fProd->discounted_price : $fProd->price }}</span>
                                            @if($fProd->discounted_price != NULL || $fProd->discounted_price != 0)
                                            <span class="old-price">Nrs.{{ $fProd->price }}</span>
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
    <!-- Product Area End Here -->

    <!-- Begin Banner Area Five -->
    <div class="banner-area-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-item img-hover_effect">
                        <div class="banner-img"
                            style="background-image: url('{{ asset('frontend/assets/images/files/banner-mid.jpg') }}');"></div>
                        <div class="banner-content">
                            <span>In-Store & Online</span>
                            <h3>
                                Men's Backpack
                                <br>
                                Wallets & More!
                            </h3>
                            <div class="kenne-btn-ps_center">
                                <a class="kenne-btn transparent-btn black-color square-btn"
                                    href="shop-left-sidebar.html">Discover Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area Five End Here -->

    <!-- Begin Product Tab Area -->
    <div class="product-tab_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>New Arrivals</h3>
                        <a href="{{ route('products',['type' => 'new-arrivals']) }}" class="main-btn">View All</a>
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

                        @foreach($new_arrivals as $key => $newProd)
                        <div class="product-item">
                            <div class="single-product">
                                <div class="product-img">
                                    <a href="{{ route('product-details',['slug' => $newProd->slug]) }}">
                                        <img class="primary-img" src="{{ asset('storage/products/'.$newProd->slug.'/thumbs/thumb_'.$newProd->image) }}" alt="{{ $newProd->slug }}">
                                    </a>
                                    {{-- <span class="sticker-2">Hot</span> --}}
                                    <div class="add-actions">
                                        <ul>
                                            <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $newProd->id }}" >
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
                                            </li>
                                            <li>
                                                <a class="btn-add-to-wishlist" data-product-id="{{ $newProd->id }}" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
                                            </li>
                                            @if($newProd->variation_type != 0)
                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $newProd->id }}">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @else

                                                <li onclick="add_to_cart('{{ $newProd->id }}', '{{ $newProd->variation_type }}', 1, 0, 0)">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="product-desc_info">
                                        <h3 class="product-name">
                                            <a href="{{ route('product-details',['slug' => $newProd->slug]) }}">{{ $newProd->title }}</a>
                                        </h3>
                                        <div class="price-box">
                                            <span class="new-price">
                                                Nrs.{{ $newProd->discounted_price != NULL || $newProd->discounted_price != 0 ? $newProd->discounted_price : $newProd->price }}
                                            </span>

                                            @if($newProd->discounted_price != NULL || $newProd->discounted_price != 0)
                                                <span class="old-price">Nrs.{{ $newProd->price }}</span>
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

    <!-- Begin Banner Area Three -->
    {{-- <div class="banner-area-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6 custom-xxs-col">
                    <div class="banner-item img-hover_effect">
                        <div class="banner-img">
                            <a href="javascrip:void(0)">
                                <img class="img-full" src="{{ asset('frontend/assets/images/banner/2-1.jpg') }}" alt="Banner">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6 custom-xxs-col">
                    <div class="banner-item img-hover_effect">
                        <div class="banner-img">
                            <a href="javascrip:void(0)">
                                <img class="img-full" src="{{ asset('frontend/assets/images/banner/2-2.jpg') }}" alt="Banner">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6 custom-xxs-col">
                    <div class="banner-item img-hover_effect">
                        <div class="banner-img">
                            <a href="javascrip:void(0)">
                                <img class="img-full" src="{{ asset('frontend/assets/images/banner/2-3.jpg') }}" alt="Banner">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Banner Area Three End Here -->

    <!-- Begin Product Tab Area -->
    <div class="product-tab_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Trending Products</h3>
                        <a href="{{ route('products',['type' => 'trending']) }}" class="main-btn">View All</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="kenne-element-carousel product-tab_slider slider-nav product-tab_arrow"
                        data-slick-options='{
                                "slidesToShow": 5,
                                "slidesToScroll": 1,
                                "infinite": false,
                                "arrows": true,
                                "dots": false,
                                "spaceBetween": 30
                                }' data-slick-responsive='[
                                {"breakpoint":992, "settings": {
                                "slidesToShow": 3,
                                "spaceBetween": 30
                                }},
                                {"breakpoint":768, "settings": {
                                "slidesToShow": 2,
                                "spaceBetween": 30
                                }},
                                {"breakpoint":575, "settings": {
                                "slidesToShow": 2,
                                "spaceBetween": 10
                                }}
                            ]'>
                        @foreach($featured_products as $key => $fProd)
                        <div class="product-item">
                            <div class="single-product">
                                <div class="product-img">
                                    <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">
                                        <img class="primary-img" src="{{ asset('storage/products/'.$fProd->slug.'/thumbs/thumb_'.$fProd->image) }}" alt="{{ $fProd->slug }}">
                                    </a>
                                    {{-- <span class="sticker-2">Hot</span> --}}
                                    <div class="add-actions">
                                        <ul>
                                            <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $fProd->id }}">
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Quick View"><i class="ion-ios-search"></i></a>
                                            </li>
                                            <li>
                                                <a class="btn-add-to-wishlist" data-product-id="{{ $fProd->id }}" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
                                            </li>
                                            @if($fProd->variation_type != 0)
                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $fProd->id }}">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @else

                                                <li onclick="add_to_cart('{{ $fProd->id }}', '{{ $fProd->variation_type }}', 1, 0, 0)">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Add To cart"><i class="ion-bag"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="product-desc_info">
                                        <h3 class="product-name">
                                            <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">{{ $fProd->title }}</a>
                                        </h3>
                                        <div class="price-box">
                                            <span class="new-price">Nrs.{{ $fProd->discounted_price != NULL || $fProd->discounted_price != 0 ? $fProd->discounted_price : $fProd->price }}</span>
                                            @if($fProd->discounted_price != NULL || $fProd->discounted_price != 0)
                                            <span class="old-price">Nrs.{{ $fProd->price }}</span>
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


    <!-- Begin List Product Area -->
    <div class="list-product_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Best <span>Selling Products</span></h3>
                        <a href="{{ route('products',['type' => 'best-selling']) }}" class="main-btn">View All</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="list-product_arrow"></div>
                    <div class="kenne-element-carousel list-product_slider slider-nav" data-slick-options='{
                            "slidesToShow": 3,
                            "slidesToScroll": 1,
                            "infinite": false,
                            "arrows": true,
                            "dots": false,
                            "spaceBetween": 30,
                            "appendArrows": ".list-product_arrow"
                            }' data-slick-responsive='[
                            {"breakpoint":1200, "settings": {
                            "slidesToShow": 2
                            }},
                            {"breakpoint":768, "settings": {
                            "slidesToShow": 1
                            }},
                            {"breakpoint":575, "settings": {
                            "slidesToShow": 1
                            }}
                        ]'>

                        @foreach($featured_products as $key => $fProd)

                        <div class="product-item">
                            <div class="single-product">
                                <div class="product-img">
                                    <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">
                                        <img class="primary-img" src="{{ asset('storage/products/'.$fProd->slug.'/thumbs/thumb_'.$fProd->image) }}" alt="{{ $fProd->slug }}">
                                    </a>
                                    {{-- <span class="sticker-2">-10%</span> --}}
                                </div>
                                <div class="product-content">
                                    <div class="product-desc_info">
                                        <span class="manufacture-product">{{ $fProd->tags }}</span>
                                        <h3 class="product-name">
                                            <a href="{{ route('product-details',['slug' => $fProd->slug]) }}">{{ $fProd->title }}</a>
                                        </h3>
                                        <div class="price-box">
                                            <span class="new-price">Nrs.{{ $fProd->discounted_price != NULL || $fProd->discounted_price != 0 ? $fProd->discounted_price : $fProd->price }}</span>

                                            @if($fProd->discounted_price != NULL || $fProd->discounted_price != 0)
                                                <span class="old-price">Nrs.{{ $fProd->price }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="add-actions">
                                        <ul>
                                            <li>
                                                <a class="btn-add-to-wishlist" data-product-id="{{ $fProd->id }}" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-ios-heart-outline"></i></a>
                                            </li>
                                            @if($fProd->variation_type != 0)
                                                <li class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $fProd->id }}">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Add To cart">Add to cart</a>
                                                </li>
                                            @else

                                                <li onclick="add_to_cart('{{ $fProd->id }}', '{{ $fProd->variation_type }}', 1, 0, 0)">
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Add To cart">Add to cart</a>
                                                </li>
                                            @endif
                                        </ul>
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
    <!-- List Product Area End Here -->

    <!-- Begin Latest Blog Area -->
    <div class="latest-blog_area latest-blog_area-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3>Latest <span>Blog</span></h3>
                        <div class="latest-blog_arrow"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="kenne-element-carousel latest-blog_slider slider-nav" data-slick-options='{
                        "slidesToShow": 2,
                        "slidesToScroll": 1,
                        "infinite": true,
                        "arrows": true,
                        "dots": false,
                        "spaceBetween": 30,
                        "appendArrows": ".latest-blog_arrow"
                        }' data-slick-responsive='[
                        {"breakpoint":992, "settings": {
                        "slidesToShow": 1
                        }},
                        {"breakpoint":768, "settings": {
                        "slidesToShow": 1
                        }},
                        {"breakpoint":575, "settings": {
                        "slidesToShow": 1
                        }}
                    ]'>
                        @foreach($blogs as $key => $blog)
                        <div class="blog-item">
                            <div class="blog-img img-hover_effect">
                                <a href="{{ route('blog-details',$blog) }}">
                                    <img src="{{ asset('storage/blogs/'.$blog->slug.'/thumbs/thumb_'.$blog->image) }}" alt="{{ $blog->slug }}">
                                </a>
                            </div>
                            <div class="blog-content">
                                <h3 class="heading">
                                    <a href="{{ route('blog-details',$blog) }}">{{ $blog->title }}</a>
                                </h3>
                                <p class="short-desc">
                                    {{ substr($blog->short_description, 0, 200) }}..
                                </p>
                                <div class="blog-meta">
                                    <ul>
                                        <li>{{ date('F.d.Y', strtotime($blog->created_at)) }}</li>
                                        {{-- <li>
                                            <a href="javascript:void(0)">02 Comments</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Latest Blog Area End Here -->

    <!-- Begin Brand Area -->
    {{-- <div class="brand-area ">
        <div class="container">
            <div class="brand-nav border-top ">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="kenne-element-carousel brand-slider slider-nav" data-slick-options='{
                            "slidesToShow": 6,
                            "slidesToScroll": 1,
                            "infinite": false,
                            "arrows": false,
                            "dots": false,
                            "spaceBetween": 30
                            }' data-slick-responsive='[
                            {"breakpoint":992, "settings": {
                            "slidesToShow": 4
                            }},
                            {"breakpoint":768, "settings": {
                            "slidesToShow": 3
                            }},
                            {"breakpoint":576, "settings": {
                            "slidesToShow": 2
                            }}
                        ]'>

                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/1.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/2.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/3.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/4.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/5.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/6.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/1.png') }}" alt="Brand Images">
                                </a>
                            </div>
                            <div class="brand-item">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('frontend/assets/images/brand/2.png') }}" alt="Brand Images">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Brand Area End Here -->

    @include('frontend.quick-view-product-modal')
@endsection


@push('post-scripts')
    <script>
        $('.category-slider').slick({
            dots: false,
            arrows: true,
            infinite: true,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 6,
            prevArrow: '<div class="product-arrow"><button class="tty-slick-text-btn tty-slick-text-prev slick-arrow slick-disabled" aria-disabled="true" style="display: block;"><i class="ion-ios-arrow-back"></i></button></div>',
            nextArrow: '<div class="product-arrow"><button class="tty-slick-text-btn tty-slick-text-next slick-arrow" aria-disabled="false" style="display: block;"><i class="ion-ios-arrow-forward"></i></button></div>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    </script>
@endpush