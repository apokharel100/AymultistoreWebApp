<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $setting->sitetitle }} | @yield('title')</title>
    <link rel="icon" href="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" type="image/png"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- sweet alert -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/dist/css/sweetalert.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/toastr/toastr.min.css')}}">
    <!-- Tags -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
    <!-- Fancybox -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/dist/css/jquery.fancybox.min.css') }}">
    <!-- nestable -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/nestable.css') }}"/>
    <!-- jquery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Morris charts -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/morris.js/morris.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/skins/_all-skins.min.css') }}">
    @stack('post-css')
    <!-- Custom style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- jQuery 3 -->
    <script src="{{ asset('backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
</head>

<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">

<div id="modal-loader" >
    <div class="loadingio-spinner-eclipse-5n5ocxxlhe2">
        <div class="ldio-shhdvnglxrk">
            <div></div>
        </div>
    </div>
</div>
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('/admin')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>I</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Muhartha</b>Admin</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a target="_blank" href="{{ url('/')}}"><i class="fa fa-reply"></i> View Site </a>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ asset('backend/dist/img/admin.jpg') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since {{ date('F jS, Y',strtotime(Auth::user()->created_at)) }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    @if(Auth::user()->role != 1)
                                        <a href="{{ url('admin/'.__('admin_details.branch_id').'/users/edituser/'.Auth::user()->id) }}" class="btn btn-default btn-flat">Profile</a>
                                    @else
                                    <a href="{{ url('admin/superusers') }}" class="btn btn-default btn-flat">Show Super Users</a>
                                    @endif
                                </div>

                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                
                <img src="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" class="img-responsive center-block" alt="User Image">
                
            </div>

            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-user-secret"></i> <span>Users</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.setting') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting') }}">
                        <i class="fa fa-gear"></i> <span>Settings</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fa fa-anchor"></i> <span>Categories</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Products</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brands.index') }}">
                        <i class="fa fa-rebel"></i> <span>Brands</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}">
                        <i class="fa fa-image"></i> <span>Banners</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.colors.index') }}">
                        <i class="fa fa-eyedropper"></i> <span>Colors</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sizes.index') }}">
                        <i class="fa fa-object-ungroup"></i> <span>Sizes</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.discount-coupons.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.discount-coupons.index') }}">
                        <i class="fa fa-percent"></i> <span>Discount Coupons</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fa fa-opencart"></i> <span>Orders</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.blogs.index') }}">
                        <i class="fa fa-rss"></i> <span>Blogs</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->
    @yield('content')

    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }}.</strong><i> All rights reserved.</i>
    </footer>

</div>
<!-- ./wrapper -->
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('backend/plugins/toastr/toastr.js') }}"></script>
<!-- Tags -->
<script src="{{ asset('backend/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('backend/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!--  Plugin for Sweet Alert -->
<script src="{{ asset('backend/dist/js/sweetalert2.js')}}"></script>
<!-- nestable -->
<script src="https://cdn.rawgit.com/dbushell/Nestable/master/jquery.nestable.js"></script>
<!-- Morris.js charts -->
<script src="{{ asset('backend/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/morris.js/morris.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{asset('backend/bower_components/Chart.js/Chart.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('backend/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('backend/dist/js/demo.js') }}"></script>
<!-- Fancy box -->
<script src="{{ asset('backend/dist/js/jquery.fancybox.min.js') }}"></script>
<!-- ck editor -->
<script src="{{ asset('backend/bower_components/ckeditor/ckeditor.js') }}"></script>
@stack('scripts')
<script type="text/javascript">
    toastr.options.timeOut = "4000";
    toastr.options.closeButton = true;
    toastr.options.positionClass = 'toast-top-right';

</script>
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
        toastr['error']('{{ $error }}', '');
    </script>

        {{-- <div data-notify="container"
             class="col-11 col-md-4 alert alert-danger alert-with-icon animated fadeInDown cart-alert-message vivify "
             role="alert" data-notify-position="bottom-right"
             style="display: inline-block; margin: 15px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1031; bottom: <?= $key * 70; ?>px; right: 20px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
            <i data-notify="icon" class="fa fa-bell"></i>
            <span data-notify="title"></span>
            <span data-notify="message">
                    Sorry!! <br> {{ $error }}
                </span>
            <a href="#" target="_blank" data-notify="url"></a>
        </div> --}}
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
    $(document).ready(function () {
        $('.sidebar-menu').tree()
    });

    //color picker with addon
    $('.variation-color-picker').colorpicker({
            useAlpha: false,
            format: "hex"
        });

    $("#startDate").datepicker({
        startDate:"today",
        autoclose:true,
        format:"yyyy-mm-dd"
    });

    // For coupon only
    // if ($("#couponId").val() != '') {
    //     $("#endDate").datepicker({
    //         startDate: $("#startDate").val(),
    //         autoclose:true,
    //         format:"yyyy-mm-dd"
    //     });        
    // }
    
    // function setStartDate(startDate){
    //     $("#endDate").val('');
    //     $("#endDate").datepicker('destroy').datepicker({
    //         startDate: startDate,
    //         autoclose:true,
    //         format:"yyyy-mm-dd"
    //     });
    // }

    // $("#orderStartDate").datepicker({
    //     endDate:"today",
    //     autoclose:true,
    //     format:"yyyy-mm-dd"
    // });

    // function setOrderStartDate(startDate){
    //     $("#orderEndDate").val('');
    //     $("#orderEndDate").datepicker('destroy').datepicker({
    //         startDate: startDate,
    //         autoclose:true,
    //         format:"yyyy-mm-dd",
    //         endDate:"today"
    //     });
    // }


    function isNumberKey(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function isDecimalNumber(evt, element){ 

        var charCode = (evt.which) ? evt.which : event.keyCode 

        if  ((charCode != 46 || ($(element).val().match(/\./g) || []).length > 0) && (charCode < 48 || charCode > 57))
            return false; 
        return true; 
    }

</script>
@yield('chart-codes')
</body>
</html>
