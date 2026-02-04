<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketSale extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'sale_type',
        'sale_value',
        'applied_on',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sale) {
            if (!$sale->slug) {
                $sale->slug = Str::slug($sale->title) . '-' . Str::random(5);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(MarketSaleItem::class);
    }
}
