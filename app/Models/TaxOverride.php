<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxOverride extends Model
{
    protected $fillable = [
        'country_id',
        'country_zone_id',
        'tax_rate',
        'tax_name',
        'tax_type'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function zone()
    {
        return $this->belongsTo(CountryZone::class, 'country_zone_id');
    }
}
