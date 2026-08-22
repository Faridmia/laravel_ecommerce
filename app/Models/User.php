<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'heading',
        'intro',
        'profile_pic',
        'first_name',
        'last_name',
        'display_name',
        'billing_company',
        'billing_country_id',
        'billing_division_id',
        'billing_district_id',
        'billing_area_id',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_phone',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_country_id',
        'shipping_division_id',
        'shipping_district_id',
        'shipping_area_id',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    static function getAdminList()
    {
        return User::select('users.*')
            ->where('is_admin', 1)
            ->where('is_delete', 0 )
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getProfilePicUrl()
    {
        if (!empty($this->profile_pic) && file_exists(public_path('upload/profile/' . $this->profile_pic))) {
            return asset('upload/profile/' . $this->profile_pic);
        }
        return asset('assets/img/user1-128x128.jpg');
    }

    public function billingCountry()
    {
        return $this->belongsTo(Country::class, 'billing_country_id');
    }

    public function billingDivision()
    {
        return $this->belongsTo(Division::class, 'billing_division_id');
    }

    public function billingDistrict()
    {
        return $this->belongsTo(District::class, 'billing_district_id');
    }

    public function billingArea()
    {
        return $this->belongsTo(Area::class, 'billing_area_id');
    }

    public function shippingCountry()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id');
    }

    public function shippingDivision()
    {
        return $this->belongsTo(Division::class, 'shipping_division_id');
    }

    public function shippingDistrict()
    {
        return $this->belongsTo(District::class, 'shipping_district_id');
    }

    public function shippingArea()
    {
        return $this->belongsTo(Area::class, 'shipping_area_id');
    }
}
