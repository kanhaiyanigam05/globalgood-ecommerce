<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketSaleItem extends Model
{
    protected $fillable = [
        'market_sale_id',
        'product_id',
        'collection_id',
    ];

    public function marketSale()
    {
        return $this->belongsTo(MarketSale::class);
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
