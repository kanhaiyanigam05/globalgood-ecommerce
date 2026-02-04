<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    protected $fillable = [
        'country_id',
        'tax_rate',
        'tax_name',
        'is_active'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
