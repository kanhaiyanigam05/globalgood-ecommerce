<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'linkable_type',
        'linkable_id',
        'url',
        'order',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function linkable()
    {
        return $this->morphTo();
    }

    public function getCalculatedUrlAttribute()
    {
        if ($this->linkable_type && $this->linkable) {
            return $this->linkable->getLinkableUrl();
        }

        return $this->url ?? '#';
    }

    public function getCalculatedLabelAttribute()
    {
        if ($this->label) {
            return $this->label;
        }

        if ($this->linkable_type && $this->linkable) {
            return $this->linkable->getLinkableLabel();
        }

        return 'Untitled';
    }

    public function isActive(): bool
    {
        $url = $this->calculated_url;
        
        if ($url === '#' || empty($url)) {
            return false;
        }

        $currentUrl = request()->url();
        
        if ($url === $currentUrl) {
            return true;
        }

        // Handle relative URLs
        if (strpos($url, '/') === 0 && !strpos($url, 'http')) {
            $path = ltrim($url, '/');
            if (empty($path)) {
                return request()->is('/');
            }
            return request()->is($path) || request()->is($path . '/*');
        }

        return false;
    }

    public function getClassesAttribute(): string
    {
        return $this->isActive() ? 'active' : '';
    }
}
