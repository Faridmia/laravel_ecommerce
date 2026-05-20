<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $primaryKey = 'brand_id';

    public $incrementing = true;

    protected $keyType = 'int';

    static public function getSingle($id)
    {
        return self::find($id);
    }

   
    static public function getRecord()
    {
        return self::select('brands.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'brands.created_by')
            ->where('brands.is_delete', '=', 0)
            ->orderBy('brands.brand_id', 'desc')
            ->paginate(10);
    }
       
   
}
