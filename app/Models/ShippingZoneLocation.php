<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZoneLocation extends Model
{
    use HasFactory;

    protected $table = 'shipping_zone_locations';

    protected $fillable = ['shipping_zone_id', 'country_id', 'division_id', 'district_id', 'area_id'];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
