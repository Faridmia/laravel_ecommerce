<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'image',
    ];

    /**
     * Get image url or standard theme placeholder fallback.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/sliders/' . $this->image))) {
            return asset('upload/sliders/' . $this->image);
        }
        
        // Check if it's one of the default assets
        if (!empty($this->image) && file_exists(public_path('assets/images/slider/' . $this->image))) {
            return asset('assets/images/slider/' . $this->image);
        }
        
        return asset('assets/images/slider/slide-1.jpg');
    }
}
