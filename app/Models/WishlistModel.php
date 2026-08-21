<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistModel extends Model
{
    protected $table = 'wishlists';
    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
