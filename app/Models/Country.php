<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'flag',
        'continent',
        'telcode',
        'postalcode',
        'zone',
    ];

    public function zones()
    {
        return $this->hasMany(CountryZone::class);
    }

    public function taxSettings()
    {
        return $this->hasMany(TaxSetting::class);
    }

    public function taxOverrides()
    {
        return $this->hasMany(TaxOverride::class);
    }
}
