<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'user_id',
        'name',
        'email',
        'comment',
        'status',
        'is_delete',
    ];

    /**
     * Get the blog that owns the comment.
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
