<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'email', 'phone', 
        'subtotal', 'discount_amount', 'shipping_amount', 'tax_amount', 'total',
        'currency', 'status', 'fulfillment_status',
        'shipping_address', 'billing_address',
        'notes', 'admin_notes', 'tags',
        'payment_gateway', 'payment_method'
    ];

    protected $casts = [
        'tags' => 'json',
        'shipping_address' => 'json',
        'billing_address' => 'json',
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'shipping_amount' => 'integer',
        'tax_amount' => 'integer',
        'total' => 'integer',
    ];

    protected $appends = [
        'formatted_subtotal',
        'formatted_discount_amount',
        'formatted_shipping_amount',
        'formatted_tax_amount',
        'formatted_total',
    ];

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->attributes['subtotal'] / 100, 2);
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return number_format($this->attributes['discount_amount'] / 100, 2);
    }

    public function getFormattedShippingAmountAttribute()
    {
        return number_format($this->attributes['shipping_amount'] / 100, 2);
    }

    public function getFormattedTaxAmountAttribute()
    {
        return number_format($this->attributes['tax_amount'] / 100, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->attributes['total'] / 100, 2);
    }

    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = (int) round($value * 100);
    }

    public function setDiscountAmountAttribute($value)
    {
        $this->attributes['discount_amount'] = (int) round($value * 100);
    }

    public function setShippingAmountAttribute($value)
    {
        $this->attributes['shipping_amount'] = (int) round($value * 100);
    }

    public function setTaxAmountAttribute($value)
    {
        $this->attributes['tax_amount'] = (int) round($value * 100);
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (int) round($value * 100);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->order_number) {
                // Initial order number generation
                $latest = static::latest()->first();
                $lastId = $latest ? (int) str_replace('#', '', $latest->order_number) : 1000;
                $order->order_number = '#' . ($lastId + 1);
            }
        });
    }
}
