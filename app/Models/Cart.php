<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // ===================================================  Relations       ==============  //
    // Relation Between User & Cart
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    // Relation Between Product & Cart
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // ===================================================  Relations End    ==============  //



    protected $guarded = ['id'];
}
