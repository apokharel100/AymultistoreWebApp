@extends('backend.layouts.headerfooter')
@section ('title', 'Dashboard')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Aayuva International</small>
        </h1>
        {{-- <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        </ol> --}}
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.users.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-user-secret"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Users</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.setting') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-cog"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Site Settings</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.categories.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-anchor"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Categories</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.products.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-orange"><i class="fa fa-shopping-cart"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Products</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.brands.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-olive"><i class="fa fa-rebel"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Brands</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.banners.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-image"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Banners</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.colors.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-eyedropper"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Colors</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.sizes.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fa fa-object-ungroup"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Sizes</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.discount-coupons.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-percent"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Discount Coupons</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.orders.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-olive"><i class="fa fa-opencart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Orders</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('admin.blogs.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-rss"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Go To Page
                                <small><i class="fa fa-chevron-right" aria-hidden="true"></i></small>
                            </span>
                            <span class="info-box-number">Blogs</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
