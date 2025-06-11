<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    // Relation Between User & Vendor-Profile
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    protected $guarded = ['id'];

    
}
