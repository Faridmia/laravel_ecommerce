<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'designation',
        'image',
        'facebook_link',
        'twitter_link',
        'instagram_link',
    ];

    /**
     * Get image url or standard theme placeholder fallback.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/team/' . $this->image))) {
            return asset('upload/team/' . $this->image);
        }
        
        // Match theme original image filenames based on ID or fallback
        if (file_exists(public_path('assets/images/team/' . $this->image))) {
            return asset('assets/images/team/' . $this->image);
        }
        
        return asset('assets/images/team/member-1.jpg');
    }
}
