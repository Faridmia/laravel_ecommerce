<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $table = 'smtp_settings';

    protected $fillable = [
        'name',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
    ];

    /**
     * Get the single SMTP settings record.
     */
    public static function getSingle()
    {
        $setting = self::find(1);
        if (!$setting) {
            $setting = new self();
            $setting->id = 1;
            $setting->name = 'Molla Commerce';
            $setting->mail_mailer = 'smtp';
            $setting->mail_host = 'smtp.gmail.com';
            $setting->mail_port = '587';
            $setting->mail_username = '';
            $setting->mail_password = '';
            $setting->mail_encryption = 'tls';
            $setting->mail_from_address = 'hello@example.com';
            $setting->save();
        }
        return $setting;
    }
}
