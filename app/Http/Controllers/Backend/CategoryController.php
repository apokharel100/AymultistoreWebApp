<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ModelHelper::getFullListFromDB('categories');
        // dd($categories);
        return view('backend.categories.list-categories',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_categories = Category::orderBy('order_item')->get();
        $products = Product::all();
        return view('backend.categories.create-update-categories',compact('parent_categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        // dd($request);

        $slug = ModelHelper::createSlug('\App\Models\Category', $request->title);
        
        $max_order = Category::max('order_item');
        $insertArray = array("title" => $request->title, 
                             "slug" => $slug,
                             "display" => isset($request->display) ? $request->display : 0,
                             "popular" => isset($request->popular) ? $request->popular : 0,
                             "order_item" => $max_order + 1,
                             "parent_id" => $request->parent_id,
                             "created_by" => Auth::user()->name
                            );

        $path = public_path() . '/storage/categories/';
        $folderPath = 'public/categories/';

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
            $folderPath = "public/categories/";
            $thumbPath = "public/categories/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 1200, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(900, 900, $image, $folderPath . "/thumbs/medium_" . $filename);
            ModelHelper::resize_crop_images(200, 200, $image, $folderPath . "/thumbs/small_" . $filename);

            //Update the database
            $insertArray['image'] = $filename;

        }

        $category_created = Category::create($insertArray);

        if ($category_created) {
            
            ModelHelper::update_child_status('\App\Models\Category', $request->parent_id);

            if (isset($request->product_id)) {

                $product_ids = $request->product_id;


                for ($i=0; $i < count($product_ids); $i++) { 
                    
                    $category_created->category_products()->updateOrCreate(['category_id' => $category_created->id, 'product_id' => $product_ids[$i]]);
                }
            }

            return redirect()->route('admin.categories.index')->with('status','New Category has been Added Successfully!');

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
        $category = Category::find(base64_decode($id));
        // $category_products = $category->category_products()->pluck('product_id')->all();
        // dd(in_array(1, $category_products));
        $parent_categories = Category::where('id', '!=', $category->id)->orderBy('order_item')->get();
        $products = Product::all();
        return view('backend.categories.create-update-categories',compact('category','parent_categories','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $slug = ModelHelper::createSlug('\App\Models\Category', $request->title, $category->id);
        // dd($_POST);

        $updateArray = array(
                                "title" => $request->title, 
                                "slug" => $slug,
                                "display" => isset($request->display) ? $request->display : 0,
                                "popular" => isset($request->popular) ? $request->popular : 0,
                                "parent_id" => $request->parent_id,
                                "updated_by" => Auth::user()->name,
                                "updated_at" => date('Y-m-d h:i:s')
                            );

        $path = public_path() . '/storage/categories/';
        $folderPath = 'public/categories/';

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
            $folderPath = "public/categories/";
            $thumbPath = "public/categories/thumbs";

            if (!file_exists($thumbPath)) {
                
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            // Storage::putFileAs($folderPath, new File($image), $filename);

            ModelHelper::resize_crop_images(1200, 1200, $image, $folderPath . "/" . $filename);
            ModelHelper::resize_crop_images(900, 900, $image, $folderPath . "/thumbs/medium_" . $filename);
            ModelHelper::resize_crop_images(200, 200, $image, $folderPath . "/thumbs/small_" . $filename);

            $OldFilename = $category->image;
            //Update the database
            $updateArray['image'] = $filename;

            Storage::delete($folderPath ."/".$OldFilename);
            Storage::delete($folderPath ."/thumbs/medium_".$OldFilename);
            Storage::delete($folderPath ."/thumbs/small_".$OldFilename);

        }

        $old_parent_id = $category->parent_id;
        $new_parent_id = $request->parent_id;
        // ModelHelper::update_child_status('\App\Models\Category', $new_parent_id, $old_parent_id);
        // dd($old_parent_id."--new---".$new_parent_id);

        $category_updated = $category->update($updateArray);

        if ($category_updated) {
                    
            ModelHelper::update_child_status('\App\Models\Category', $new_parent_id, $old_parent_id);

            if (isset($request->product_id)) {

                $product_ids = $request->product_id;
                // dd($product_ids);

                for ($i=0; $i < count($product_ids); $i++) { 
                    
                    $category->category_products()->updateOrCreate(['category_id' => $category->id, 'product_id' => $product_ids[$i]]);
                }

                $category->category_products()->whereNotIn('product_id',$product_ids)->delete();
                
            }else{
                $category->category_products()->delete();
            }
            
            // dd('test');
            return redirect()->route('admin.categories.index')->with('status','Category Details has been Updated Successfully!');

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
        $category = Category::where('id' , $id)->firstOrFail();

        

        if ($category) {

            if ($category->child == 1) {
                return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Category has Child!', 'secondary' => 'Currently, It cannot be deleted.'));
            }

            $parentId = $category->parent_id;
            $oldImage = $category->image;
            
            // dd(count($category->products));
            // if (count($category->products) > 0) {

            //     return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Category has Products!', 'secondary' => 'Currently, It cannot be deleted.'));
            //     exit();
            // }

            // exit();
            if ($category->delete()) {

                $folderPath = "public/categories";

                Storage::delete($folderPath ."/".$oldImage);
                Storage::delete($folderPath ."/thumbs/small_".$oldImage);
                Storage::delete($folderPath ."/thumbs/medium_".$oldImage);

                $childCheck = Category::where('parent_id' , $parentId)->doesntExist();

                if ($childCheck) {
                    Category::where('id', $parentId)->update(["child" => 0]);
                }
            }

            return redirect()->back()->with('status', 'Category Deleted Successfully!');
        }
        return redirect()->back()->with('error', 'Something Went Wrong!');
    }

    public static function set_order(Request $request)
    {
        $has_child = $request['has_child'];
        $model = $request['model'];
        $list_order = $request['list_order'];

        $data = ModelHelper::set_order($list_order, $model, $has_child);
            
        echo json_encode($data);
        exit;
    }
}
