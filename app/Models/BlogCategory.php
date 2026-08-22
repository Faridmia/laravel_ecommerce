<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_delete',
        'created_by',
    ];

    /**
     * Get active blog categories.
     */
    public static function getActiveCategories()
    {
        return self::where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get blog posts for this category.
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
