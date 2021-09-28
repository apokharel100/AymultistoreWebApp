<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('permission:color-list|color-create|color-edit|color-delete', ['only' => ['index','show']]);
        // $this->middleware('permission:color-create', ['only' => ['create','store']]);
        // $this->middleware('permission:color-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:color-delete', ['only' => ['destroy']]);
        
    }

    public function index()
    {
        
        $colors = Color::all();
        return view('backend.colors.list-colors',compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.colors.create-update-colors');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreColorRequest $request)
    {
        $validateData = $request->validate([
            "title" => 'required|max:255',
            "code" => 'required'
        ]);

        
        $insertArray = array("title" => $request->title, 
                             "code" => $request->code,
                             "display" => isset($request->display) ? $request->display : 0,
                             "created_by" => Auth::user()->name
                            );

        $color_created = Color::create($insertArray);

        if ($color_created) {

            return redirect()->route('admin.colors.index')->with('status','New Color has been Added Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $color = Color::find(base64_decode($id));
        return view('backend.colors.create-update-colors',compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateColorRequest $request, Color $color)
    {

        $updateArray = array(
                                "title" => $request->title, 
                                "code" => $request->code,
                                "display" => isset($request->display) ? $request->display : 0,
                                "updated_by" => Auth::user()->name,
                                "updated_at" => date('Y-m-d h:i:s')
                            );

        $color_updated = $color->update($updateArray);

        if ($color_updated) {

            return redirect()->route('admin.colors.index')->with('status','Color Details has been Updated Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        $color = Color::where('id' , $id)->firstOrFail();
        
        if ($color) {

            if ($color->delete()) {

                return redirect()->back()->with('status', 'Color Deleted Successfully!');
            }
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }

}
