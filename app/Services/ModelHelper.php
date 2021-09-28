<?php

namespace App\Services;

// use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class ModelHelper
{
    public static function getFullListFromDB($table, $parent_id = 0)
    {
        $datas = DB::table($table)->select('id','title', 'slug', 'image', 'display', 'popular', 'content', 'order_item', 'child', 'parent_id')->where('parent_id', $parent_id)->orderBy('order_item')->get();
        // $datas = collect($datas)->all();

        foreach ($datas as &$value) {
            $subresult = Self::getFullListFromDB($table, $value->id);

            if (count($subresult) > 0) {
                $value->children = collect($subresult)->all();
            }
        }

        unset($value);

        return $datas;
    }

    public static function createSlug($model, $title, $id = 0)
    {
        $i = 0;
        $slug = Str::slug($title);

        $allSlugs = Self::getRelatedSlugs($model, $slug, $id);
        // dd($allSlugs);

        
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

 		do{
            $i++;
            $newSlug = $slug . '-' . $i;

        }while($allSlugs->contains('slug', $newSlug));

        return $newSlug;

        throw new \Exception('Can not create a unique slug');
    }

    protected static function getRelatedSlugs($model, $slug, $id = 0)
    {
        return $model::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    public static function resize_crop_images($max_width, $max_height, $image, $filename){
        $imgSize = getimagesize($image);
        $width = $imgSize[0];
        $height = $imgSize[1];

        $width_new = round($height * $max_width / $max_height);
        $height_new = round($width * $max_height / $max_width);

        if ($width_new > $width) {
            //cut point by height
            $h_point = round(($height - $height_new) / 2);

            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width, $height_new, 0, $h_point)->resize($max_width, $max_height)->save($cover);
        } else {
            //cut point by width
            $w_point = round(($width - $width_new) / 2);
            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width_new, $height, $w_point, 0)->resize($max_width, $max_height)->save($cover);
        }

    }

    public static function set_order($list_order, $model, $has_child = 0)
    {

        if ($has_child == 0 || !isset($has_child)) {
            
            $i = 1 ;
            foreach($list_order as $id) {
                $updateData = array("order_item" => $i);
                $model::where('id', $id)->update($updateData);

                $i++ ;
            }

            $data = array('status'=> 'success');
            return $data;

        }else{

            Self::saveList($list_order, $model);
            $data = array('status' => 'success');
            echo json_encode($data);
            exit;
        }

    }

    public static function saveList($list, $model, $parent_id = 0, $child = 0, &$m_order = 0)
    {

        foreach ($list as $item) {
            
            $m_order++;
            $updateData = array("parent_id" => $parent_id, "child" => $child, "order_item" => $m_order);

            $model::where('id', $item['id'])->update($updateData);

            if (array_key_exists("children", $item)) {

                $updateData = array("child" => 1);
                $model::where('id', $item['id'])->update($updateData);
                Self::saveList($item["children"], $model, $item['id'], 0, $m_order);

            }
        }
    }

    public static function update_child_status($model, $new_parent_id, $old_parent_id = 0)
    {
        if ($new_parent_id != 0) {
            // dd('test');
            $model::where('id',$new_parent_id)->update(['child' => 1]);
        }

        if ($old_parent_id != 0) {

            $childCount = $model::where('parent_id', $old_parent_id)->get()->count();
            // dd($childCount);
            if ($childCount == 0) {
                $model::where('id', $old_parent_id)->update(['child' => 0]);
            }
            
        }

    }
    public static function payment_methods()
    {
        $payment_methods = [1 => 'Cash on Delivery', 2 => 'Bank Payment Gateway', 3 => 'Esewa', 4 => 'Khalti', 5 => 'IME Pay', 6 => 'Phone Pay Available', 7 => 'Connect IPS'];
        return $payment_methods;
    }
}
