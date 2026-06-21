<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'sub_category';

   protected $fillable = [
        'category_id',
        'name',
        'category_slug',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public static function getSubCategoryList()
    // {
    //     return self::select('sub_categories.*','users.name as created_by_name')
    //     ->join('users', 'users.id', '=', 'sub_categories.created_by')  
    //     ->join('categories', 'categories.id', '=', 'sub_categories.category_id')  
    //     ->orderBy('sub_categories.id', 'desc')
    //     ->get();
    // }

    public static function getSingleSlug( $slug )
    {
        return self::where('category_slug', $slug)->where('sub_category.status', 0 )->where('sub_category.is_deleted', 0)->first();
    } 

    public static function getSubCategoryList()
    {
        return self::with(['creator:id,name', 'category:id,name'])
            ->orderByDesc('id')
            ->paginate(5);
    }

    
    public static function getRecordSubCategory( $categoryId )
    {
        return self::select('sub_category.*')
            ->join('users', 'users.id', '=', 'sub_category.created_by')
            ->where('sub_category.status', 0 ) 
            ->where('sub_category.is_deleted', 0)  
            ->where('sub_category.category_id', '=', $categoryId)  
            ->orderBy('sub_category.name', 'asc')
            ->get();
    }

    public function totalProducts()
    {
        return $this->hasMany(ProductModel::class, 'sub_category_id')->where('status', 0)->where('is_delete', 0)->count();
    }
}
