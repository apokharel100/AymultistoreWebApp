@extends('backend.layouts.headerfooter')
@section ('title', 'Settings')
@section('content')
    
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Setting
                <small>Change your Site Setting</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active"><i class="fa fa-cog"></i> Setting</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box box-primary">
                <form role="form" method="post" action="{{ route('admin.setting.update',$setting) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="box-body">
                        <!-- form start -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group" style="padding-top: 65px">
                                        <span class="input-group-addon"><i class="fa fa-image"></i>&nbsp; <b>Logo</b></span>
                                        <input style="padding-bottom: 40px" type="file" name="logo" class="form-control btn btn-primary btn-block" placeholder="Logo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if(!$setting->logo == '')
                                    <div class="Image text-center" >
                                        <img width="150px" src="{{ asset('storage/setting/logo/'.$setting->logo) }}" class="img-responsive" alt="Logo" style="padding-bottom: 20px">
                                    </div>
                                    <div class="clearfix"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group" >
                                        <span class="input-group-addon"><i class="fa fa-image"></i>&nbsp; <b>Favicon</b></span>
                                        <input style="padding-bottom: 40px" type="file" name="favicon" class="form-control btn btn-primary btn-block" placeholder="Favicon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if(!$setting->favicon == '')
                                        <div class="Image text-center">
                                            <img width="50px" src="{{ asset('storage/setting/favicon/'.$setting->favicon) }}" alt="Logo" class="img-responsive">
                                        </div>
                                        <div class="clearfix"></div>
                                    @endif
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-text-width"></i>&nbsp; <b>Site Title</b></span>
                                        <input type="text" name="sitetitle" value="{{ $setting->sitetitle }}"
                                               class="form-control" placeholder="Site Title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i>&nbsp; <b>Site Email</b></span>
                                        <input type="email" name="siteemail" value="{{ $setting->siteemail }}" class="form-control" placeholder="Site Email">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-phone-square"></i>&nbsp; <b>Phone</b></span>
                                        <input type="text" min="0" name="phone" value="{{ $setting->phone }}" class="form-control" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-mobile"></i>&nbsp; <b>Mobile</b></span>
                                        <input type="text" min="0" name="mobile" value="{{ $setting->mobile }}"
                                               class="form-control" placeholder="Mobile">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="fa fa-print"></i>&nbsp; <b>Fax</b></span>
                                        <input type="text" name="fax" value="{{ $setting->fax }}" class="form-control"
                                               placeholder="fax">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-map-marker"></i>&nbsp; <b>Address</b></span>
                                        <input type="text" name="address" value="{{ $setting->address }}"
                                               class="form-control" placeholder="Mobile">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-facebook-square"></i>&nbsp; <b>Facebook Url</b></span>
                                        <input type="text" name="facebookurl" value="{{ $setting->facebookurl }}"
                                               class="form-control" placeholder="Facebook Url">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-twitter"></i>&nbsp; <b>Twitter Url</b></span>
                                        <input type="text" name="twitterurl" value="{{ $setting->twitterurl }}"
                                               class="form-control" placeholder="Twitter Url">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="fa fa-instagram"></i>&nbsp; <b>Instagram Url</b></span>
                                        <input type="text" name="instagramurl" value="{{ $setting->instagramurl }}" class="form-control" placeholder="Instagram Url">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-youtube"></i>&nbsp; <b>Youtube Url</b></span>
                                        <input type="text" name="youtubeurl" value="{{ $setting->youtubeurl }}" class="form-control" placeholder="Youtube Url">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-newspaper-o"></i>&nbsp; <b>Short Description</b></span>
                                        <input type="text" name="short_content" value="{{ $setting->short_content }}" class="form-control" placeholder="Short Details about your Site" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i>&nbsp; <b>Delivery Charge</b></span>
                                        <input type="text" name="delivery_charge" value="{{ $setting->delivery_charge }}" class="form-control" placeholder="Delivery Charge">
                                    </div>
                                </div>
                            </div>
                            <div class="box box-default box-solid">
                                <div class="box-header">
                                    <strong>Update SEO Attributes Your Site</strong>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-facebook-square"></i>&nbsp; <b>OG Title</b>
                                                </span>
                                                <input type="text" name="og_title" value="{{ $setting->og_title }}" class="form-control" placeholder="OG Title">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-text-width"></i>&nbsp; <b>Meta Title</b>
                                                </span>
                                                <input type="text" name="meta_title" value="{{ $setting->meta_title }}" class="form-control" placeholder="Meta Title">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-facebook-square"></i>&nbsp; <b>OG Description</b>
                                                </span>
                                                <textarea class="form-control" name="og_description">{{ $setting->og_description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-text-width"></i>&nbsp; <b>Meta Description</b>
                                                </span>
                                                <textarea class="form-control" name="meta_description">{{ $setting->meta_description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-offset-6 col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-text-width"></i>&nbsp; <b>Meta Keywords</b>
                                                </span>
                                                <input type="text" name="meta_keywords" value="{{ $setting->meta_keywords }}" class="form-control" placeholder="Meta Keywords">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div class="box box-default box-solid">
                                                <div class="box-body ">
                                                    <div class="col-md-6">
                                                        <small>Recommended OG Image Size : 1200px X 1200px for best fit</small>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-facebook-square"></i>&nbsp; <b>OG Image</b>
                                                            </span>
                                                            <input type="file" name="og_image" class="form-control btn btn-default">
                                                        </div>
    
                                                    </div>
                                                    <div class="col-md-6">
                                                        <img src="{{ asset('storage/setting/og_image/og_'.$setting->og_image) }}" data-toggle="tooltip" data-placement="top" title="" alt="NO OG IMAGE" class="rounded img-thumbnail" width="80px" data-original-title="OG IMAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
            
        </section>
    </div>
    
@endsection
