<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'language',
        'email_marketing_consent',
        'sms_marketing_consent',
        'notes',
        'tags',
        'tax_setting',
        'total_spent',
        'total_orders',
        'store_credit',
        'last_order_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_marketing_consent' => 'boolean',
        'sms_marketing_consent' => 'boolean',
        'last_order_at' => 'datetime',
        'total_spent' => 'decimal:2',
        'store_credit' => 'decimal:2',
    ];

    protected $appends = [
        'full_name',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_customer');
    }
}
