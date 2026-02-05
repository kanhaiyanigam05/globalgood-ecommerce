<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
use App\Traits\HandlesSmartCollections;

class Collection extends Model
{
    use HandlesSmartCollections;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'media_id',
        'type',
        'condition_type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product')
            ->withPivot('variant_ids')
            ->withTimestamps();
    }

    public function conditions()
    {
        return $this->hasMany(CollectionCondition::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Get the image URL (handles both legacy path and media library)
     */
    public function getImageUrlAttribute()
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

        if ($this->image) {
            return route('file.path', [
                'path' => $this->image,
                'w' => $w,
                'h' => $h
            ]);
        }

        $size = ($w && $h) ? "{$w}x{$h}" : "100x100";
        return "https://placehold.co/{$size}?text=".urlencode($this->title);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->title);
            }
        });
    }
}
