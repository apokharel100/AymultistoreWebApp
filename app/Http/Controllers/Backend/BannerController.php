<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        return view('backend.banners.list-banners',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banners.create-update-banners');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBannerRequest $request)
    {

        $insertArray = array("title" => $request->title, 
                             "link" => $request->link,
                             "display" => isset($request->display) ? $request->display : 0,
                             "created_by" => Auth::user()->name
                            );

        $path = public_path() . '/storage/banners/';
        $folderPath = 'public/banners/';

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
            $folderPath = "public/banners/";
            $thumbPath = "public/banners/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1920, 800, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(720, 300, $image, $folderPath . "/thumbs/thumb_" . $filename);

            //Update the database
            $insertArray['image'] = $filename;

        }

        $banner_created = Banner::create($insertArray);

        if ($banner_created) {

            return redirect()->route('admin.banners.index')->with('status','New Banner has been Added Successfully!');

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
        $banner = Banner::find(base64_decode($id));

        return view('backend.banners.create-update-banners',compact('banner'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $slug = ModelHelper::createSlug('\App\Models\Banner', $request->title, $banner->id);

        $updateArray = array(
                                "title" => $request->title, 
                                "link" => $request->link,
                                "display" => isset($request->display) ? $request->display : 0,
                                "updated_by" => Auth::user()->name,
                                "updated_at" => date('Y-m-d h:i:s')
                            );

        $path = public_path() . '/storage/banners/';
        $folderPath = 'public/banners/';

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
            $folderPath = "public/banners/";
            $thumbPath = "public/banners/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1920, 800, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(720, 300, $image, $folderPath . "/thumbs/thumb_" . $filename);

            $OldFilename = $banner->image;
            //Update the database
            $updateArray['image'] = $filename;

            Storage::delete($folderPath ."/".$OldFilename);
            Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);

        }


        $banner_updated = $banner->update($updateArray);

        if ($banner_updated) {
            
            return redirect()->route('admin.banners.index')->with('status','Banner Details has been Updated Successfully!');

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
        $banner = Banner::where('id' , $id)->firstOrFail();
        
        if ($banner) {
            $oldImage = $banner->image;

            if ($banner->delete()) {
                $folderPath = "public/banners";

                Storage::delete($folderPath ."/".$oldImage);
                Storage::delete($folderPath ."/thumbs/thumb_".$oldImage);

                return redirect()->back()->with('status', 'Banner Deleted Successfully!');
            }
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }
}
