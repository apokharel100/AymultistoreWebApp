<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'color_id', 'price', 'stock_count', 'sku'];
    protected $hidden = ['price', 'created_at', 'updated_at'];

    public function product_sizes()
    {
    	return $this->hasMany(ProductSize::class);
    }

    public function color_details()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public static function createSlug($product_id, $title, $id = 0)
    {
        $i = 0;
        $slug = Str::slug($title);

        $allSlugs = Self::getRelatedSlugs($product_id, $slug, $id);
        
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

    protected static function getRelatedSlugs($product_id, $slug, $id = 0)
    {
        return \App\Models\ProductColor::select('slug')->where('slug', 'like', $slug . '%')
        	->where('product_id',$product_id)
            ->where('id', '<>', $id)
            ->get();
    }
}
