@extends('backend.layouts.headerfooter')
@section ('title', 'Categories')
@section('content')

    <?php
        function displayList($list)
        {
            ?>
            <ol class="dd-list">
                <?php

                foreach ($list as $item):
                    ?>
                    <li class="dd-item" data-id="{{ $item->id }} ">
                        <div class="dropdown pull-right item_actions hidden-lg hidden-md hidden-sm">
                            <button class="btn btn-sm btn-success view dropdown-toggle" type="button" id="dropdownContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-chevron-down" aria-hidden="true"></i> </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownContentButton">
                                @if(!property_exists($item, "children"))
                                <a  href="#viewCategory"
                                    data-toggle="modal"
                                    data-id="{{ $item->id }} "
                                    data-details='{{ $item->content }}'
                                    data-display="{{ $item->display }}"
                                    data-popular="{{ $item->popular }}"
                                    id="view{{ $item->id }}"
                                    class="btn btn-sm btn-success view center-block"
                                    onClick="view_content('{{$item->id }} ','{{addslashes($item->title) }} ','{{$item->image }}')">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endif

                                <a href="{{ route('admin.categories.edit', base64_encode($item->id)) }}"
                                class="btn btn-sm btn-primary center-block"><i class="fa fa-edit"></i> </a>
                                
                                
                                <a href="#delete"
                                data-toggle="modal"
                                data-id="{{ $item->id }}"
                                id="delete{{ $item->id }}"
                                class="btn btn-sm btn-danger center-block"
                                onClick="delete_menu('{{ base64_encode($item->id) }}')"> <i class="fa fa-trash"></i> 
                                </a>
                                    
                                
                            </div>
                        </div>
                        <div class="pull-right item_actions hidden-xs">
                            @if(!property_exists($item, "children"))
                            <a href="#delete"
                                data-toggle="modal"
                                data-id="{{ $item->id }}"
                                id="delete{{ $item->id }}"
                                class="btn btn-sm btn-danger delete"
                                onClick="delete_menu('{{ base64_encode($item->id) }}')"> <i class="fa fa-trash"></i> 
                            </a>
                            @endif
                        
                            <a href="#viewCategory"
                                data-toggle="modal"
                                data-id="{{ $item->id }}"
                                data-details='{{ $item->content }}'
                                data-display="{{ $item->display }}"
                                data-popular="{{ $item->popular }}"
                                id="view{{ $item->id }} "
                                class="btn btn-sm btn-success view"
                                onClick="view_content('{{ $item->id }}','{{ addslashes($item->title) }}','{{ $item->image }}')">
                                <i class="fa fa-eye"></i>
                            </a>&nbsp;

                            <a href="{{ route('admin.categories.edit', base64_encode($item->id)) }}" class="btn btn-sm btn-primary edit"><i class="fa fa-edit"></i> </a>
                                
                        </div>
                        <div class="dd-handle">{{ $item->title }} &nbsp;&nbsp;&nbsp;
                            <small>
                                @if($item->display == 1)
                                
                                    <i style="color: green;" class="fa fa-eye"></i>
                                
                                @else
                                
                                    <i style="color: red;" class="fa fa-eye-slash"></i>
                                @endif

                                @if($item->popular == 1)
                                
                                    <i style="color: #a8a800;" class="fa fa-star"></i>
                                
                                @endif
                            </small>
                        </div>
                        <?php if (property_exists($item,"children")): ?>
                            <?php displayList( $item->children); ?>
                        <?php endif; ?>
                    </li>
                    <?php
                endforeach; ?>
            </ol>
            <?php
        }
    ?>    
    <!-- Category Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Category Header (Page header) -->
        <section class="content-header">
            <h1>
                Categories
                <small>
                    List | Add | Update | Delete Categories
                </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active"><i class="fa fa-anchor"></i> Categories</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div id="listCategory">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">View All Categories |  
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" title="Add New Category">
                                <i class="fa fa-plus"></i> Add Category
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="cf nestable-lists">
                            <div class="dd" id="nestable">
                               <?php isset($categories) ?  displayList($categories) : '' ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>
        function view_content(id, title, image) {
            var display = $('#view' + id).attr('data-display');
            var category = $('#view' + id).attr('data-popular');
            var content = $('#view' + id).attr('data-details');
            
            if (display == 0) {
                $('#viewDisplay').html('<small class="label label-danger">Not Displayed</small>');
            }else{
                $('#viewDisplay').html('<small class="label label-success">Displayed</small>');
            }

            if (category == 0) {
                $('#viewCat').html('<small class="label label-danger">Is Not Popular Category</small>');
            }else{
                $('#viewCat').html('<small class="label label-success">Popular Category</small>');
            }
            $('#viewId').val(id);
            $('#viewTitle').html(title);

            if (image != '') {
                $('#viewImage').show();
                $('#viewImage').attr('src', "{{ asset('storage/categories/thumbs/small_') }}" + image);
            }else{
                $('#viewImage').hide();
            }
            $('#viewContents').html(content);
        }  


        /*Delete Menu*/
        function delete_menu(id) {
            var conn = './categories/delete/' + id;
            $('#delete a').attr("href", conn);
        }
        /*Delete Menu Ends*/
        
        $(document).ready(function () {
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');

                $.ajax({
                    method: "POST",
                    url: "{{route('admin.categories.order')}}",
                    data: {
                        '_token': $('input[name=_token]').val(),
                        list_order: list.nestable('serialize'),
                        table: "categories",
                        has_child : 1,
                        model : "\\App\\Models\\Category"
                    },
                    success: function (response) {
                     
                        var obj = jQuery.parseJSON(response);
                        if (obj.status == 'success') {
                            toastr['success']('Categories Sorted!');

                        };

                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    sweetAlert('Failure', 'Something Went Wrong!', 'error');
                });
            };

            $('#nestable').nestable({
                group: 1,
                maxDepth: 4,
            }).on('change', updateOutput);
        });  
    </script>
    <!--    Initialize Multi Select   -->

    <div class="modal fade" id="viewCategory">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #449D44">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><span id="viewTitle"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <img  id="viewImage" src="" class="img-responsive center-block">
                    </div>
                    <br>
                    <div class="row">
                        <span id="viewDisplay"></span>
                        <span id="viewCat"></span>
                    </div>
                    <br>
                    {{-- <div class="row">
                        <label for="description"> &nbsp;&nbsp;&nbsp;Description: </label>
                        <p style="width:100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                        id="viewContents"></p>
                    </div> --}}
                </div>
                <div class="modal-footer" style="background: #449D44">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- DELETE MODAL STARTS -->
    <div class="modal fade modal-danger" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Category</h4>
                </div>
                <div class="modal-body">
                    <p>Are You Sure&hellip;?</p>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer">
                        <a class="btn btn-outline" href="" onclick="">Delete</a>
                        <a data-dismiss="modal" class="btn btn-outline pull-left" href="#">Cancel</a>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- DELETE MODAL ENDS -->
@endsection