<?php

namespace App\Contracts;

interface LinkableInterface
{
    /**
     * Get the URL for the linkable resource.
     *
     * @return string
     */
    public function getLinkableUrl(): string;

    /**
     * Get the display label for the linkable resource.
     *
     * @return string
     */
    public function getLinkableLabel(): string;

    /**
     * Get the type name of the linkable resource (e.g., 'Product', 'Collection').
     *
     * @return string
     */
    public static function getLinkableType(): string;
}
