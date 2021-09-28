<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ApiHelper
{
	public static function execute_products_functionality($product)
	{
		$gallery_images = array();
	    $product->discounted_price = $product->discounted_price == NULL ? 0 : $product->discounted_price;
		// array_push($gallery_images, $product->image);

		$images = Storage::files('public/products/'.$product->slug.'/');

		for ($i=0; $i < count($images); $i++) { 
			if (basename($images[$i]) != $product->image) {
				array_push($gallery_images, basename($images[$i]));
			}
			
		}

		$product->gallery_images = $gallery_images;
		$product->attributes = [
							'main_image_url' => asset('storage/products/'.$product->slug."/"), 
							'image_variation_url' => asset('storage/products/'.$product->slug."/"),
							'image_variations' => ['small_','thumb_']
						];
		
		$categoryNames = array();
		$productCategories = $product->categories;

		foreach ($productCategories as $key => $prodCat) {
			array_push($categoryNames, $prodCat->title);
		}

		$product->category_names = $categoryNames;

		unset($product->categories);

		// if ($product->product_colors->count() > 0) {
		// 	foreach ($product->product_colors as $color_key => $color) {

		// 	}
		// }

		return $product;

	}
}