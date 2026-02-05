<?php

namespace App\Models;

use App\Enums\Scope;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'scope',
    ];

    protected $casts = [
        'id' => 'integer',
        'scope' => Scope::class,
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('value');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'attribute_category');
    }
}
