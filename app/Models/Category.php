<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'image',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Handle image deletion when category is deleted
        static::deleting(function ($category) {
            if ($category->image) {
                ImageHelper::destroy($category->image);
            }
        });
    }

    /**
     * Set the image attribute and handle file upload
     *
     * @param  mixed  $value
     * @return void
     */
    public function setImageAttribute($value)
    {
        // If value is an UploadedFile, handle the upload
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $existingImage = $this->attributes['image'] ?? null;
            $this->attributes['image'] = ImageHelper::store($value, 'categories', $existingImage);
        }
        // If value is null and we're clearing the image
        elseif (is_null($value) && isset($this->attributes['image'])) {
            ImageHelper::destroy($this->attributes['image']);
            $this->attributes['image'] = null;
        }
        // Otherwise, just set the value (for existing paths)
        else {
            $this->attributes['image'] = $value;
        }
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get all categories for dropdown (excluding current category and its children)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCategoriesForDropdown(?int $excludeId = null)
    {
        $query = self::query();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->pluck('title', 'id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category');
    }

    /**
     * Get all descendants recursively (if needed)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if category has children
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Get the full path breadcrumb
     */
    public function getPathAttribute()
    {
        $path = [$this->title];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->title);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Scope to get only root categories
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
