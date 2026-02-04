<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'file',
        'alt',
        'title',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Handle image deletion when product image is deleted
        static::deleting(function ($productImage) {
            if ($productImage->file) {
                // Use ImageHelper from App\Helpers
                \App\Helpers\ImageHelper::destroy($productImage->file);
            }
        });
    }

    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
    ];

    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute()
    {
        return route('file.path', ['path' => $this->file]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
