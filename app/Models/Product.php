<?php

namespace App\Models;

use App\Traits\HandlesSmartCollections;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HandlesSmartCollections;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'quantity',
        'price',
        'compare_at_price',
        'features',
        'additional_details',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_featured',
        'status',
        'is_approved',
        'vendor_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'integer',
        'compare_at_price' => 'integer',
        'features' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'is_approved' => 'boolean',
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
        $this->setAttribute('price', (int) round($value * 100));
    }

    public function setCompareAtPriceAttribute($value)
    {
        $this->setAttribute('compare_at_price', (int) round($value * 100));
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function firstImage()
    {
        return $this->images()->first();
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id', 'attribute_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($product) {
            $product->syncProductToSmartCollections($product);
        });
    }
}
