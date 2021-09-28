<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('permission:size-list|size-create|size-edit|size-delete', ['only' => ['index','show']]);
        // $this->middleware('permission:size-create', ['only' => ['create','store']]);
        // $this->middleware('permission:size-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:size-delete', ['only' => ['destroy']]);
        
    }

    public function index()
    {
        
        $sizes = Size::all();
        return view('backend.sizes.list-sizes',compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sizes.create-update-sizes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSizeRequest $request)
    {
        // dd($request->all());
        $slug = ModelHelper::createSlug('\App\Models\Size', $request->title);

        $insertArray = array("title" => $request->title, 
                             "display" => isset($request->display) ? $request->display : 0,
                             "created_by" => Auth::user()->name
                            );

        $size_created = Size::updateOrCreate(
                                                ['slug' => $slug], 
                                                ["title" => $request->title, 
                                                 "display" => isset($request->display) ? $request->display : 0,
                                                 "created_by" => Auth::user()->name
                                                ]
                                            );

        if ($size_created) {

            return redirect()->route('admin.sizes.index')->with('status','New Size has been Added Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $size = Size::find(base64_decode($id));
        return view('backend.sizes.create-update-sizes',compact('size'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update_size(UpdateSizeRequest $request)
    {
        // dd($request->all());
        $size = Size::findOrFail($request->id);
        $slug = ModelHelper::createSlug('\App\Models\Size', $request->title, $request->id);
        $updateArray = array(
                                "title" => $request->title, 
                                "slug" => $slug,
                                "display" => isset($request->display) ? $request->display : 0,
                                "updated_by" => Auth::user()->name,
                                "updated_at" => date('Y-m-d h:i:s')
                            );

        $size_updated = $size->update($updateArray);

        if ($size_updated) {

            return redirect()->route('admin.sizes.index')->with('status','Size Details has been Updated Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        $size = Size::where('id' , $id)->firstOrFail();
        
        if ($size) {

            if ($size->delete()) {

                return redirect()->back()->with('status', 'Size Deleted Successfully!');
            }
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }

}
