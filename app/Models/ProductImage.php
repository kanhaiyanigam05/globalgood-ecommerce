<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'file',
        'media_id',
        'alt',
        'title',
        'position',
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
        'position' => 'integer',
    ];

    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute()
    {
        return $this->thumb(480);
    }

    /**
     * Get a manual resize URL with both width and height
     */
    public function thumb($w = null, $h = null)
    {
        if ($this->media_id && $this->media) {
            return $this->media->getSize($w, $h);
        }

        return route('file.path', [
            'path' => $this->file,
            'w' => $w,
            'h' => $h
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
