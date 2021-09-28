<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $setting->sitetitle }} | @yield('title')</title>
    
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="{{ $setting->meta_description }}">
    <meta name="keywords" content="{{ $setting->meta_keywords }}">

    @if(request()->routeIs('home'))
        <meta property="og:title" content="{{ $setting->og_title }}"/>
    @else
        <meta property="og:title" content="@yield('title') - {{ $setting->og_title }}"/>
    @endif

    <meta property="og:description" content="{{ $setting->og_description }}"/>
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:image" content="{{ asset('storage/setting/og_image/og_'.$setting->og_image) }}"/>

    <meta content="telephone=yes" name="format-detection"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}">

    <!-- CSS    ============================================ -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Limelight&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/bootstrap.min.css') }}">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/font-awesome.min.css') }}">
    <!-- Fontawesome Star -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/fontawesome-stars.min.css') }}">
    <!-- Ion Icon -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/ion-fonts.css') }}">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/slick.css') }}">
    <!-- Animation -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/animate.css') }}">
    <!-- jQuery Ui -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/jquery-ui.min.css') }}">
    <!-- Nice Select -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/nice-select.css') }}">
    <!-- Timecircles -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/timecircles.css') }}">

    <!-- Vendor & Plugins CSS (Please remove the comment from below vendor.min.css & plugins.min.css for better website load performance and remove css files from the above) -->

    {{-- <script src="{{ asset('frontend/assets/js/vendor/vendor.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/plugins/plugins.min.js') }}"></script> --}}
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/toastr/toastr.min.css')}}">

    <!-- Main Style CSS (Please use minify version for better website load performance) -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <!--<link rel="stylesheet" href="assets/css/style.min.css">-->
    @stack('post-css')

</head>

