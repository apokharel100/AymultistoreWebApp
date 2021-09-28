<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return view('backend.blogs.list-blogs',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blogs.create-update-blogs');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $slug = ModelHelper::createSlug('\App\Models\Blog', $request->title);

        $insertArray = array(
                             "title" => $request->title, 
                             "slug" => $slug,
                             "author" => $request->author,
                             "display" => isset($request->display) ? $request->display : 0,
                             "featured" => isset($request->featured) ? $request->featured : 0,
                             "short_description" => $request->short_description,
                             "long_description" => $request->long_description,
                             "created_by" => Auth::user()->name
                            );
        // dd($insertArray);
        $path = public_path().'/storage/blogs/'.$slug;
        $folderPath = 'public/blogs/'.$slug;

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath,0777,true,true);

            if (!is_dir($path."/thumbs")) {
                Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
            }

        }

        if ($request->hasFile('image')) {
                    //Add the new photo
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 800, $image, $folderPath."/".$filename);
            ModelHelper::resize_crop_images(600, 400, $image, $folderPath."/thumbs/thumb_".$filename);

            $insertArray['image'] = $filename;

        }

        if ($request->hasFile('other_images')) {
                    //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
                // Storage::putFileAs($folderPath, new File($other), $filename_o);

                ModelHelper::resize_crop_images(1200, 800, $other, $folderPath."/".$filename_o);
                ModelHelper::resize_crop_images(600, 400, $other, $folderPath."/thumbs/thumb_".$filename_o);
            }

        }

        $blog_created = Blog::create($insertArray);

        if ($blog_created) {

            return redirect()->route('admin.blogs.index')->with('status','New Blog has been Added Successfully!');

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
        $blog = Blog::findOrFail(base64_decode($id));
        return view('backend.blogs.create-update-blogs',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        // dd($request);
        $slug = ModelHelper::createSlug('\App\Models\Blog', $request->title, $blog->id);
        $path = public_path().'/storage/blogs/'.$blog->slug;

        if ($blog->slug != $slug) {

            if (file_exists($path)) {
                Storage::move('public/blogs/'. $blog->slug , 'public/blogs/'.$slug);
            }

            $slug = ModelHelper::createSlug('\App\Models\Blog', $slug, $blog->id);
            
        }

        $updateArray = array(
                             "title" => $request->title, 
                             "slug" => $slug,
                             "author" => $request->author,
                             "display" => isset($request->display) ? $request->display : 0,
                             "featured" => isset($request->featured) ? $request->featured : 0,
                             "short_description" => $request->short_description,
                             "long_description" => $request->long_description,
                             "updated_by" => Auth::user()->name,
                             "updated_at" => date('Y-m-d H:i:s')
                            );

        $path = public_path().'/storage/blogs/'.$slug;
        $folderPath = 'public/blogs/'.$slug;

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath,0777,true,true);

            if (!is_dir($path."/thumbs")) {
                Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
            }

        }

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 800, $image, $folderPath."/".$filename);
            ModelHelper::resize_crop_images(600, 400, $image, $folderPath."/thumbs/thumb_".$filename);

            $updateArray['image'] = $filename;

            $OldFilename = $blog->image;
            // dd($OldFilename);
            //Delete the old photo
            Storage::delete($folderPath ."/".$OldFilename);
            Storage::delete($folderPath ."/thumbs/small_".$OldFilename);
            Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);

        }

        if ($request->hasFile('other_images')) {
            //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
                // Storage::putFileAs($folderPath, new File($other), $filename_o);

                ModelHelper::resize_crop_images(1200, 800, $other, $folderPath."/".$filename_o);
                ModelHelper::resize_crop_images(600, 400, $other, $folderPath."/thumbs/thumb_".$filename_o);
            }

        }

        $blog_updated = $blog->update($updateArray);

        if ($blog_updated) {

            return redirect()->route('admin.blogs.index')->with('status','Blog has been Updated Successfully!');

        }else{
            return redirect()->back()->with('error', 'Something Went Wrong!');
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
        $blog = Blog::where('id' , base64_decode($id))->firstOrFail();
        // dd($blog);
        if ($blog) {

            if ($blog->delete()) {

                $blogFolder = 'public/blogs/'.$blog->slug;
                Storage::deleteDirectory($blogFolder);

                return redirect()->back()->with('status', 'Blog Deleted Successfully!');
            }else{
                return redirect()->back()->with('status', 'Something Went Wrong!');
            }
        }else{

            return redirect()->back()->with('status', 'Blog Not Found!');
        }
    }

    public function delete_gallery_image(Request $request){

        $slug = $request->slug;
        $image = $request->image;

        Storage::delete("public/blogs/".$slug."/".$image);
        Storage::delete("public/blogs/".$slug."/thumbs/thumb_".$image);

        $response = array('message' => "success");
        echo json_encode($response);
    }
}
