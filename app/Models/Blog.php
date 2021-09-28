<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'slug',
    	'display',
    	'featured',
    	'author',
    	'image',
    	'short_description',
    	'long_description',
    	'created_by',
    	'updated_by'
    ];
}
