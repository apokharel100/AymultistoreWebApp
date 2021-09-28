@extends('layouts.app')
@section('title', Auth::user()->name.' - Wishlists')
@section('content')
	<div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Wishlist</h2>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Wishlist</li>
                </ul>
            </div>
        </div>
    </div>

    <main class="page-content">
        <div class="account-page-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <ul class="nav myaccount-tab-trigger">
                            <li class="nav-item">
                                <a href="{{ route('customer.my-account') }}" class="nav-link d-flex flex-row justify-content-between align-items-center" href="javascript:void(0)">Dashboard <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.orders') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Orders <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.addresses') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Addresses <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.settings') }}" class="nav-link d-flex flex-row justify-content-between align-items-center">Account Details <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active d-flex flex-row justify-content-between align-items-center">Wishlist<i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link d-flex flex-row justify-content-between align-items-center">Logout <i class="fa fa-chevron-right text-right"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="tab-content myaccount-tab-content">
                            <div class="tab-pane show active">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="javascript:void(0)">
                                            <div class="table-content table-responsive">
                                                <table class="table" id="wishlistItems">
                                                    <thead>
                                                        <tr>
                                                            <th class="kenne-product_remove">remove</th>
                                                            <th class="kenne-product-thumbnail">images</th>
                                                            <th class="cart-product-name">Product</th>
                                                            <th class="kenne-product-price">Unit Price</th>
                                                            <th class="kenne-product-stock-status">Stock
                                                                Status</th>
                                                            <th class="kenne-cart_btn">add to cart</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($wishlists as $key => $wishlist)
                                                        <tr>
                                                            <td class="kenne-product_remove">
                                                                <a onclick="removeFromWishlist('{{ $wishlist->id }}')" href="javascript:void(0)"><i class="fa fa-trash" title="Remove"></i></a>
                                                            </td>
                                                            <td class="kenne-product-thumbnail">
                                                                <a href="{{ route('product-details',['slug' => $wishlist->product->slug]) }}">
                                                                    <img width="30%" src="{{ asset('storage/products/'.$wishlist->product->slug.'/thumbs/thumb_'.$wishlist->product->image) }}" alt="{{ $wishlist->product->slug }}"></a>
                                                            </td>
                                                            <td class="kenne-product-name">
                                                                <a href="{{ route('product-details',['slug' => $wishlist->product->slug]) }}">{{ $wishlist->product->title }}</a>
                                                            </td>
                                                            <td class="kenne-product-price">
                                                                <span class="amount">
                                                                    Nrs.{{ $wishlist->product->discounted_price != NULL || $wishlist->product->discounted_price != 0 ? $wishlist->product->discounted_price : $wishlist->product->price }}
                                                                </span>
                                                            </td>
                                                            <td class="kenne-product-stock-status">
                                                                <span class="{{ $wishlist->product->stock_status == 1 ? 'in-stock' : 'out-stock' }}">{{ $wishlist->product->stock_status == 1 ? 'In Stock' : 'Out Stock' }}</span>
                                                            </td>

                                                            <td class="kenne-cart_btn">
                                                                @if($wishlist->product->stock_status == 1)

                                                                    @if($wishlist->product->variation_type != 0)
                                                                        <a href="javascript:void(0)" class="quick-view-btn" data-toggle="modal" data-target="#quickView" data-product-id="{{ $wishlist->product->id }}">add to cart</a>
                                                                    @else
                                                                        <a href="javascript:void(0)" onclick="add_to_cart('{{ $wishlist->product->id }}', '{{ $wishlist->product->variation_type }}', 1, 0, 0)">add to cart</a>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
        <!-- Kenne's Account Page Area End Here -->
    </main>
    @include('frontend.quick-view-product-modal')
@endsection

@push('post-scripts')
    <script>
        function removeFromWishlist(id) {
            $.ajax({
                url: "{{ URL::route('customer.remove-from-wishlist') }}",
                type: "POST",
                data: {
                        '_token': '{{ csrf_token() }}', 
                        action: 'delete', 
                        wishlist_id: id
                },
                beforeSend: function () {

                },
                success: function (response) {
                    // console.log("success");
                    // console.log("response " + response);
                    var obj = jQuery.parseJSON(response);

                    if (obj.status == 'removed') {
                        $('#wishlistItems').load(document.URL + ' #wishlistItems');
                        toastr['success']('Product removed from Wishlist Successfully.','Removed');

                    }else{
                        toastr['error']('Something Went Wrong!','Error');
                    }
                }
            });
        }
    </script>
@endpush