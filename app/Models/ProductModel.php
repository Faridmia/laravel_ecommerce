<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

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

    static public function getProduct($category_id = null, $sub_category_id = null)
    {
        $return = ProductModel::select(
            'products.*',
            'users.name as created_by_name',
            'sub_category.name as sub_category_name',
            'sub_category.category_slug as sub_category_slug',
            'categories.name as category_name',
            'categories.category_slug as category_slug'
        )
        ->join('users', 'users.id', '=', 'products.created_by')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->join('sub_category', 'sub_category.id', '=', 'products.sub_category_id');

        if (!empty($category_id)) {
            $return->where('products.category_id', $category_id);
        }

        if (!empty($sub_category_id)) {
            $return->where('products.sub_category_id', $sub_category_id);
        }

        if(!empty(Request::get('sub_category_id'))){
            $sub_category_id = rtrim(Request::get('sub_category_id'), ',');
            $sub_category_id_inarray = explode(',', $sub_category_id);
            $return = $return->whereIn('products.sub_category_id', $sub_category_id_inarray);
           
        } else {
            if(!empty(Request::get('old_category_id'))){
                $return->where('products.category_id', Request::get('old_category_id') );
            }

            if(!empty(Request::get('old_subcategory_id'))){
                $return->where('products.sub_category_id', Request::get('old_subcategory_id'));
            }
        }

        if( !empty( Request::get('color_id') ) ){
            $color_id = rtrim( Request::get('color_id' ), ',');
            $color_id_inarray = explode(',', $color_id);
            $return = $return->join('product_colors', 'product_colors.product_id', '=', 'products.id');
            $return = $return->whereIn('product_colors.color_id', $color_id_inarray);
           
        }

        if(!empty(Request::get('sub_brand_id'))){
            $sub_brand_id = rtrim(Request::get('sub_brand_id'), ',');
            $sub_brand_id_inarray = explode( ',', $sub_brand_id );
            $return = $return->whereIn('products.brand_id', $sub_brand_id_inarray);
           
        }

        if( !empty(Request::get('start_price')) && !empty(Request::get('end_price')) ){
            $start_price = str_replace('$', '', Request::get('start_price'));
            $end_price = str_replace('$', '', Request::get('end_price'));
            $return = $return->where('products.price', '>=', $start_price );
            $return = $return->where('products.price', '<=', $end_price );
        }
    
            

        return $return
            ->where('products.is_delete', 0)
            ->where('products.status', 0)
            // ->groupBy('products.id')
            ->distinct()
            ->orderBy('products.id', 'desc')
            ->paginate(12);
    }

    public function getImageSingle($product_id)
    {
        return ProductImageModel::where('product_id', $product_id)->orderBy('order_by', 'asc')->first();
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
