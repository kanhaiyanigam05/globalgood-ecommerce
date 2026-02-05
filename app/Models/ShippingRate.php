<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'shipping_zone_id',
        'name',
        'type',
        'min_value',
        'max_value',
        'price'
    ];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class);
    }
}
