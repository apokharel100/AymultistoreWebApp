<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'status',
        'phone',
        'address',
        'gender',
        'city',
        'region',
        'country',
        'postal_code',
        'google_token',
        'facebook_token',
        'apple_token',
        'otp',
        'remember_token',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "image",
        "phone",
        "address",
        "gender",
        "city",
        "region",
        "country",
        "postal_code",
        'password',
        'remember_token',
        'deleted_at',
        'created_by',
        'updated_by',
        'created_at', 
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function api_tokens()
    {
        return $this->hasMany(ApiToken::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function customer_addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function customer_orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function applied_coupons()
    {
        return $this->hasMany(AppliedCoupon::class, 'customer_id');
    }

}
