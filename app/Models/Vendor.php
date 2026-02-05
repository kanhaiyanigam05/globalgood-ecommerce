<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'legal_name',
        'display_name',
        'email',
        'phone',
        'password',
        'status',
        'kyc_status',
        'tax_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(VendorBankAccount::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
