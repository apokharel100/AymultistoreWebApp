<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return view('backend.brands.list-brands',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.brands.create-update-brands');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $slug = ModelHelper::createSlug('\App\Models\Brand', $request->title);

        $insertArray = array("title" => $request->title, 
                             "slug" => $slug,
                             "display" => isset($request->display) ? $request->display : 0,
                             "created_by" => Auth::user()->name
                            );

        $path = public_path() . '/storage/brands/';
        $folderPath = 'public/brands/';

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }

        if ($request->hasFile('image')) {

            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/brands/";
            $thumbPath = "public/brands/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 800, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(600, 400, $image, $folderPath . "/thumbs/medium_" . $filename);
            ModelHelper::resize_crop_images(300, 200, $image, $folderPath . "/thumbs/thumb_" . $filename);

            //Update the database
            $insertArray['image'] = $filename;

        }

        $brand_created = Brand::create($insertArray);

        if ($brand_created) {

            return redirect()->route('admin.brands.index')->with('status','New Brand has been Added Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }

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
        $brand = Brand::find(base64_decode($id));

        return view('backend.brands.create-update-brands',compact('brand'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $slug = ModelHelper::createSlug('\App\Models\Brand', $request->title, $brand->id);

        $updateArray = array(
                                "title" => $request->title, 
                                "slug" => $slug,
                                "display" => isset($request->display) ? $request->display : 0,
                                "updated_by" => Auth::user()->name,
                                "updated_at" => date('Y-m-d h:i:s')
                            );

        $path = public_path() . '/storage/brands/';
        $folderPath = 'public/brands/';

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }

        if ($request->hasFile('image')) {

            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/brands/";
            $thumbPath = "public/brands/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 800, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(600, 400, $image, $folderPath . "/thumbs/medium_" . $filename);
            ModelHelper::resize_crop_images(300, 200, $image, $folderPath . "/thumbs/thumb_" . $filename);

            $OldFilename = $brand->image;
            //Update the database
            $updateArray['image'] = $filename;

            Storage::delete($folderPath ."/".$OldFilename);
            Storage::delete($folderPath ."/thumbs/medium_".$OldFilename);
            Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);

        }


        $brand_updated = $brand->update($updateArray);

        if ($brand_updated) {
            
            return redirect()->route('admin.brands.index')->with('status','Brand Details has been Updated Successfully!');

        }else{
            return redirect()->back()->with('error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        $brand = Brand::where('id' , $id)->firstOrFail();
        
        if ($brand) {
            $oldImage = $brand->image;

            if ($brand->delete()) {
                $folderPath = "public/brands";

                Storage::delete($folderPath ."/".$oldImage);
                Storage::delete($folderPath ."/thumbs/thumb_".$oldImage);
                Storage::delete($folderPath ."/thumbs/medium_".$oldImage);

                return redirect()->back()->with('status', 'Brand Deleted Successfully!');
            }
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }
}
