<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'handle',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function rootItems()
    {
        return $this->items()->whereNull('parent_id');
    }

    public function scopeByHandle($query, $handle)
    {
        return $query->where('handle', $handle);
    }

    public function render($view = 'components.menu')
    {
        return view($view, ['menu' => $this, 'items' => $this->rootItems()->with('children.children')->get()]);
    }
}
