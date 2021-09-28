@extends('backend.layouts.headerfooter')
@section ('title', 'Colors')
@section('content')
    
    <!-- Color Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Color Header (Page header) -->
        <section class="content-header">
            <h1>
                Colors
                <small>
                    List | Add | Update | Delete Colors
                </small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.colors.index') }}"><i class="fa fa-rebel"></i> Colors</a></li>
                    <li class="active">{{ request()->routeIs('admin.colors.edit') ? 'Update Color' : 'Add New Color' }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
            <div class="row">
                <div class="col-md-12" id="addColor">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add || Edit Color's Details</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ request()->routeIs('admin.colors.edit') ? route('admin.colors.update',$color) : route('admin.colors.store') }}" enctype="multipart/form-data">
                    
                            @csrf

                            @if(request()->routeIs('admin.colors.edit'))
                                @method('PUT')
                            @endif
                            
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">

                                            <span class="input-group-addon">
                                                <i class="fa fa-text-width"></i>  Title
                                            </span>
                                            <input type="text" class="form-control" value="{{ request()->routeIs('admin.colors.edit') ? $color->title : (old('title') ? old('title') : '') }}" name="title" placeholder="Enter Title Here.." required />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group mb-1 color-picker">
                                            <input type="text" name="code" value="{{ request()->routeIs('admin.colors.edit') ? $color->code : '#000000' }}" class="form-control" placeholder="Choose Color" required/>
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?php $display =  request()->routeIs('admin.colors.edit') ? $color->display : (old('display') ? old('display') : 1)  ?>
                                                <input name="display" <?php echo($display == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                            </span>
                                            <input type="text" value="Display" class="form-control" readonly="readonly">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="box-footer">

                                <?php if (request()->routeIs('admin.colors.edit')) { ?>
                                    <a href="{{ route('admin.colors.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submitEdit" class="btn btn-primary pull-right">UPDATE COLOR
                                    </button>
                                <?php } else { ?>
                                    <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submit" class="btn btn-success pull-right">SAVE COLOR
                                    </button>
                                <?php } ?>
                            </div>
                            
                        </form>
                        <!-- form ends -->
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>
    

@endsection

@push('scripts')
    
    <script>
        $('.color-picker').colorpicker({
            useAlpha: false,
            format: "hex"
        });
    </script>
@endpush