<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    static public function checkSlug($slug)
    {
        return self::where('slug', $slug)->count();
    }

    static public function getSingle($id)
    {
        return self::findOrFail($id);
    }

    static public function getRecord()
    {
        return self::select('products.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'products.created_by')
            ->where('products.is_delete', '=', 0)
            ->orderBy('products.id', 'desc')
            ->paginate(10);
    }

    public function getColors()
    {
        return $this->hasMany( ProductColorModel::class, 'product_id' );
    }

     public function getSize()
    {
        return $this->hasMany( ProductSizeModel::class, 'product_id' );
    }
   
}
