<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\SiteSetting;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateSettingRequest;
use App\Services\ModelHelper;


class SiteSettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = SiteSetting::where('id' ,1)->first();
        return view('backend.site-setting.update-setting',compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettingRequest $request, SiteSetting $setting)
    {
        // dd($_POST);

        $validatedData = $request->validate([
            'sitetitle' => 'required|max:255',
            'siteemail' => 'required|max:225|email',
        ]);

        $setting->sitetitle = $request['sitetitle'];
        $setting->siteemail = $request['siteemail'];
        $setting->phone = $request['phone'];
        $setting->mobile = $request['mobile'];
        $setting->fax = $request['fax'];
        $setting->address = $request['address'];
        $setting->facebookurl = $request['facebookurl'];
        $setting->twitterurl = $request['twitterurl'];
        $setting->instagramurl = $request['instagramurl'];
        $setting->youtubeurl = $request['youtubeurl'];
        $setting->short_content = $request['short_content'];
        $setting->delivery_charge = $request['delivery_charge'];
        
        $setting->og_title = $request['og_title'];
        $setting->og_description = $request['og_description'];
        $setting->meta_title = $request['meta_title'];
        $setting->meta_description = $request['meta_description'];
        $setting->meta_keywords = $request['meta_keywords'];

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time().'.'.$logo->getClientOriginalExtension();
            $oldlogo = $setting->logo;
            $validatedData = $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            Storage::putFileAs('public/setting/logo', new File($logo), $filename);

            $setting->logo = $filename;
            
            ModelHelper::resize_crop_images(200, 200, $logo, "public/setting/logo/thumb_" . $filename);

            //deleting exiting logo
            Storage::delete('public/setting/logo/'.$oldlogo);
            Storage::delete('public/setting/logo/thumb_'.$oldlogo);
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $filename = time().'.'.$favicon->getClientOriginalExtension();
            $oldfavicon = $setting->favicon;
            $validatedData = $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            Storage::putFileAs('public/setting/favicon', new File($favicon), $filename);
            $setting->favicon = $filename;

            ModelHelper::resize_crop_images(200, 200, $favicon, "public/setting/favicon/thumb_" . $filename);

            //deleting exiting logo
            Storage::delete('public/setting/favicon/'.$oldfavicon);
            Storage::delete('public/setting/favicon/thumb_'.$oldfavicon);
        }
        
        if ($request->hasFile('og_image')) {
            $logo = $request->file('og_image');
            $filename = time() . '.' . $logo->getClientOriginalExtension();
            $oldog_image = $setting->og_image;

            $validatedData = $request->validate([
                'og_image' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            Storage::putFileAs('public/setting/og_image', new File($logo), $filename);

            $setting->og_image = $filename;
            
            ModelHelper::resize_crop_images(1200, 1200, $logo, "public/setting/og_image/og_" . $filename);


            if ($oldog_image != null) {
                //deleting exiting logo
                Storage::delete('public/setting/og_image/' . $oldog_image);
                Storage::delete('public/setting/og_image/og_' . $oldog_image);
            }
        }

        $setting->save();


        return redirect()->route('admin.setting')->with('status','Settings Updated Successfully.');
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
}
