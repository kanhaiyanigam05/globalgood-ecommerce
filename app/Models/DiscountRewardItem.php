<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRewardItem extends Model
{
    use HasFactory;

    protected $fillable = ['discount_id', 'product_id', 'collection_id', 'variant_ids'];

    protected $casts = [
        'variant_ids' => 'array',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
