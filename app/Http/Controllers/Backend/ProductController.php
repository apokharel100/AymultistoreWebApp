<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Services\ModelHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('order_item')->get();
        return view('backend.products.list-products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::where('display',1)->get();
        $categories = ModelHelper::getFullListFromDB('categories');
        return view('backend.products.create-update-products',compact('brands','categories'));
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

        $slug = ModelHelper::createSlug('\App\Models\Product', $request->title);
        $max_order = Product::max('order_item');

        $insertArray = array(
                             "title" => $request->title, 
                             "slug" => $slug,
                             "sku" => $request->sku,
                             "price" => $request->price,
                             "discounted_price" => isset($request->discounted_price) ? $request->discounted_price : 0,
                             "display" => isset($request->display) ? $request->display : 0,
                             "featured" => isset($request->featured) ? $request->featured : 0,
                             "variation_type" => $request->variation_type,
                             "short_description" => $request->short_description,
                             "long_description" => $request->long_description,
                             "stock_status" => $request->stock_status,
                             "stock_count" => $request->stock_count,
                             "brand_id" => $request->brand_id,
                             "order_item" => $max_order + 1,
                             "tags" => $request->tags,
                             "created_by" => Auth::user()->name
                            );
        // dd($insertArray);
        $path = public_path().'/storage/products/'.$slug;
        $folderPath = 'public/products/'.$slug;

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

            ModelHelper::resize_crop_images(1200, 1200, $image, $folderPath."/".$filename);
            ModelHelper::resize_crop_images(900, 900, $image, $folderPath."/thumbs/small_".$filename);
            ModelHelper::resize_crop_images(600, 600, $image, $folderPath."/thumbs/thumb_".$filename);

            $insertArray['image'] = $filename;

        }

        if ($request->hasFile('other_images')) {
                    //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
                // Storage::putFileAs($folderPath, new File($other), $filename_o);

                ModelHelper::resize_crop_images(1200, 1200, $other, $folderPath."/".$filename_o);
                ModelHelper::resize_crop_images(900, 900, $other, $folderPath."/thumbs/small_".$filename_o);
                ModelHelper::resize_crop_images(600, 600, $other, $folderPath."/thumbs/thumb_".$filename_o);
            }

        }

        $product_created = Product::create($insertArray);
        $variations = $request->variation;

        if ($request->variation_type == 1) {

            foreach ($variations as $color) {
                // $color_slug = ProductColor::createSlug($product_created->id, $color['name']);
                $product_color = ProductColor::updateOrCreate(['product_id' => $product_created->id, 'color_id' => $color['color_id']],
                                                              ['sku' => $color['sku'], 'stock_count' => $color['stock_count']]);
            }
        }elseif ($request->variation_type == 2) {
            

            foreach ($variations as $color) {

                // $color_slug = ProductColor::createSlug($product_created->id, $color['name']);
                $product_color = ProductColor::updateOrCreate(['product_id' => $product_created->id, 'color_id' => $color['color_id']],
                                                              ['sku' => $color['sku']]);

                $sizes = $color['sizes'];

                foreach ($sizes as $size) {

                    // $size_slug = ProductSize::createSlug($product_color->id, $size['name']);
                    $size_created = ProductSize::updateOrCreate(
                                                    ['product_color_id' => $product_color->id, 'size_id' => $size['size_id']],
                                                    ['stock_count' => $size['stock_count']]
                                                );
                }
            }
        }

        if ($product_created) {

            return redirect()->route('admin.products.index')->with('status','New Product has been Added Successfully!');

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
        $product = Product::findOrFail(base64_decode($id));
        $brands = Brand::where('display',1)->get();
        $categories = ModelHelper::getFullListFromDB('categories');
        return view('backend.products.create-update-products',compact('product','brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // dd($request);
        $slug = ModelHelper::createSlug('\App\Models\Product', $request->title, $product->id);
        $path = public_path().'/storage/products/'.$product->slug;

        if ($product->slug != $slug) {

            if (file_exists($path)) {
                Storage::move('public/products/'. $product->slug , 'public/products/'.$slug);
            }

            $slug = ModelHelper::createSlug('\App\Models\Product', $slug, $product->id);
            
        }

        $updateArray = array(
                             "title" => $request->title, 
                             "slug" => $slug,
                             "sku" => $request->sku,
                             "price" => $request->price,
                             "discounted_price" => isset($request->discounted_price) ? $request->discounted_price : 0,
                             "display" => isset($request->display) ? $request->display : 0,
                             "featured" => isset($request->featured) ? $request->featured : 0,
                             "variation_type" => $request->variation_type,
                             "short_description" => $request->short_description,
                             "long_description" => $request->long_description,
                             "stock_status" => $request->stock_status,
                             "stock_count" => $request->stock_count,
                             "brand_id" => $request->brand_id,
                             "tags" => $request->tags,
                             "updated_by" => Auth::user()->name,
                             "updated_at" => date('Y-m-d H:i:s')
                            );

        $path = public_path().'/storage/products/'.$slug;
        $folderPath = 'public/products/'.$slug;

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

            ModelHelper::resize_crop_images(1200, 1200, $image, $folderPath."/".$filename);
            ModelHelper::resize_crop_images(900, 900, $image, $folderPath."/thumbs/small_".$filename);
            ModelHelper::resize_crop_images(600, 600, $image, $folderPath."/thumbs/thumb_".$filename);

            $updateArray['image'] = $filename;

            $OldFilename = $product->image;
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

                ModelHelper::resize_crop_images(1200, 1200, $other, $folderPath."/".$filename_o);
                ModelHelper::resize_crop_images(900, 900, $other, $folderPath."/thumbs/small_".$filename_o);
                ModelHelper::resize_crop_images(600, 600, $other, $folderPath."/thumbs/thumb_".$filename_o);
            }

        }

        $product_updated = $product->update($updateArray);
        $variations = $request->variation;

        if ($request->variation_type == 1) {

            foreach ($variations as $color) {

                if (isset($color['id'])) {

                    // $color_slug = ProductColor::createSlug($product->id, $color['name'], $color['id']);

                    $product_color = ProductColor::find($color['id']);
                    $productColorExists = ProductColor::where([['id', '!=' , $product_color->id], ['product_id' , $product->id], ['color_id', $color['color_id']]])->exists();

                    if (!$productColorExists) {

                        ProductColor::where('id', $color['id'])->update([
                                                                            'color_id' => $color['color_id'],
                                                                            'sku' => $color['sku'],
                                                                            'stock_count' => $color['stock_count']
                                                                        ]);
                    }

                }else{

                    // $color_slug = ProductColor::createSlug($product->id, $color['name']);

                    $product_color = ProductColor::updateOrCreate(['product_id' => $product->id, 'color_id' => $color['color_id']],
                                                                  ['sku' => $color['sku'], 'stock_count' => $color['stock_count']]);

                }
            }
        }elseif ($request->variation_type == 2) {
            

            foreach ($variations as $color) {

                if (isset($color['id'])) {

                    // $color_slug = ProductColor::createSlug($product->id, $color['name'], $color['id']);

                    $product_color = ProductColor::find($color['id']);

                    $productColorExists = ProductColor::where([['id', '!=' , $product_color->id], ['product_id' , $product->id], ['color_id', $color['color_id']]])->exists();

                    if (!$productColorExists) {

                        ProductColor::where('id', $color['id'])->update([
                                                                            'color_id' => $color['color_id'],
                                                                            'sku' => $color['sku']
                                                                        ]);
                    }

                    $sizes = $color['sizes'];

                    foreach ($sizes as $size) {


                        if (isset($size['id'])) {
                            
                            // $size_slug = ProductSize::createSlug($color['id'], $size['name'], $size['id']);

                            $product_size = ProductSize::find($size['id']);

                            $sizeExists = ProductSize::where([['id', '!=' , $product_size->id], ['product_color_id', $color['id']], ['size_id' , $size['size_id']] ])->exists();
                            // dd($sizeExists);
                            if (!$sizeExists) {

                                ProductSize::where('id', $size['id'])->update([
                                                                    'size_id' => $size['size_id'],
                                                                    'stock_count' => $size['stock_count']
                                                                ]);
                            }


                        }else{

                            // $size_slug = ProductSize::createSlug($color['id'], $size['name']);
                             
                            $size = ProductSize::updateOrCreate(
                                                            ['product_color_id' => $color['id']],
                                                            ['stock_count' => $size['stock_count']]
                                                        );
                        }

                    }

                }else{

                    // $color_slug = ProductColor::createSlug($product->id, $color['name']);

                    $product_color = ProductColor::updateOrCreate(['product_id' => $product->id, 'color_id' => $color['color_id']],
                                                                  ['sku' => $color['sku']]);

                    $sizes = $color['sizes'];

                    foreach ($sizes as $size) {

                        // $size_slug = Size::createSlug($product_color->id, $size['name']);
                        $size = ProductSize::updateOrCreate(
                                                        ['product_color_id' => $product_color->id, 'size_id' => $size['size_id']],
                                                        ['stock_count' => $size['stock_count']]
                                                    );
                    }
                }

            }
        }

        // dd($request);
        if ($product_updated) {

            if (isset($request->category_id)) {

                $category_ids = $request->category_id;

                for ($i=0; $i < count($category_ids); $i++) { 
                    
                    $product->category_products()->updateOrCreate(['product_id' => $product->id, 'category_id' => $category_ids[$i]]);
                }

                $product->category_products()->whereNotIn('category_id',$category_ids)->delete();
                
            }else{
                $product->category_products()->delete();
            }

            return redirect()->route('admin.products.index')->with('status','Product has been Updated Successfully!');

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

        $product = Product::where('id' , base64_decode($id))->firstOrFail();
        // dd($product);
        if ($product) {

            if ($product->delete()) {

                $productFolder = 'public/products/'.$product->slug;
                Storage::deleteDirectory($productFolder);

                return redirect()->back()->with('status', 'Product Deleted Successfully!');
            }else{
                return redirect()->back()->with('status', 'Something Went Wrong!');
            }
        }else{

            return redirect()->back()->with('status', 'Product Not Found!');
        }
    }

    public function get_variation_fields(Request $request)
    {
            
        switch ($request->variation) {
            case '0':
                if ($request->flag == 0) {
                    $stock_count = 0;
                }else{
                    $product = Product::find($request->product_id);
                    $stock_count = $product->stock_count;
                }
                $returnResponse = '<div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-database"></i>&nbsp; Stock Count</span>
                                            </span>
                                            <input type="text" class="form-control" name="stock_count" placeholder="Available Quantity" onkeypress="return isDecimalNumber(event,this)" value="'.$stock_count.'" required>
                                        </div>
                                        <hr>
                                    </div>';
                break;
            case '1':
                $colors = Color::where('display',1)->get();
                if ($request->flag == 0) {

                    $returnResponse = '<div class="box box-success mb-3">
                                            <div class="box-body with-border" style="padding: 1.25rem;" id="colorOnlyVariation">

                                                <div class="row color-only-variation" id="color-0" data-cumulative-count="0">
                                                    
                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1 variation-color-picker">
                                                            <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                                            <select class="form-control" name="variation[0][color_id]" required>';
                                                            foreach ($colors as $color_key => $color) {
                                                                $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'">'.$color->title.'</option>';
                                                            }
                                                                
                                                            $returnResponse.='</select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                                            <input type="text" name="variation[0][sku]" class="form-control" placeholder="SKU"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                                            <input type="number" min="0" name="variation[0][stock_count]" class="form-control" placeholder="Stock Count"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn btn-sm btn-danger remove-color-btn" onclick="remove_color(0)" data-color-id="color-0" ><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                }elseif ($request->flag == 1) {
                    
                    $returnResponse ='';
                    $i = 0;
                    $returnResponse .= '<div class="box box-success mb-3">
                                            <div class="box-body with-border" style="padding: 1.25rem;" id="colorOnlyVariation">';

                                                $product = Product::find($request->product_id);
                                                $productColors = $product->product_colors;

                                                foreach ($productColors as $db_color_key => $db_color) {
                                                    
                                                    $returnResponse .= '<div class="row color-only-variation" id="color-'.$db_color_key.'" data-cumulative-count="'.$db_color_key.'">
                                                        <input type="hidden" name="variation['.$db_color_key.'][id]" value="'.$db_color->id.'">
                                                        
                                                        <div class="col-md-3">
                                                            <div class="input-group mb-1 variation-color-picker">

                                                                <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                                                <select class="form-control" name="variation['.$db_color_key.'][color_id]" required>';
                                                                foreach ($colors as $color_key => $color) {
                                                                    $colorSelectedStatus = $color->id == $db_color->color_id ? 'selected' : '';
                                                                    $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'" '.$colorSelectedStatus.'>'.$color->title.'</option>';
                                                                }
                                                                    
                                                                $returnResponse.='</select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                                                <input type="text" name="variation['.$db_color_key.'][sku]" class="form-control" placeholder="SKU" value="'.$db_color->sku.'"/>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                                                <input type="number" min="0" name="variation['.$db_color_key.'][stock_count]" class="form-control" placeholder="Stock Count" value="'.$db_color->stock_count.'"/>
                                                            </div>
                                                        </div>';

                                                        if ($productColors->count() > 0) {

                                                            $returnResponse .= '<div class="col-md-3">
                                                                <a href="#deleteVariation" data-toggle="modal" class="btn btn-sm btn-danger remove-color-btn" onclick="delete_variation(this)" data-identifier="color" data-color-id="'.$db_color->id.'" data-color-key="'.$db_color_key.'" ><i class="fa fa-trash"></i></a>
                                                            </div>';
                                                        }
                                                    $returnResponse .=  '</div>';
                                                }
                                            $returnResponse .= '</div>
                                        </div>';

                }
                break;

            case '2':
                $colors = Color::where('display',1)->get();
                $sizes = Size::where('display',1)->get();
                if ($request->flag == 0) {
                    
                    $returnResponse = '<div class="box box-success mb-3 color-size-variation" id="color-size-0" data-cumulative-count="0" style="margin: 0px;">
                                            <div class="box-body" style="padding: 1.25rem;">
                                                
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1 variation-color-picker">
                                                            <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                                            <select class="form-control" name="variation[0][color_id]" required>';
                                                            foreach ($colors as $color_key => $color) {
                                                                $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'">'.$color->title.'</option>';
                                                            }
                                                                
                                                            $returnResponse.='</select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                                            <input type="text" name="variation[0][sku]" class="form-control" placeholder="SKU"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-primary btn_add_size_variation" onclick="add_size_variation(0)">
                                                            <i class=" fa fa-plus"></i>
                                                        </button>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <div class="text-right">
                                                            <button type="button" class="btn btn-danger remove-color-size-btn" onclick="remove_color_size(0)" data-color-size-id="color-size-0" ><i class="fa fa-trash"></i></button>

                                                        </div>
                                                    </div>

                                                    <br><br>
                                                    <div class="col-lg-12">
                                                        <hr class="mt-0">
                                                        <div class="row" id="color-sizes-0">

                                                            <div class="col-md-3 size-input size-variation-0" id="size-0-0" data-cumulative-count="0">
                                                                <div class="input-group mb-1">
                                                                    <span class="input-group-addon"><i class="fa fa-text-width"></i> Size</span>
                                                                    <select class="form-control" name="variation[0][sizes][0][size_id]" required>';
                                                                    foreach ($sizes as $size_key => $size) {
                                                                        $returnResponse.='<option value="'.$size->id.'">'.$size->title.'</option>';
                                                                    }
                                                                        
                                                                    $returnResponse.='</select>

                                                                    <span class="input-group-btn">
                                                                        <button type="button" onclick="remove_size(0, 0)" class="btn btn-warning remove-size-btn-0"><i class="fa fa-trash"></i></button>
                                                                    </span>
                                                                </div>

                                                                <div class="input-group mb-1">
                                                                    <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                                                    <input type="number" min="0" name="variation[0][sizes][0][stock_count]" class="form-control name_list" placeholder="Stock" required />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';

                }elseif ($request->flag == 1) {
                    $product = Product::find($request->product_id);
                    $productColors = $product->product_colors;

                    $returnResponse ='';
                    
                    foreach ($productColors as $db_color_key => $db_color) {

                        $returnResponse .= '<div class="box box-success mb-3 color-size-variation" id="color-size-'.$db_color_key.'" data-cumulative-count="'.$db_color_key.'" style="margin: 0px;">
                                            <input type="hidden" name="variation['.$db_color_key.'][id]" value="'.$db_color->id.'">
                                            <div class="box-body" style="padding: 1.25rem;">
                                                
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1 variation-color-picker">
                                                            <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                                            <select class="form-control" name="variation['.$db_color_key.'][color_id]" required>';

                                                            foreach ($colors as $color_key => $color) {
                                                                $colorSelectedStatus = $color->id == $db_color->color_id ? 'selected' : '';
                                                                $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'" '.$colorSelectedStatus.'>'.$color->title.'</option>';
                                                            }
                                                                
                                                            $returnResponse.='</select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                                            <input type="text" name="variation['.$db_color_key.'][sku]" class="form-control" placeholder="SKU" value="'.$db_color->sku.'"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-primary btn_add_size_variation" onclick="add_size_variation('.$db_color_key.')">
                                                            <i class=" fa fa-plus"></i>
                                                        </button>
                                                    </div>';
                                                    if ($productColors->count() > 0) {
                                                        
                                                        $returnResponse .= '<div class="col-lg-2">
                                                            <div class="text-right">
                                                                <a href="#deleteVariation" data-toggle="modal" class="btn btn-danger remove-color-size-btn" onclick="delete_variation(this)" data-identifier="color-size" data-color-id="'.$db_color->id.'" data-color-key="'.$db_color_key.'" ><i class="fa fa-trash"></i></a>

                                                            </div>
                                                        </div>';
                                                    }

                                                    $returnResponse .= '<br><br>
                                                    <div class="col-lg-12">
                                                        <hr class="mt-0">
                                                        <div class="row" id="color-sizes-'.$db_color_key.'">';
                                                            foreach ($db_color->product_sizes as $db_size_key => $db_size) {
                                                                
                                                                $returnResponse .= '<div class="col-md-3 size-input size-variation-'.$db_color_key.'" id="size-'.$db_color_key.'-'.$db_size_key.'" data-cumulative-count="'.$db_size_key.'">
                                                                    <input type="hidden" name="variation['.$db_color_key.'][sizes]['.$db_size_key.'][id]" value="'.$db_size->id.'">

                                                                    <div class="input-group mb-1">
                                                                        <span class="input-group-addon"><i class="fa fa-text-width"></i> Size</span>
                                                                        <select class="form-control" name="variation['.$db_color_key.'][sizes]['.$db_size_key.'][size_id]" required>';

                                                                        foreach ($sizes as $size_key => $size) {
                                                                            $sizeSelectedStatus = $size->id == $db_size->size_id ? 'selected' : '';
                                                                            $returnResponse.='<option value="'.$size->id.'" '.$sizeSelectedStatus.'>'.$size->title.'</option>';
                                                                        }
                                                                            
                                                                        $returnResponse.='</select>';

                                                                        if ($db_color->product_sizes->count() > 0) {
                                                                            
                                                                            $returnResponse .='<span class="input-group-btn">
                                                                                <a href="#deleteVariation" data-toggle="modal" onclick="delete_variation(this)" data-identifier="size" data-color-id="'.$db_color->id.'" data-size-id="'.$db_size->id.'" data-color-key="'.$db_color_key.'" data-size-key="'.$db_size_key.'" class="btn btn-warning remove-size-btn-'.$db_size_key.'"><i class="fa fa-trash"></i></a>
                                                                            </span>';
                                                                        }

                                                                    $returnResponse .= '</div>

                                                                    <div class="input-group mb-1">
                                                                        <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                                                        <input type="number" min="0" name="variation['.$db_color_key.'][sizes]['.$db_size_key.'][stock_count]" class="form-control name_list" placeholder="Stock" required value="'.$db_size->stock_count.'"/>

                                                                    </div>
                                                                </div>';
                                                            }

                                                        $returnResponse .='</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';

                        
                    }
                }
                break;
            default:
                $returnResponse = 'Something Went wrong';
                break;
        }
        return $returnResponse;
    }

    public function add_extra_variation_fields(Request $request){
        $new_index = $request->cIndex;
        $returnResponse = '';
        if($request->variation == 1){
            $colors = Color::where('display',1)->get();
            $returnResponse .= '<div class="row color-only-variation" id="color-'.$new_index.'" data-cumulative-count="'.$new_index.'">
                        
                        <div class="col-md-3">
                            <div class="input-group mb-1 variation-color-picker">
                                <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                <select class="form-control" name="variation['.$new_index.'][color_id]" required>';
                                foreach ($colors as $color_key => $color) {
                                    $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'">'.$color->title.'</option>';
                                }
                                    
                                $returnResponse.='</select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group mb-1">
                                <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                <input type="text" name="variation['.$new_index.'][sku]" class="form-control" placeholder="SKU"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group mb-1">
                                <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                <input type="number" min="0" name="variation['.$new_index.'][stock_count]" class="form-control" placeholder="Stock Count"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-danger remove-color-btn" onclick="remove_color('.$new_index.')" data-color-id="color-'.$new_index.'"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>';

            
        }elseif($request->variation == 2){
            $colors = Color::where('display',1)->get();
            $sizes = Size::where('display',1)->get();
            $returnResponse .= '<div class="box box-success mb-3 color-size-variation" id="color-size-'.$new_index.'" data-cumulative-count="'.$new_index.'" style="margin: 0px;">
                        <div class="box-body" style="padding: 1.25rem;">
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group mb-1 variation-color-picker">
                                        <span class="input-group-addon"><i class="fa fa-eyedropper"></i> Color</span>
                                        <select class="form-control" name="variation['.$new_index.'][color_id]" required>';
                                        foreach ($colors as $color_key => $color) {
                                            $returnResponse.='<option style="color: '.$color->code.';" value="'.$color->id.'">'.$color->title.'</option>';
                                        }
                                            
                                        $returnResponse.='</select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group mb-1">
                                        <span class="input-group-addon"><i class="fa fa-barcode"></i> SKU</span>
                                        <input type="text" name="variation['.$new_index.'][sku]" class="form-control" placeholder="SKU"/>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <button type="button" class="btn btn-primary btn_add_size_variation" onclick="add_size_variation('.$new_index.')">
                                        <i class=" fa fa-plus"></i>
                                    </button>
                                </div>

                                <div class="col-lg-2">
                                    <div class="text-right">
                                        <button type="button" class="btn btn-danger remove-color-size-btn" onclick="remove_color_size('.$new_index.')" data-color-size-id="color-size-'.$new_index.'" ><i class="fa fa-trash"></i></button>

                                    </div>
                                </div>

                                <br><br>
                                <div class="col-lg-12">
                                    <hr class="mt-0">
                                    <div class="row" id="color-sizes-'.$new_index.'">

                                        <div class="col-md-3 size-input size-variation-'.$new_index.'" id="size-'.$new_index.'-0" data-cumulative-count="0">
                                            <div class="input-group mb-1">
                                                <span class="input-group-addon"><i class="fa fa-text-width"></i> Size</span>
                                                <select class="form-control" name="variation['.$new_index.'][sizes][0][size_id]" required>';
                                                foreach ($sizes as $size_key => $size) {
                                                    $returnResponse.='<option value="'.$size->id.'">'.$size->title.'</option>';
                                                }
                                                    
                                                $returnResponse.='</select>

                                                <span class="input-group-btn">
                                                    <button type="button" onclick="remove_size('.$new_index.', 0)" class="btn btn-warning remove-size-btn-'.$new_index.'"><i class="fa fa-trash"></i></button>
                                                </span>
                                            </div>

                                            <div class="input-group mb-1">
                                                <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                                                <input type="number" min="0" name="variation['.$new_index.'][sizes][0][stock_count]" class="form-control name_list" placeholder="Stock" required />

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>';
        }

        return $returnResponse;
    }

    public function get_size_fields(Request $request)
    {
        $size_index = $request->size_index;
        $color_index = $request->color_index;
        $sizes = Size::where('display',1)->get();
        $returnResponse = '';

        $returnResponse .= '<div class="col-md-3 size-input size-variation-'.$color_index.'" id="size-'.$color_index.'-'.$size_index.'" data-cumulative-count="'.$size_index.'">
                    <div class="input-group mb-1">
                        <span class="input-group-addon"><i class="fa fa-text-width"></i> Size</span>
                        <select class="form-control" name="variation['.$color_index.'][sizes]['.$size_index.'][size_id]" required>';
                        foreach ($sizes as $size_key => $size) {
                            $returnResponse.='<option value="'.$size->id.'">'.$size->title.'</option>';
                        }
                            
                        $returnResponse.='</select>

                        <span class="input-group-btn">
                            <button type="button" onclick="remove_size('.$color_index.', '.$size_index.')" class="btn btn-warning remove-size-btn-'.$color_index.'"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>

                    <div class="input-group mb-1">
                        <span class="input-group-addon"><i class="fa fa-database"></i> Stock</span>
                        <input type="number" min="0" name="variation['.$color_index.'][sizes]['.$size_index.'][stock_count]" class="form-control name_list" placeholder="Stock" required />

                    </div>
                </div>';

        return $returnResponse;
    }

    public function delete_variation(Request $request)
    {

        $id = $request->variation_id;
        $identifier = $request->identifier;

        if ($identifier == 'size') {
            $size = ProductSize::findOrFail($id);
            $deleted = $size->delete();
            // Variation::where('parent_id', $variation->id)->delete();
        }else{

            $color = ProductColor::find($id);
            $deleted = $color->delete();
        }

        if ($deleted) {

            $response = array('message' => "success");

        }else{
            $response = array('message' => "failed");
        }

        echo json_encode($response);
    }

    public function delete_gallery_image(Request $request){

        $slug = $request->slug;
        $image = $request->image;

        Storage::delete("public/products/".$slug."/".$image);
        Storage::delete("public/products/".$slug."/thumbs/small_".$image);
        Storage::delete("public/products/".$slug."/thumbs/thumb_".$image);

        $response = array('message' => "success");
        echo json_encode($response);
    }
}
