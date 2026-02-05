<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRegion extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'tax_rate'
    ];
}
