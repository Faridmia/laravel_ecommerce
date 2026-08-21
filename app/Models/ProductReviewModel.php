<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewModel extends Model
{
    protected $table = 'product_reviews';
    protected $fillable = ['product_id', 'user_id', 'name', 'email', 'rating', 'review', 'status'];

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
