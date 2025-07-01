<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //  ========    Relations
    // Order & USer
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Order & OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    //  ========    Relations End





    protected $guarded = ['id'];
}
