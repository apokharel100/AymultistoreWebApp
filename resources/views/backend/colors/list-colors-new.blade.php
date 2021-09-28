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
                <li class="active"><i class="fa fa-rebel"></i> Colors</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div id="listColor">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">View All Colors |  
                            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary" title="Add New Color">
                                <i class="fa fa-plus"></i> Add Color
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <form method="POST" action="{{ route('admin.colors.bulk-update') }}">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="4%">SN.</th>
                                            <th width="30%">Color Name</th>
                                            <th width="30%">Color Code</th>
                                            <th class="text-center" width="">Display</th>
                                            <th class="text-center" width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($colors as $key => $color)
                                        <input type="hidden" name="color[{{$key}}][id]" value="{{ $color->id }}">
                                        <tr>
                                            <td class="text-center">{{ $key+1 }}</td>
                                            <td>
                                                <input class="form-control color-table" type="text" name="color[{{$key}}][title]" value="{{ $color->title }}" disabled="">
                                                {{-- <b>{{ $color->title }}</b>&nbsp;|&nbsp; 
                                                <small>
                                                    <i>
                                                        @if($color->display == 1)
                                                        
                                                            <i style="color: green;" class="fa fa-eye"></i>
                                                        
                                                        @else
                                                        
                                                            <i style="color: red;" class="fa fa-eye-slash"></i>
                                                        @endif

                                                    </i>
                                                </small> --}}

                                            </td>
                                            <td>
                                                <div class="input-group mb-1 color-picker">
                                                    <input type="text" name="color[{{$key}}][code]" value="{{ $color->code }}" class="form-control" placeholder="Choose Color" required/>
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>

                                                {{-- {{ $color->code }}
                                                <div style="display: inline-block; height: 20px; width: 20px; background-color: {{ $color->code  }}"></div> --}}
                                            </td>
                                            
                                            <td class="text-center">
                                                <input type="checkbox" name="color[{{$key}}][display]" value="1" {{ $color->display == 1 ? 'checked' : '' }}>
                                                {{-- @if($color->display == 1)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-default">InActive</span>
                                                @endif --}}
                                            </td>
                                            
                                            <td class="text-center">
                                                <span class="content-right">
                                                    <a href="#viewColor"
                                                        data-toggle="modal"
                                                        data-id="{{ $color->id }} "
                                                        data-details='{{ $color->content }}'
                                                        data-display="{{ $color->display }}"
                                                        id="view{{ $color->id }}"
                                                        class="btn btn-sm btn-success"
                                                        onClick="view_content('{{$color->id }} ','{{addslashes($color->title) }} ','{{$color->image }}')"><i class="fa fa-eye"></i></a>

                                                    <a href="{{ route('admin.colors.edit', base64_encode($color->id)) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                                    
                                                    
                                                    <a href="#delete"
                                                       data-toggle="modal"
                                                       data-id="{{ $color->id }}"
                                                       id="delete{{ $color->id }}"
                                                       title="Delete" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="delete_menu('{{ base64_encode($color->id) }}')"><i class="fa fa-trash  "></i>
                                                   </a>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <button id="addMoreColor" type="button" class="btn btn-warning"> <i class="fa fa-plus"></i> Add More Color</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save All</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
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

            if (display == 0) {
                $('#viewDisplay').html('<small class="label label-danger">Not Displayed</small>');
            }else{
                $('#viewDisplay').html('<small class="label label-success">Displayed</small>');
            }

            $('#viewId').val(id);
            $('#viewTitle').html(title);
            if (image != '') {
                $('#viewImage').show();
                $('#viewImage').attr('src', "{{ asset('storage/colors/thumbs/thumb_') }}" + image);
            }else{
                $('#viewImage').hide();
            }
            $('#viewContents').html(content);
        }  


        /*Delete Menu*/
        function delete_menu(id) {
            var conn = './colors/delete/' + id;
            $('#delete a').attr("href", conn);
        }
        /*Delete Menu Ends*/
        
    </script>
    <!--    Initialize Multi Select   -->

    <div class="modal fade" id="viewColor">
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
                    </div>
                    <br>
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
                    <h4 class="modal-title">Delete Color</h4>
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

@push('scripts')
    
    <script>
        $('#addMoreColor').click(function(){
            var color_count = $('.color-picker').length;
            color_count++;
        });

        $('.color-picker').colorpicker({
            useAlpha: false,
            format: "hex"
        });
    </script>
@endpush