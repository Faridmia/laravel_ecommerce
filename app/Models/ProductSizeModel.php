<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeModel extends Model
{
    use HasFactory;

    protected $table = 'product_sizes';

    static function getSingle( $size_id )
    {
        return self::find($size_id);
    }   

    public static function DeleteRecord( $product_id )
    {
        self::where('product_id', $product_id)->delete();
    }

}
