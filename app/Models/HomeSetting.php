<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;

    protected $table = 'home_setting';

    protected $fillable = [
        'trendy_product_title',
        'shop_category_title',
        'recent_arrival_title',
        'blog_title',
        'payment_delivery_title',
        'payment_delivery_description',
        'payment_delivery_image',
        'refund_title',
        'refund_description',
        'refund_image',
        'support_title',
        'support_description',
        'support_image',
        'singup_title',
        'singup_description',
        'singup_image',
    ];

    /**
     * Get single record.
     */
    public static function getSingle()
    {
        return self::first();
    }

    /**
     * Helper methods to get image URLs.
     */
    public function getPaymentDeliveryImageUrl()
    {
        if (!empty($this->payment_delivery_image) && file_exists(public_path('upload/home/' . $this->payment_delivery_image))) {
            return asset('upload/home/' . $this->payment_delivery_image);
        }
        return '';
    }

    public function getRefundImageUrl()
    {
        if (!empty($this->refund_image) && file_exists(public_path('upload/home/' . $this->refund_image))) {
            return asset('upload/home/' . $this->refund_image);
        }
        return '';
    }

    public function getSupportImageUrl()
    {
        if (!empty($this->support_image) && file_exists(public_path('upload/home/' . $this->support_image))) {
            return asset('upload/home/' . $this->support_image);
        }
        return '';
    }

    public function getSingupImageUrl()
    {
        if (!empty($this->singup_image) && file_exists(public_path('upload/home/' . $this->singup_image))) {
            return asset('upload/home/' . $this->singup_image);
        }
        return '';
    }
}
