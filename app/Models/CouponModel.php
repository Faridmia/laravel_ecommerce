<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponModel extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    static public function getSingle($id)
    {
        return self::findOrFail($id);
    }

    static public function getRecord()
    {
        return self::select('coupons.*', 'users.name as created_by_name')
            ->leftJoin('users', 'users.id', '=', 'coupons.created_by')
            ->where('coupons.is_delete', '=', 0)
            ->orderBy('coupons.id', 'desc')
            ->paginate(20);
    }

    static public function getRecordActive()
    {
        return self::select('coupons.*')
            ->where('coupons.is_delete', '=', 0)
            ->where('coupons.status', '=', 0)
            ->orderBy('coupons.id', 'desc')
            ->get();
    }
}
