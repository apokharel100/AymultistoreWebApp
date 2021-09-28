<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'display', 'created_by', 'updated_by'];
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public static function createSlug($product_color_id, $title, $id = 0)
    {
        $i = 0;
        $slug = Str::slug($title);

        $allSlugs = Self::getRelatedSlugs($product_color_id, $slug, $id);
        
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

    protected static function getRelatedSlugs($product_color_id, $slug, $id = 0)
    {
        return \App\Models\Size::select('slug')->where('slug', 'like', $slug . '%')
        	->where('product_color_id', $product_color_id)
            ->where('id', '<>', $id)
            ->get();
    }
}
