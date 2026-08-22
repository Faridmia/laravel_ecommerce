<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'category_slug',
        'status',
        'is_home',
        'image',
        'button_text',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public static function getCategoryList()
    // {
    //     return self::select('categories.*','users.name as created_by_name')->join('users', 'users.id', '=', 'categories.created_by')    
    //         ->orderBy('categories.id', 'desc')
    //         ->get();
    // }

    public static function getSingleSlug( $slug )
    {
        return self::where('category_slug', $slug)->where('categories.status', 0 )->where('categories.is_deleted', 0)->first();
    } 

    public static function getCategoryList()
    {
        return self::with('user')
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    public static function getCategoryAll()
    {
        return self::with('user')
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getCategoryActive()
    {
        return self::select('categories.*','users.name as created_by_name')->join('users', 'users.id', '=', 'categories.created_by')
            ->where('categories.status', 0 ) 
            ->where('categories.is_deleted', 0)    
            ->orderBy('categories.name', 'asc')
            ->get();
    }

    public static function getCategoryMenu()
    {
        return self::select('categories.*','users.name as created_by_name')->join('users', 'users.id', '=', 'categories.created_by')
            ->where('categories.status', 0 ) 
            ->where('categories.is_deleted', 0)    
            ->orderBy('categories.name', 'asc')
            ->get();
    }

    public function getSubCategory()
    {
        return $this->hasMany( SubCategoryModel::class, 'category_id' )->where('sub_category.status', 0)->where('sub_category.is_deleted', 0)->orderBy('sub_category.name', 'asc');
    }

    public function products()
    {
        return $this->hasMany(ProductModel::class, 'category_id')
            ->where('products.is_delete', 0)
            ->where('products.status', 0)
            ->orderBy('products.id', 'desc')
            ->limit(8);
    }

    public function totalProducts()
    {
        return ProductModel::where('category_id', $this->id)
            ->where('is_delete', 0)
            ->where('status', 0)
            ->count();
    }

    /**
     * Get category banner image url.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/categories/' . $this->image))) {
            return asset('upload/categories/' . $this->image);
        }
        
        // Return default theme category banner based on ID or fallback
        if (file_exists(public_path('assets/images/banners/home/banner-' . $this->id . '.jpg'))) {
            return asset('assets/images/banners/home/banner-' . $this->id . '.jpg');
        }
        
        // Fallback banner loop (1 to 4)
        $fallback_id = (($this->id - 1) % 4) + 1;
        return asset('assets/images/banners/home/banner-' . $fallback_id . '.jpg');
    }
}
