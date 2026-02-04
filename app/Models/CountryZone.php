<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryZone extends Model
{
    protected $table    = 'country_zones';
    protected $fillable = [
        'country_id', 'name', 'code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function taxOverrides()
    {
        return $this->hasMany(TaxOverride::class, 'country_zone_id');
    }
}
