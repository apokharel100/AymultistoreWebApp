<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
		    'user_id', 'address_type', 'name', 'email', 'phone', 'street_address', 'apt_ste_bldg', 'city', 'state', 'zip_code', 'country'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function customer()
	{
		return $this->belongsTo(User::class);
	}	
}
