<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = [
        'website_name',
        'logo',
        'fevicon',
        'footer_description',
        'footer_payment_icon',
        'address',
        'phone',
        'phone_two',
        'submit_email',
        'email',
        'email_two',
        'working_hour',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'youtube_link',
        'pinterest_link',
    ];

    /**
     * Get the single system settings record.
     */
    public static function getSingle()
    {
        $setting = self::find(1);
        if (!$setting) {
            $setting = new self();
            $setting->id = 1;
            $setting->website_name = 'Ecommer';
        }
        return $setting;
    }

    /**
     * Get Website Logo URL.
     */
    public function getLogoUrl()
    {
        if (!empty($this->logo) && file_exists(public_path('upload/system/' . $this->logo))) {
            return asset('upload/system/' . $this->logo);
        }
        return asset('assets/images/logo.png');
    }

    /**
     * Get Website Favicon URL.
     */
    public function getFaviconUrl()
    {
        if (!empty($this->fevicon) && file_exists(public_path('upload/system/' . $this->fevicon))) {
            return asset('upload/system/' . $this->fevicon);
        }
        return asset('assets/images/icons/favicon.ico');
    }

    /**
     * Get Footer Payment Icon URL.
     */
    public function getPaymentIconUrl()
    {
        if (!empty($this->footer_payment_icon) && file_exists(public_path('upload/system/' . $this->footer_payment_icon))) {
            return asset('upload/system/' . $this->footer_payment_icon);
        }
        return asset('assets/images/payments.png');
    }
}
