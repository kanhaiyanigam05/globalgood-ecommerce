<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['shipping_profile_id', 'name'];

    public function profile()
    {
        return $this->belongsTo(ShippingProfile::class, 'shipping_profile_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'shipping_zone_countries');
    }

    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}
