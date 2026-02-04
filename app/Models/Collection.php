<?php

namespace App\Models;

use App\Traits\HandlesSmartCollections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HandlesSmartCollections;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'type',
        'condition_type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }

    public function conditions()
    {
        return $this->hasMany(CollectionCondition::class);
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
