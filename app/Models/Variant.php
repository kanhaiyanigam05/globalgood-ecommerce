<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'product_id',
        'price',
        'compare_at_price',
        'quantity',
        'sku',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'price' => 'integer',
        'compare_at_price' => 'integer',
        'quantity' => 'integer',
        'status' => 'boolean',
    ];

    protected $appends = [
        'price_formatted',
        'compare_at_price_formatted',
    ];

    public function getPriceFormattedAttribute()
    {
        return number_format($this->price / 100, 2, '.', '');
    }

    public function getCompareAtPriceFormattedAttribute()
    {
        return number_format($this->compare_at_price / 100, 2, '.', '');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) round($value * 100);
    }

    public function setCompareAtPriceAttribute($value)
    {
        $this->attributes['compare_at_price'] = (int) round($value * 100);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'variant_attribute', 'variant_id', 'attribute_id')
            ->withPivot('value')
            ->withTimestamps();
    }
}
