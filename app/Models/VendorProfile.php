<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'store_name',
        'slug',
        'logo',
        'banner',
        'description',
        'support_email',
        'support_phone',
        'address',
        'country',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
