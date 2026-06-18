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
            ->paginate(50);
    }

    static public function getProduct( $category_id = null, $sub_category_id = null )
    {
        $return = ProductModel::select('products.*', 'users.name as created_by_name', 'sub_category.name as sub_category_name', 'sub_category.category_slug as sub_category_slug' )
            ->join('users', 'users.id', '=', 'products.created_by')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('sub_category', 'sub_category.id', '=', 'products.sub_category_id');

            if( !empty($category_id) )
            {
                $return = $return->where('products.category_id', '=', $category_id);
            }

            if( !empty($sub_category_id) )
            {
                $return = $return->where('products.sub_category_id', '=', $sub_category_id);
            }

            $return = $return->where('products.is_delete', '=', 0)
            ->where('products.status', '=', 0)
            ->orderBy('products.id', 'desc')
            ->paginate(20);

        return $return;
    }

    public function getColors()
    {
        return $this->hasMany( ProductColorModel::class, 'product_id' );
    }

    public function getSize()
    {
        return $this->hasMany( ProductSizeModel::class, 'product_id' );
    }

   public function getImages()
{
    return $this->hasMany(ProductImageModel::class, 'product_id')
        ->orderBy('order_by', 'asc');
}
   
}
