<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'image',
        'about_vision_title',
        'about_vision_description',
        'about_mission_title',
        'about_mission_description',
        'about_who_we_are_title',
        'about_who_we_are_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Get a page record by its slug.
     */
    public static function getSlug($slug)
    {
        return self::where('slug', '=', $slug)->first();
    }
}
