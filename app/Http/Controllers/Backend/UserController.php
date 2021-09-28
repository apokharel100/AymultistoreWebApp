<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::role('super-admin')->where('id','!=',1)->get();
        $roles = Role::pluck('name', 'name')->all();
        $id = 0;
        return view('backend.users.list-users', compact('users', 'id', 'roles'));

        // $users = DB::table('users')->where('role',2]])->get();
        // return view('admin.users', array('users' => $users, 'id' => '0', 'branchId' => $branchId));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $id = 0;
        return view('backend.users.create-update-users', compact('id', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        // dd($_POST);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'city' => $request['city'],
            'region' => $request['region'],
            'country' => $request['country'],
            'status' => isset($request['status']) ? 1 : 0,
            'password' => Hash::make($request['password'])
        ]);
        $user->assignRole($request->input('role'));
        return redirect()->route('admin.users.index')->with('status', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $user = User::findOrFail($id);
        $userRole = $user->roles->pluck('id', 'id')->all();
        $roles = Role::all();
        return view('backend.users.create-update-users', compact('user', 'userRole', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'city' => $request['city'],
            'region' => $request['region'],
            'country' => $request['country'],
            'status' => isset($request['status']) ? 1 : 0]);

        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        $user->assignRole($request->input('role'));

        return redirect()->route('admin.users.index')->with('status', 'User Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_password(Request $request)
    {
        // dd($_POST);
        $user = User::find($request->id);

        if (Hash::check($request->oldpassword, $user->password)) {

            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            $user->password = Hash::make($request['password']);
            $user->save();
            return redirect()->back()->with('status', 'Password Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Your Current Password Doesnot Match!!');
        }
    }

    public function get_states($cName, Request $request)
    {

        if ($cName != 'null') {

            $countryArray = array();
            $region= $request->region;
            $country = DB::table('countries')->where('name', $cName)->select('id','phonecode')->first();

            $postal_code = $country->phonecode;
            $states = DB::table('states')->where('country_id', $country->id)->get();

            array_push($countryArray, "<option value='' disabled selected>Select State/Region</option>");
            foreach ($states as $stat) {
                if ($stat->name == $region) {
                    $selectFlag = 'selected';
                }else{
                    $selectFlag = '';
                }
                array_push($countryArray, "<option ".$selectFlag." value='".$stat->name."' >".$stat->name."</option>");
            }
            $countryArray = json_encode($countryArray);
            $cArray = array("country_list" => $countryArray, "postal_code" =>  $postal_code);

            echo json_encode($cArray);
        }
    }
}
