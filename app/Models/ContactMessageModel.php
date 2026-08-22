<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_messages';
    protected $fillable = ['user_id', 'name', 'email', 'phone', 'subject', 'message'];

    /**
     * Get the user that sent this contact message.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get and search contact message records.
     */
    public static function getRecord($searchParams = [])
    {
        $query = self::select('contact_messages.*', 'users.name as login_name')
            ->leftJoin('users', 'users.id', '=', 'contact_messages.user_id');

        if (!empty($searchParams['id'])) {
            $query->where('contact_messages.id', $searchParams['id']);
        }
        if (!empty($searchParams['name'])) {
            $query->where('contact_messages.name', 'like', '%' . trim($searchParams['name']) . '%');
        }
        if (!empty($searchParams['email'])) {
            $query->where('contact_messages.email', 'like', '%' . trim($searchParams['email']) . '%');
        }
        if (!empty($searchParams['phone'])) {
            $query->where('contact_messages.phone', 'like', '%' . trim($searchParams['phone']) . '%');
        }
        if (!empty($searchParams['subject'])) {
            $query->where('contact_messages.subject', 'like', '%' . trim($searchParams['subject']) . '%');
        }

        return $query->orderBy('contact_messages.id', 'desc')->paginate(20);
    }
}
