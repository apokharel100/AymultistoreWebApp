<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'display', 'image', 'description', 'created_by', 'updated_by'];
    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at'];
    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
