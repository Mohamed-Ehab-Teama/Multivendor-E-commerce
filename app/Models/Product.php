<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    // ===================================================  Relations       ==============  //
    // Relation Between Vendor & Products
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }



    // Relation Between Category & Products
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }



    // Relation Between Product & Cart
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }



    // OrderItem & Product
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    // ===================================================  Relations End   ==============  //




    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = ['id'];
}
