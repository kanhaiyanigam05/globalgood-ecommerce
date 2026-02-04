<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'variant_id',
        'name', 'sku', 'quantity', 'price',
        'tax_amount', 'discount_amount', 'total',
        'is_custom', 'weight'
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'price' => 'integer',
        'tax_amount' => 'integer',
        'discount_amount' => 'integer',
        'total' => 'integer',
    ];

    protected $appends = [
        'formatted_price',
        'formatted_tax_amount',
        'formatted_discount_amount',
        'formatted_total',
    ];

    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['price'] / 100, 2);
    }

    public function getFormattedTaxAmountAttribute()
    {
        return number_format($this->attributes['tax_amount'] / 100, 2);
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return number_format($this->attributes['discount_amount'] / 100, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->attributes['total'] / 100, 2);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) round($value * 100);
    }

    public function setTaxAmountAttribute($value)
    {
        $this->attributes['tax_amount'] = (int) round($value * 100);
    }

    public function setDiscountAmountAttribute($value)
    {
        $this->attributes['discount_amount'] = (int) round($value * 100);
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (int) round($value * 100);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
