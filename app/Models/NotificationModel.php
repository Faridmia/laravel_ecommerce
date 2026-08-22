<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'url',
        'message',
        'is_read',
    ];

    /**
     * Get the user associated with the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Notify all admin users.
     */
    public static function notifyAdmins($message, $url)
    {
        $admins = User::where('is_admin', 1)->where('is_delete', 0)->get();
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'message' => $message,
                'url' => $url,
                'is_read' => 0
            ]);
        }
    }

    /**
     * Notify a specific user.
     */
    public static function notifyUser($userId, $message, $url)
    {
        return self::create([
            'user_id' => $userId,
            'message' => $message,
            'url' => $url,
            'is_read' => 0
        ]);
    }
}
