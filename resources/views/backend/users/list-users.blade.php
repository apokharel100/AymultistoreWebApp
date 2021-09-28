@extends('backend.layouts.headerfooter')
@section ('title', 'Users')
@section('content')
    
    <!-- User Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- User Header (Page header) -->
        <section class="content-header">
            <h1>
                Users
                <small>
                    List | Add | Update | Delete Admin Users
                </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active"><i class="fa fa-user-secret"></i> Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div id="listUser">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">View All Users |  
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary" title="Add New User">
                                <i class="fa fa-plus"></i> Add User
                            </a>
                        </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $key+1 }}.</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if($user->getRoleNames())
                                                @foreach($user->getRoleNames() as $v)
                                                    <span class="badge badge-success">{{ $v }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td width="20%">
                                            
                                                
                                                @if(Auth::user()->id != $user->id)
                                                <a href="#delete"
                                                   data-toggle="modal"
                                                   data-id="{{ $user->id }}"
                                                   id="delete{{ $user->id }}"
                                                   class="btn btn-sm btn-danger edit"
                                                   onclick="delete_menu({{ $user->id }})"> Delete
                                                </a>
                                                @endif

                                                <a href="{{ route('admin.users.edit', base64_encode($user->id)) }}"
                                                   class="btn btn-sm btn-primary edit">Edit</a>

                                                <a href="#viewUser"
                                                   data-toggle="modal"
                                                   data-id="{{ $user->id }}"
                                                   data-email="{{ $user->email }}"
                                                   data-gender="{{ $user->gender }}"
                                                   data-phone="{{ $user->phone }}"
                                                   data-address="{{ $user->address }}"
                                                   data-city="{{ $user->city }}"
                                                   data-region="{{ $user->region }}"
                                                   data-country="{{ $user->country }}"
                                                   id="view{{ $user->id }}"
                                                   class="btn btn-sm btn-success delete view{{ $user->id}} "
                                                   onclick="view_user('{{ $user->id }}','{{ addslashes($user->name) }}','{{ $user->status }}')">
                                                    Quick View
                                                </a>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <b><?= isset($users) ? count($users) : '' ?></b> Users listed.
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!-- /.box -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>

        function view_user(id, name, status) {
            var email = $('#view' + id).attr('data-email');
            var gender = $('#view' + id).attr('data-gender');
            var phone = $('#view' + id).attr('data-phone');
            var address = $('#view' + id).attr('data-address');
            var city = $('#view' + id).attr('data-city');
            var region = $('#view' + id).attr('data-region');
            var country = $('#view' + id).attr('data-country');
            $('#viewName').html(name);
            $('#viewEmail').html(email);
            $('#viewGender').html(gender);
            $('#viewPhone').html(phone);
            $('#viewAddress').html(address);
            $('#viewCity').html(city);
            $('#viewRegion').html(region);
            $('#viewCountry').html(country);

            if (status == 0) {
                $('#viewStatus').html('<label class="label label-danger">Inactive</label>');
            }else{
                $('#viewStatus').html('<label class="label label-success">Active</label>');
            }
        }

        /*Delete Menu*/
        function delete_menu(id) {
            var conn = './users/delete/' + id;
            $('#delete a').attr("href", conn);
        }
        
    </script>
    <!--    Initialize Multi Select   -->

    <!-- Modal view -->
    <div class="modal fade" id="viewUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #449D44">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center">View User</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong >Name : <span id="viewName"></span></strong>
                        </div><br><br>
                        <div class="col-md-6">
                            <p>Email : <span style="font-weight: 800;" id="viewEmail"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Gender : <span style="font-weight: 800;" id="viewGender"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Contact Number : <span style="font-weight: 800;" id="viewPhone"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Address : <span style="font-weight: 800;" id="viewAddress"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>City : <span style="font-weight: 800;" id="viewCity"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Region : <span style="font-weight: 800;" id="viewRegion"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Country : <span style="font-weight: 800;" id="viewCountry"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Status : <span style="font-weight: 800;" id="viewStatus"></span></p>
                        </div>
                    </div>
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
    <!-- //Modal View Ends -->

    <!-- DELETE MODAL STARTS -->
    <div class="modal fade modal-danger" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete User</h4>
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

    <!-- Modal view -->
    <div class="modal fade" id="changePassword">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #F39C12">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Password </h4>
                </div>
                <form method="POST" action="{{ route('admin.users.update-password') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info"></i> New Password</span>
                                    <input type="password" class="form-control" name="password" placeholder="New Password" required/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info"></i> Retype Password</span>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Retype Password" required/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info"></i> Old Password</span>
                                    <input type="password" class="form-control" name="oldpassword"
                                           placeholder="Old Password" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #F39C12">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" id="updateSend" class="btn btn-outline" name="updatePassword"><i
                                class="fa fa-plus"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-users -->
            <!-- /.modal-users -->
        </div>
        <!-- /.modal-dialog -->

    </div>



    <!-- alert message dismiss -->
    <script type="text/javascript">

        function editPassword(id) {
            $('#editId').val(id);
        }
    </script>
@endsection