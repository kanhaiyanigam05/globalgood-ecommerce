<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProfile extends Model
{
    protected $fillable = ['name', 'is_default'];

    public function zones()
    {
        return $this->hasMany(ShippingZone::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shipping_profile_products');
    }
}
