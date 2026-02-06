<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait IsLinkable
{
    public function getLinkableUrl(): string
    {
        $typeName = Str::lower(class_basename($this));
        // Default implementation assuming the model has a slug and a front-end route
        // This should be overridden in the model if the route structure is different
        return route($typeName . '.item', $this->slug);
    }

    public function getLinkableLabel(): string
    {
        return $this->title;
    }

    public static function getLinkableType(): string
    {
        return class_basename(static::class);
    }
}
