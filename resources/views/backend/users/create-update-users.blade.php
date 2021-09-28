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
            
            <div class="row">
                <div class="col-md-12" id="addUser">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add || Edit User's Details
                                @if(request()->routeIs('admin.users.edit'))
                                    @if(Auth::user()->id == $user->id)
                                    <a href="#changePassword"
                                       class="btn btn-warning"
                                       data-toggle="modal"
                                       id="chngPass{{ $user->id }}"
                                       data-id="<?= $user->id; ?>"
                                       data-user="{{ $user->name }}"
                                       onClick="editPassword('{{ $user->id }}')">
                                        <i class="fa fa-key"></i> Change Password
                                    </a>
                                    @endif
                                @endif
                                

                            </h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ request()->routeIs('admin.users.edit') ? route('admin.users.update',$user) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(request()->routeIs('admin.users.edit'))
                                @method('PUT')
                            @endif
                            <div class="box-body">

                                <input type="hidden" id="userId" name="id" value="{{ request()->routeIs('admin.users.edit') ? $user->id : '' }}"/>

                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> Full Name</span>
                                                <input type="text" class="form-control" value="{{ request()->routeIs('admin.users.edit') ? $user->name : '' }}" name="name" placeholder="eg: John Doe" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-at"></i> Email</span>
                                                <input type="email" class="form-control" value="{{ request()->routeIs('admin.users.edit') ? $user->email : '' }}" name="email" placeholder="eg: johndoe@domain.com" {{ request()->routeIs('admin.users.edit') ? 'readonly' : '' }} required/>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> Gender</span>
                                                <select class="form-control" name="gender">
                                                    <option selected disabled>Select Gender</option>
                                                    <option value="Male" {{ request()->routeIs('admin.users.edit') && $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ request()->routeIs('admin.users.edit') && $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Others" {{ request()->routeIs('admin.users.edit') && $user->gender == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> Phone</span>
                                                <input type="text" class="form-control" value="{{ request()->routeIs('admin.users.edit') ? $user->phone : '' }}" name="phone" placeholder="eg: 98490000XX" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> Address</span>
                                                <input type="text" class="form-control" value="{{ request()->routeIs('admin.users.edit') ? $user->address : '' }}" name="address" placeholder="Address" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> City</span>
                                                <input type="text" class="form-control" value="{{ request()->routeIs('admin.users.edit') ? $user->city : '' }}" name="city" placeholder="City" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> Country</span>
                                                <select id="inputCountry" class="form-control" name="country" onchange='getStates(this.value, "{{ @$user->region }}")' >
                                                    <option selected disabled>Select Country</option>
                                                    @php
                                                        $countries = DB::table('countries')->get();
                                                    @endphp
                                                    @foreach($countries as $con)
                                                        <option value="{{ $con->name }}" {{ request()->routeIs('admin.users.edit') && $user->country == $con->name ? 'selected' : '' }}>{{ $con->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-info"></i> States</span>
                                                <select id="inputState" class="form-control" name="region" >
                                                    <option disabled selected="" value="">Choose...</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <?php $status =  request()->routeIs('admin.users.edit') ? $user->status : 0  ?>
                                                    <input name="status" <?php echo($status == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                                </span>
                                                <input type="text" value="Check for Active Members" class="form-control" readonly="readonly">
                                            </div>
                                        </div> --}}

                                        <div class="col-lg-4">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-anchor"></i> Role</span>
                                                
                                                <select name="role" class="form-control">

                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ request()->routeIs('admin.users.edit') && array_key_exists($role->id, $userRole) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        @if(request()->routeIs('admin.users.create'))
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-link"></i> Password</span>
                                                    <input type="password" class="form-control" name="password" placeholder="*******" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-link"></i> Confirm Password</span>
                                                    <input type="password" class="form-control" name="password_confirmation"placeholder="*******" />
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">

                                <?php if (request()->routeIs('admin.users.edit')) { ?>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submitEdit" class="btn btn-primary pull-right">UPDATE USER
                                    </button>
                                <?php } else { ?>
                                    <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submit" class="btn btn-success pull-right">SAVE USER
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

@endsection

@push('scripts')
    
    <script type="text/javascript">

        $('#inputCountry').attr('required',false);
        $('#inputState').attr('required',false);

        if ($("#inputCountry").val() != null) {
            getStates($("#inputCountry").val(), '{{ request()->routeIs('admin.users.edit') ? $user->region : ''}}');
        }

        function getStates($cName, $regionState){
            $.ajax({
                url : "{{url('admin/users/get_states/')}}/"+$cName+"?region="+$regionState,
                cache : false,
                beforeSend : function (){
                    $('#inputState').empty();
                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        var obj = jQuery.parseJSON($response.responseText);
                        var countries = jQuery.parseJSON(obj['country_list']);

                        $("#inputZip").val(obj['postal_code']);

                        for (var i = 0; i < countries.length; i++) {
                            $('#inputState').append(countries[i]);
                        }
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            });
        }

        function editPassword(id) {
            $('#editId').val(id);
        }
    </script>
@endpush