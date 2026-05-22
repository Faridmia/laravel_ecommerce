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

    public static function getCategoryList()
    {
        return self::with('user')
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

     public static function getCategoryActive()
    {
        return self::select('categories.*','users.name as created_by_name')->join('users', 'users.id', '=', 'categories.created_by')
            ->where('categories.status', 0 ) 
            ->where('categories.is_deleted', 0)    
            ->orderBy('categories.name', 'asc')
            ->get();
    }
}
