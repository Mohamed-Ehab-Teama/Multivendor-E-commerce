<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //  ========    Relations
    // Order & OrderItem
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


    // OrderItem & Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    //  ========    Relations End





    protected $guarded = ['id'];
}
