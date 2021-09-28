@extends('backend.layouts.headerfooter')
@section('title', 'Roles & Permission Management')
@push('post-css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/DataTables/datatables.min.css') }}">
    <style>
        .wrapper .page-wrap .main-content .page-header .page-header-title i {
            width: 50px !important;
            height: 50px !important;
        }
    </style>
@endpush
@section('content')
    <!-- Roles & Permissions Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Roles & Permissions Header (Page header) -->

        <section class="content-header">
            <h1>
                Roles & Permissions
                <small>
                    List | Add | Update | Delete Roles & Permissionss
                </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-users"></i> Roles & Permissions</a></li>
                <li class="active"><i class="fa fa-rebel"></i> Create New Role</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div id="listRolePermission">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">View All Roles & Permissionss |  
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary" title="Add New Roles & Permissions">
                                <i class="fa fa-plus"></i> Add Roles & Permissions
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <form class="forms-sample" method="post" action="{{ route('admin.roles.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-addon">
                                            <i class="fa fa-text-width"></i>  Title
                                        </div>
                                        <input type="text" class="form-control" name="name" placeholder="Role Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-md-12">
                                    <ul class="list-group ">
                                        <div class="row">
                                            @foreach($permissions as $key => $value)
                                                
                                                @php
                                                    $parts = explode('-', $value->name);
                                                    $parts = implode(' ', $parts);
                                                @endphp

                                                <div class="col-md-3 mb-2">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <input class="form-check-input permission" type="checkbox" name="permission[]" value="{{ $value->id }}">
                                                        </div>
                                                        <input type="text" disabled value="{{ ucwords($parts) }}" class="form-control">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-danger mr-2">Cancel</a>
                            <button style="float: right" type="submit" class="btn btn-success mr-2">Submit</button>
                        </div>
                    </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/DataTables/datatables.min.js') }}"></script>
    <!--  datatable script-->
    <script>
        (function ($) {
            'use strict';
            $(document).ready(function () {
                var dTable = $('#myAdvancedTable').DataTable({

                    order: [],
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    responsive: false,
                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Permissions',
                            header: false,
                            footer: true,
                            exportOptions: {
                                // columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Permissions',
                            header: false,
                            footer: true,
                            exportOptions: {
                                // columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-warning',
                            title: 'Permissions',
                            header: false,
                            footer: true,
                            exportOptions: {
                                // columns: ':visible',
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Permissions',
                            pageSize: 'A2',
                            header: false,
                            footer: true,
                            exportOptions: {
                                // columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-default',
                            title: 'Permissions',
                            // orientation:'landscape',
                            pageSize: 'A2',
                            header: true,
                            footer: false,
                            orientation: 'landscape',
                            exportOptions: {
                                // columns: ':visible',
                                stripHtml: false
                            }
                        }
                    ]
                });

            });

        })(jQuery);
    </script>
@endpush
