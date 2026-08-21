<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'discount',
        'shipping_charge',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'coupon_code',
        'order_notes',
        
        // Billing
        'billing_first_name',
        'billing_last_name',
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
        'billing_email',

        // Shipping
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
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
