<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // ================ Relations ==================== //
    // Relation Between Category & SubCategories
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    
    // Relation Between Category & SubCategories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    // Relation Between Category & Products
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    // ================ Relations End ==================== //
}
