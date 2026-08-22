<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'designation',
        'review',
        'image',
    ];

    /**
     * Get user image url or fallback.
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('upload/testimonials/' . $this->image))) {
            return asset('upload/testimonials/' . $this->image);
        }
        
        if (file_exists(public_path('assets/images/testimonials/' . $this->image))) {
            return asset('assets/images/testimonials/' . $this->image);
        }
        
        return asset('assets/images/testimonials/user-1.jpg');
    }
}
