<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';

    protected $fillable = [
        'name',
        'image',
        'link',
    ];

    /**
     * Get image url or standard theme placeholder fallback.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/partners/' . $this->image))) {
            return asset('upload/partners/' . $this->image);
        }
        
        // Check if it's one of the default assets
        if (!empty($this->image) && file_exists(public_path('assets/images/brands/' . $this->image))) {
            return asset('assets/images/brands/' . $this->image);
        }
        
        return asset('assets/images/brands/1.png');
    }
}
