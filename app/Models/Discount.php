<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'type', 'method', 'value_type', 'value',
        'min_requirement_type', 'min_requirement_value',
        'customer_selection', 'apply_on_pos',
        'usage_limit_total', 'usage_limit_per_customer', 'usage_count',
        'combinations', 'is_featured', 'starts_at', 'ends_at', 'is_active',
        // Buy X Get Y
        'buy_type', 'buy_value', 'get_quantity', 'get_type', 'get_value', 'max_uses_per_order',
        // Free Shipping
        'countries', 'selected_countries', 'exclude_shipping_over'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_requirement_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'apply_on_pos' => 'boolean',
        'usage_limit_per_customer' => 'boolean',
        'is_featured' => 'boolean',
        'combinations' => 'array',
        'selected_countries' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(DiscountItem::class);
    }

    public function rewardItems()
    {
        return $this->hasMany(DiscountRewardItem::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'discount_customer');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('starts_at', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>=', now());
                     });
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) return 'Disabled';
        if ($this->starts_at->isFuture()) return 'Scheduled';
        if ($this->ends_at && $this->ends_at->isPast()) return 'Expired';
        return 'Active';
    }
}