<body class="template-color-2">
    <div id="modal-loader" >
        <div class="loadingio-spinner-eclipse-5n5ocxxlhe2">
            <div class="ldio-shhdvnglxrk">
                <div></div>
            </div>
        </div>
    </div>

    <div class="main-wrapper">

        <!-- Begin Main Header Area Two -->
        <header class="main-header_area-2">
            <div class="header-top_area d-none d-lg-block">
                <div class="container">
                    <div class="header-top_nav">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="ht-menu">
                                    <ul>
                                        <li>
                                            <a href="mailto:{{ $setting->siteemail }}">
                                                <i class="ion-email"></i>&nbsp;&nbsp;&nbsp; {{ $setting->siteemail }}
                                            </a>

                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="header-top_right">
                                    <ul>
                                        @if(Auth::check())
                                            <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                                            <li><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
                                            <li>
                                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                            </li>
                                            <li><a href="{{ route('checkout') }}">Checkout</a></li>
                                        @else
                                            <li><a href="{{ route('user.login') }}">Login</a></li>
                                            <li><a href="{{ route('user.register') }}">Register</a></li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle_area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="header-middle_nav">
                                <div class="header-logo_area">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('storage/setting/logo/'.$setting->logo) }}" alt="Header Logo" style="max-height: 70px;">
                                    </a>
                                </div>
                                <div class="header-contact d-none d-md-flex">
                                    <i class="fa fa-headphones-alt"></i>
                                    <div class="contact-content">
                                        <p>
                                            Call us
                                            <br>
                                            Free Support: <a href='tel:{{ preg_replace("/[^0-9,+]/", "", $setting->phone)}}'>{{ $setting->phone }}</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="header-search_area d-none d-lg-block">
                                    <form class="search-form" action="#">
                                        <input type="text" placeholder="Search">
                                        <button class="search-button"><i class="ion-ios-search"></i></button>
                                    </form>
                                </div>
                                @php
                                    $cart_items = (array)session()->get('cart');
                                    $cart_total_price = session()->get('total_price');
                                    $total_price = 0;
                                    $cart_items_count =count($cart_items);
                                @endphp
                                <div class="header-right_area d-none d-lg-inline-block">
                                    <ul>
                                        <li class="mobile-menu_wrap d-flex d-lg-none">
                                            <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn color--white">
                                                <i class="ion-android-menu"></i>
                                            </a>
                                        </li>
                                        <li class="minicart-wrap">
                                            <a href="#miniCart" class="minicart-btn toolbar-btn">
                                                <div class="minicart-count_area">
                                                    <span class="item-count">{{ $cart_items_count }}</span>
                                                    <i class="ion-bag"></i>
                                                </div>
                                                <div class="minicart-front_text">
                                                    <span>Cart:</span>
                                                    Nrs.<span class="total-price cart-total-price" id="cartTotalPrice">{{ $cart_total_price ? $cart_total_price : 0 }}</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-right_area header-right_area-2 d-inline-block d-lg-none">
                                    <ul>
                                        <li class="mobile-menu_wrap d-inline-block d-lg-none">
                                            <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn color--white">
                                                <i class="ion-android-menu"></i>
                                            </a>
                                        </li>
                                        <li class="minicart-wrap">
                                            <a href="#miniCart" class="minicart-btn toolbar-btn">
                                                <div class="minicart-count_area">
                                                    <span class="item-count">{{ $cart_items_count }}</span>
                                                    <i class="ion-bag"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#searchBar" class="search-btn toolbar-btn">
                                                <i class="pe-7s-search"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#offcanvasMenu"
                                                class="menu-btn toolbar-btn color--white d-none d-lg-block">
                                                <i class="ion-android-menu"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="offcanvas-minicart_wrapper" id="miniCart">
                <div class="offcanvas-menu-inner">
                    <a href="javascript:void(0)" class="btn-close"><i class="ion-android-close"></i></a>
                    @if($cart_items_count > 0)
                        <div class="minicart-content">
                            <div class="minicart-heading">
                                <h4>Shopping Cart</h4>
                            </div>
                            <ul class="minicart-list">
                                @foreach($cart_items as $key => $item)
                                    @php
                                        $cProd = \App\Models\Product::where("id", $item["product_id"])->first();
                                        $total_price += $item['sub_total'];
                                    @endphp
                                    <li class="minicart-product">
                                        <a class="product-item_remove" title="Remove this Item" onclick="cartDelete('{{ $key }}')"><i class="ion-android-close"></i></a>

                                        <div class="product-item_img">
                                            <img src="{{ asset('storage/products/'.$cProd->slug.'/thumbs/thumb_'.$cProd->image) }}" alt="{{ $cProd->slug }}">
                                        </div>

                                        <div class="product-item_content">
                                            <strong><a class="product-item_title" href="{{ route('product-details',['slug' => $cProd->slug]) }}">{{ $cProd->title }}</a></strong>
                                            @if($item['color_code'] != NULL)
                                                <p class="cartdetail" style="justify-content: left;">
                                                    <span>Color: <span class="cart-color" style="background-color: {{ $item['color_code'] }};"></span>
                                                    </span>
                                                    @if($item['size_name'] != NULL)
                                                    <span>Size: <span class="cart-size"><strong>{{ $item['size_name'] }}</strong></span>
                                                    </span>
                                                    @endif
                                                </p>

                                            @endif

                                            <span class="product-item_quantity">{{ $item['ordered_qty'] }} x Nrs.{{ (float)$item['sub_total']/(float)$item['ordered_qty'] }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="minicart-item_total">
                            <span>Subtotal</span>
                            <span class="ammount">Nrs.{{ $total_price }}</span>
                        </div>
                        <div class="minicart-btn_area">
                            <a href="{{ route('cart') }}" class="kenne-btn kenne-btn_fullwidth">View Cart</a>
                        </div>
                        <div class="minicart-btn_area">
                            <a href="{{ route('checkout') }}" class="kenne-btn kenne-btn_fullwidth">Checkout</a>
                        </div>

                    @else
                        <div class="text-center">
                            <strong>No Items in the Cart</strong>
                            <p>Keep Shopping</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="header_area">
                <div class="main_header_area animated">
                    <div class="container">
                        <nav id="navigation1" class="navigation">
                            <!-- Logo Area Start -->
                            <div class="nav-header">
                                <a class="nav-brand" href="{{ route('home') }}">
                                    <img src="{{ asset('storage/setting/logo/'.$setting->logo) }}" alt="Header Logo" style="max-width: 70px;">
                                </a>
                                <div class="nav-toggle"></div>
                            </div>
                            <!-- Search panel Start -->
                            <div class="nav-search">
                                <div class="nav-search-button">
                                    <span class="nav-search-icon">
                                        <i class="fa fa-shopping-cart"></i>
                                    </span>
                                </div>
                                <form>
                                    <div class="nav-search-inner">
                                        <input type="search" name="search" placeholder="Type Your Keywords">
                                    </div>
                                </form>
                            </div>
                            <div class="nav-cart">
                                <div class="minicart-wrap">
                                    <a href="#miniCart" class="minicart-btn toolbar-btn">
                                        <div class="minicart-count_area">
                                            <span class="item-count">{{ $cart_items_count }}</span>
                                            <i class="ion-bag"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- Main Menus Wrapper -->
                            <div class="nav-menus-wrapper">
                                <ul class="nav-menu align-to-center">
                                    {{-- <li>
                                        <a href="index.html">Home</a>
                                    </li> --}}
                                    <?php

                                        function displayList($list){
                                            foreach ($list as $item){
                                                ?>
                                                <li>
                                                    <?php
                                                        if (array_key_exists("children", (array)$item)) {
                                                            ?>
                                                            <a href="javascript:void(0)">{{ $item->title }}</a>
                                                            <ul class="nav-dropdown">
                                                                {{ displayList( $item->children) }}
                                                            </ul>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <a href="{{ route('category-products',['slug' => $item->slug]) }}">{{ $item->title }}</a>
                                                            <?php
                                                        }
                                                    ?>
                                                </li>
                                                <?php
                                            }
                                        }
                                    ?>
                                    {{ displayList($menu_categories) }}

                                    @if(Auth::check())
                                        <li class="mobile-only"><a href="{{ route('customer.my-account') }}">My Account</a></li>
                                        <li class="mobile-only"><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
                                        <li class="mobile-only">
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                        </li>
                                        <li class="mobile-only"><a href="{{ route('checkout') }}">Checkout</a></li>
                                    @else
                                        <li class="mobile-only"><a href="{{ route('user.login') }}">Login</a></li>
                                        <li class="mobile-only"><a href="{{ route('user.register') }}">Register</a></li>
                                    @endif

                                    <li><a href="{{ route('about-us') }}">About Us </a></li>
                                    <li><a href="{{ route('contact-us') }}">Contact Us</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="global-overlay"></div>
        </header>
        <!-- Main Header Area End Here Two -->

        @yield('content')

        <!-- Begin  Footer Area -->
        <div class="kenne-footer_area bg-smoke_color">
            <div class="footer-top_area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="newsletter-area">
                                <div class="newsletter-logo">
                                    <a href="javascript:void(0)">
                                        <img src="{{ asset('storage/setting/logo/'.$setting->logo) }}" alt="Logo" style="max-height: 90px;">
                                    </a>
                                </div>
                                <p class="short-desc">Subscribe to our newsleter, Enter your emil address</p>
                                <div class="newsletter-form_wrap">
                                    <form
                                        action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                                        method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                        class="newsletters-form validate" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll">
                                            <div id="mc-form" class="mc-form subscribe-form">
                                                <input id="mc-email" class="newsletter-input" type="email"
                                                    autocomplete="off" placeholder="Enter email address" />
                                                <button class="newsletter-btn" id="mc-submit"><i
                                                        class="ion-android-mail"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 offset-lg-1">
                            <div class="row footer-widgets_wrap">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="footer-widgets_title">
                                        <h4>Shopping</h4>
                                    </div>
                                    <div class="footer-widgets">
                                        <ul>
                                            <li><a href="{{ route('about-us') }}">About Us</a></li>
                                            <li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
                                            <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                                            <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="footer-widgets_title">
                                        <h4>Account</h4>
                                    </div>
                                    <div class="footer-widgets">
                                        <ul>
                                            @if(Auth::check())
                                                <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                                                <li><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
                                            @else
                                                <li><a href="{{ route('user.login') }}">Login</a></li>
                                                <li><a href="{{ route('user.register') }}">Register</a></li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="footer-widgets_title">
                                        <h4>Categories</h4>
                                    </div>
                                    <div class="footer-widgets">
                                        <ul>
                                            <li><a href="javascript:void(0)">Men</a></li>
                                            <li><a href="javascript:void(0)">Women</a></li>
                                            <li><a href="javascript:void(0)">Jeans</a></li>
                                            <li><a href="javascript:void(0)">Shoes</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom_area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="copyright">
                                <span>
                                    Copyright &copy; {{ date('Y') }} <a href="javascript:void(0)">{{ $setting->sitetitle }}.</a> 
                                    All rights reserved.
                                </span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--  Footer Area End Here -->
        
        <!-- Scroll To Top Start -->
        <a class="scroll-to-top" href=""><i class="ion-chevron-up"></i></a>
        <!-- Scroll To Top End -->

    </div>

    <!-- JS
============================================ -->

    <!-- jQuery JS -->
    <script src="{{ asset('frontend/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- Modernizer JS -->
    <script src="{{ asset('frontend/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('frontend/assets/js/vendor/popper.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('frontend/assets/js/vendor/bootstrap.min.js') }}"></script>

    <!-- Slick Slider JS -->
    <script src="{{ asset('frontend/assets/js/plugins/slick.min.js') }}"></script>
    <!-- Barrating JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery.barrating.min.js') }}"></script>
    <!-- Counterup JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery.counterup.js') }}"></script>
    <!-- Nice Select JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery.nice-select.js') }}"></script>
    <!-- Sticky Sidebar JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery.sticky-sidebar.js') }}"></script>
    <!-- Jquery-ui JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/plugins/jquery.ui.touch-punch.min.js') }}"></script>
    <!-- Theia Sticky Sidebar JS -->
    <script src="{{ asset('frontend/assets/js/plugins/theia-sticky-sidebar.min.js') }}"></script>
    <!-- Waypoints JS -->
    <script src="{{ asset('frontend/assets/js/plugins/waypoints.min.js') }}"></script>
    <!-- jQuery Zoom JS -->
    <script src="{{ asset('frontend/assets/js/plugins/jquery.zoom.min.js') }}"></script>
    <!-- Timecircles JS -->
    <script src="{{ asset('frontend/assets/js/plugins/timecircles.js') }}"></script>

    <!-- Vendor & Plugins JS (Please remove the comment from below vendor.min.js & plugins.min.js for better website load performance and remove js files from avobe) -->
    
    <script src="{{ asset('frontend/assets/js/vendor/vendor.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/plugins/plugins.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    <script>
        $("#range-picker").click(function(e){
            var sizeList = document.getElementById('range-picker').children;
            for (var i = 0; i <= sizeList.length - 1; i++) {
                console.log(sizeList[i].classList);
                if (sizeList[i].classList.contains('active')) {
                    sizeList[i].classList.remove('active');
                }
            }
            e.target.classList.add('active');
        });
        

    </script>

    @stack('post-scripts')

    <script>
        // Color Select
        $('.color-list a').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.addClass('active');
            $this.siblings().removeClass('active');

            var has_sizes = $(this).data('has-sizes');
            var color_id = $(this).data('color-id');
            // alert(color_id);
            if (has_sizes == 1) {
                get_related_sizes(color_id);
            }else{
                var max_order_qty = $(this).data('stock-count');
                $("#ordered-qty").val(1);
                $("#ordered-qty").attr('max',max_order_qty);
            }
            
        });


        // Size Select
        $("#size-picker div").click(function(e){
            var sizeList = document.getElementById('size-picker').children;
            for (var i = 0; i <= sizeList.length - 1; i++) {
                console.log(sizeList[i].classList);
                if (sizeList[i].classList.contains('active')) {
                    sizeList[i].classList.remove('active');
                }
            }
            e.target.classList.add('active');

            var max_order_qty = $(this).data('stock-count');
            $("#ordered-qty").val(1);
            $("#ordered-qty").attr('max',max_order_qty);
        });


        // Quantity Select
        // $('.qtybutton').on('click', function () {
        //     var $button = $(this);
        //     var oldValue = $button.parent().find('input').val();
        //     if ($button.hasClass('inc')) {

        //         var newVal = parseFloat(oldValue) + 1;
        //         if (newVal > $('#ordered-qty').attr('max')) {
        //             newVal = $('#ordered-qty').attr('max');
        //         }

        //     } else {
        //         // Don't allow decrementing below zero
        //         if (oldValue > 1) {
        //             var newVal = parseFloat(oldValue) - 1;
        //         } else {
        //             newVal = 1;
        //         }
        //     }
        //     $button.parent().find('input').val(newVal);
        // });

        // Add to Cart
        $("#add-to-cart").click(function(){
            var product_id = $(this).data('product-id');
            var variation_type = $(this).data('variation-type');
            var ordered_qty = $('#ordered-qty').val();
            var color_id = 0;
            var size_id = 0;

            if (variation_type > 0) {
                
                color_id = $("#color-picker .active").data('color-id');
                // has_sizes = $(".color-list .active").data('has-sizes');
            }

            if (variation_type > 1) {
                size_id = $("#size-picker .active").data('size-id');
            }

            // console.log(product_id+ ' --- ' + variation_type+ ' --- ' + ordered_qty+ ' --- ' + color_id+ ' --- ' + size_id);
            add_to_cart(product_id, variation_type, ordered_qty, color_id, size_id);

        });

        $(".quick-view-btn").click(function(){

            var product_id = $(this).data('product-id');
            
            $.ajax({
                url : "{{ URL::route('get-product-details') }}",
                type : "POST",
                data : { '_token': '{{ csrf_token() }}',
                            product_id: product_id
                        },
                cache : false,
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {

                        var obj = jQuery.parseJSON($response.responseText);
                        $("#quickViewProductDetails").html(obj.product_details);

                        // Color Select
                        $('.color-list a').on('click', function (e) {
                            e.preventDefault();

                            var $this = $(this);
                            $this.addClass('active');
                            $this.siblings().removeClass('active');

                            var has_sizes = $(this).data('has-sizes');
                            var color_id = $(this).data('color-id');
                            // alert(color_id);
                            if (has_sizes == 1) {
                                get_related_sizes(color_id);
                            }else{
                                var max_order_qty = $(this).data('stock-count');
                                $("#ordered-qty").val(1);
                                $("#ordered-qty").attr('max',max_order_qty);
                            }
                            
                        });


                        // Size Select
                        $("#size-picker div").click(function(e){
                            var sizeList = document.getElementById('size-picker').children;
                            for (var i = 0; i <= sizeList.length - 1; i++) {
                                console.log(sizeList[i].classList);
                                if (sizeList[i].classList.contains('active')) {
                                    sizeList[i].classList.remove('active');
                                }
                            }
                            e.target.classList.add('active');

                            var max_order_qty = $(this).data('stock-count');
                            $("#ordered-qty").val(1);
                            $("#ordered-qty").attr('max',max_order_qty);
                        });


                        // Quantity Select
                        $('.qtybutton').on('click', function () {
                            var $button = $(this);
                            var oldValue = $button.parent().find('input').val();
                            if ($button.hasClass('inc')) {

                                var newVal = parseFloat(oldValue) + 1;
                                if (newVal > $('#ordered-qty').attr('max')) {
                                    newVal = $('#ordered-qty').attr('max');
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
                        });

                        // Add to Cart
                        $("#add-to-cart").click(function(){
                            var product_id = $(this).data('product-id');
                            var variation_type = $(this).data('variation-type');
                            var ordered_qty = $('#ordered-qty').val();
                            var color_id = 0;
                            var size_id = 0;

                            if (variation_type > 0) {
                                
                                color_id = $("#color-picker .active").data('color-id');
                                // has_sizes = $(".color-list .active").data('has-sizes');
                            }

                            if (variation_type > 1) {
                                size_id = $("#size-picker .active").data('size-id');
                            }

                            // console.log(product_id+ ' --- ' + variation_type+ ' --- ' + ordered_qty+ ' --- ' + color_id+ ' --- ' + size_id);
                            add_to_cart(product_id, variation_type, ordered_qty, color_id, size_id);

                        });
                    }
                    $('#modal-loader').hide();
                }
            })
        });

        function add_to_cart(product_id, variation_type, ordered_qty, color_id, size_id) {

            $.ajax({
                url : "{{ URL::route('add-to-cart') }}",
                type : "POST",
                data : { 
                            '_token': '{{ csrf_token() }}',
                            product_id: product_id,
                            variation_type: variation_type,
                            ordered_qty: ordered_qty,
                            color_id: color_id,
                            size_id: size_id
                        },
                cache : false,
                beforeSend : function (){``
                    $('#modal-loader').show();
                },
                success : function(response){
                    $('#modal-loader').hide();
                    var obj = jQuery.parseJSON( response);
                    // console.log(response);
                    if (obj.status=='success') {

                        $('.item-count').html(obj.total_qty);
                        $('.cart-total-price').html(obj.total_price);
                        $('#miniCart').load(document.URL + ' #miniCart>*');
                        $('#cartTable').load(document.URL + ' #cartTable>*');

                        toastr['success']('Item Added to Cart');
                        // alert('Item Added to Cart');
                    }else if(obj.status == 'stockerror') {

                        var stock = obj.stock;
                        var in_cart = obj.in_cart;

                        toastr["error"]("<span>Available : "+stock+"</span><br><span>In Cart : "+in_cart+"</span>", "Out of Stock!");
                        // alert("<span>Available : "+stock+"</span><br><span>In Cart : "+in_cart+"</span>", "Out of Stock!");
                    }


                }
            });
        }        

        function get_related_sizes(color_id) {
            $.ajax({
                url : "{{ URL::route('get-related-sizes') }}",
                type : "POST",
                data : { '_token': '{{ csrf_token() }}',
                            color_id: color_id
                        },
                cache : false,
                beforeSend : function (){
                    // $('#modal-loader').show();
                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {

                        var obj = jQuery.parseJSON($response.responseText);
                        $("#size-picker").html(obj.related_sizes);

                        // Size Select
                        $("#size-picker div").click(function(e){
                            var sizeList = document.getElementById('size-picker').children;
                            for (var i = 0; i <= sizeList.length - 1; i++) {
                                console.log(sizeList[i].classList);
                                if (sizeList[i].classList.contains('active')) {
                                    sizeList[i].classList.remove('active');
                                }
                            }
                            e.target.classList.add('active');

                            var max_order_qty = $(this).data('stock-count');
                            $("#ordered-qty").val(1);
                            $("#ordered-qty").attr('max',max_order_qty);
                        });
                        $("#ordered-qty").val(1);
                        $("#ordered-qty").attr('max',obj.max_order_qty);
                    }
                }
            });
        }

        function cartDelete(cart_item_key) {

            $.ajax({
                url: "{{ URL::route('delete-cart-item') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    action: 'delete',
                    item_key: cart_item_key
                },
                beforeSend: function () {

                },
                success: function (response) {
                    // console.log("success");
                    // console.log("response " + response);

                    var obj = jQuery.parseJSON(response);

                    if (obj.status == 'deleted') {
                        var totalQty = obj.totalQty;

                        // $('#cartCount').html(obj.totalQty);
                        // $('#cartQuickView').load(location.href + ' #cartQuickView>*');
                        // $('#cartTable').load(document.URL + ' #cartTable>*');

                        $('.item-count').html(obj.total_qty);
                        $('.cart-total-price').html(obj.total_price);
                        $('#miniCart').load(document.URL + ' #miniCart>*');
                        $('#cartTable').load(document.URL + ' #cartTable>*');

                        toastr['error']('Product Removed from Cart!');


                    }
                    ;
                }
            });
        }

        $(".btn-add-to-wishlist").click(function(){

            var productId = $(this).data("product-id");

            add_to_wishlist(productId);

        });

        function add_to_wishlist(product_id) {

            $.ajax({
                url : "{{ URL::route('add-to-wishlist') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        product_id: product_id
                    },
                beforeSend: function(){                

                },
                success : function(response)
                {
                    var obj = jQuery.parseJSON(response);

                    if (obj.status=='success') {

                        toastr['success']('Product added to Wishlist Successfully!');

                    }else if (obj.status=='exist') {

                        toastr['warning']('Product already exists in your Wishlist!');

                        $('#wishlistItems').load(document.URL + ' #wishlistItems>*');

                    }else if (obj.status=='error') {

                        toastr['error']('Something went wrong!');

                    }else if(obj.status == 'login-error') {
                        
                        toastr['error']('Please Login First!');

                    }else if(obj.status == 'not-a-customer') {

                        toastr['error']('You must be logged in as a customer!!');

                    };
                }
            });
        }
    </script>

    <!-- Toastr -->
    <script src="{{ asset('frontend/assets/plugins/toastr/toastr.js') }}"></script>
    <script type="text/javascript">
        toastr.options.timeOut = "4000";
        toastr.options.closeButton = true;
        toastr.options.positionClass = 'toast-top-right';
        toastr.options.preventDuplicates= true;
        toastr.options.progressBar= true;

    </script>
    {{-- <script>
        toastr['success']('Item Added to Cart Successfully', 'Success!');
    </script> --}}
    @if (session('status'))
        <script>
            toastr['success']('{{ session('status') }}', 'Success!');
        </script>
    @elseif (session('error'))
        <script>
            toastr['error']('{{ session('error') }}');
        </script>

    @elseif (session('log_status'))
        <script>
            toastr['error']('{{ session('log_status') }}', '');
        </script>

    @elseif (session("parent_status"))
        <script>
            toastr['error']('{{ session("parent_status")["secondary"] }}', '{{ session("parent_status")["primary"] }}');
        </script>

    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $key=>$error)
            <script>
                toastr['error']('{{ $error }}');
            </script>
        @endforeach

        <script>
            var $alert = $('.cart-alert-message');
            $alert.hide();

            var i = 0;
            setInterval(function () {
                $($alert[i]).show();
                $($alert[i]).addClass('flipInX');
                i++;
            }, 500);

            // $(".cart-alert-message").fadeTo((($alert.length)+1)*1000, 0.1).slideUp('slow');
            setTimeout(function () {
                $('.cart-alert-message').addClass('fadeOutRight');
            }, $alert.length * ($alert.length == 1 ? 5000 : 2000));
        </script>
    @endif

    <script>

        $(document).scroll(function() {
            var y = $(this).scrollTop();
            if (y > 200) {
                $('.header_area').addClass("sticky colored");
            } 
            else{
                $('.header_area').removeClass("sticky colored");
            }
        });
    </script>

</body>

</html>