<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'size_id',
        'color_id',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSizeModel::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(ColorModel::class, 'color_id');
    }
}
