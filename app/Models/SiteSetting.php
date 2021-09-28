<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
    						'logo',
							'favicon',
							'sitetitle',
							'siteemail',
							'sitekeyword',
							'facebookurl',
							'twitterurl',
							'googleplusurl',
							'linkedinurl',
							'instagramurl',
							'youtubeurl',
							'short_content',
							'phone',
							'mobile',
							'fax',
							'address',
							'delivery_charge',
							'og_title',
							'og_description',
							'og_image',
							'meta_title',
							'meta_description',
							'meta_keywords'
						];

	protected $hidden = ['created_at', 'updated_at'];
}
