<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'tags',
        'status',
        'is_delete',
        'created_by',
    ];

    /**
     * Get image url or standard theme placeholder fallback.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/blogs/' . $this->image))) {
            return asset('upload/blogs/' . $this->image);
        }
        
        // Fallback to default seeded assets based on id
        $fallback_id = (($this->id - 1) % 3) + 1;
        return asset('assets/images/blog/home/post-' . $fallback_id . '.jpg');
    }

    /**
     * Get category of the blog post.
     */
    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get author of the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get comments of the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
}
