<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $table = 'shipping_zones';

    protected $fillable = ['name', 'is_active'];

    public function locations()
    {
        return $this->hasMany(ShippingZoneLocation::class, 'shipping_zone_id');
    }

    public function methods()
    {
        return $this->hasMany(ShippingMethod::class, 'shipping_zone_id');
    }

    public static function getSingle($id)
    {
        return self::findOrFail($id);
    }

    public static function getRecord()
    {
        return self::orderBy('id', 'desc')->paginate(20);
    }
}
