<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_messages';
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];
}
