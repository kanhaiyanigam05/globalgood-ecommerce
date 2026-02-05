<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{

    protected $fillable = [
        'uuid',
        'collection_name',
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
        'vendor_id',
    ];

    protected $appends = ['url', 'thumb', 'human_readable_size', 'is_image'];

    protected $casts = [
        'custom_properties' => 'array',
        'manipulations' => 'array',
        'generated_conversions' => 'array',
        'responsive_images' => 'array',
    ];

    // Cached image widths
    public const CACHED_SIZES = [128, 480, 720, 1080, 1920];

    /**
     * Relationships
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Accessors
     */
    public function getUrlAttribute()
    {
        return route('file.path', ['path' => $this->getPath()]);
    }

    public function getThumbAttribute()
    {
        if ($this->is_image) {
            return $this->getCachedSize(480);
        }
        return $this->url;
    }

    public function getHumanReadableSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Get cached size URL for a specific width
     */
    public function getCachedSize($width)
    {
        return $this->getSize($width);
    }

    /**
     * Get image URL with required width and height
     */
    public function getSize($width = null, $height = null)
    {
        if (!$this->is_image) {
            return $this->url;
        }

        return route('file.path', [
            'path' => $this->getPath(),
            'w' => $width,
            'h' => $height
        ]);
    }

    /**
     * Generate and store all cached sizes
     */
    public function generateCachedSizes()
    {
        if (!$this->is_image) {
            return;
        }

        $cachedSizes = [];

        foreach (self::CACHED_SIZES as $width) {
            $cachedSizes[$width] = route('file.path', [
                'path' => $this->getPath(),
                'w' => $width
            ]);
        }

        $this->setCustomProperty('cached_sizes', $cachedSizes);
        $this->save();
    }

    /**
     * Get the relative path of the media file
     */
    public function getPath()
    {
        // Use directory (collection_name) if specified, otherwise default to 'media'
        $directory = $this->collection_name ?: 'media';
        return $directory . '/' . $this->file_name;
    }

    /**
     * Get custom property
     */
    public function getCustomProperty($key, $default = null)
    {
        $properties = $this->custom_properties ?? [];
        return $properties[$key] ?? $default;
    }

    /**
     * Set custom property
     */
    public function setCustomProperty($key, $value)
    {
        $properties = $this->custom_properties ?? [];
        $properties[$key] = $value;
        $this->custom_properties = $properties;
        return $this;
    }

    /**
     * Scopes
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeDocuments($query)
    {
        return $query->whereNotIn('mime_type', function ($q) {
            $q->select('mime_type')
                ->from('media')
                ->where('mime_type', 'like', 'image/%')
                ->orWhere('mime_type', 'like', 'video/%');
        });
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'like', 'video/%');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('file_name', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%");
        });
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            // Delete physical file when model is deleted
            $disk = Storage::disk($media->disk);
            $path = $media->getPath();
            
            if ($disk->exists($path)) {
                $disk->delete($path);
            }

            // Delete directory if empty
            $directory = dirname($path);
            if ($disk->exists($directory) && empty($disk->allFiles($directory))) {
                $disk->deleteDirectory($directory);
            }
        });
    }
}

