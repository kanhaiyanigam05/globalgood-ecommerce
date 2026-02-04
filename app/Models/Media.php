<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    protected $fillable = [
        'vendor_id',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'conversions_disk',
        'size',
        'manipulations',
        'custom_properties',
        'generated_conversions',
        'responsive_images',
        'order_column',
    ];

    protected $appends = ['url', 'thumb', 'human_readable_size'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getThumbAttribute()
    {
        if ($this->hasGeneratedConversion('thumb')) {
            return $this->getUrl('thumb');
        }
        return $this->getUrl();
    }
}
