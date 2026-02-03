<?php

if (! function_exists('setting_data')) {
    function setting_data()
    {
        return (object) [
            'site_name' => 'GlobalGood',
            'site_logo' => 'admins/images/logo/1.png',
            'site_logo_light' => 'admins/images/logo/1.png',
            'site_favicon' => 'admins/images/favicon/1.png',
        ];
    }
}
if (! function_exists('formatCategoriesRecursive')) {
    function formatCategoriesRecursive($categories)
    {
        return $categories->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->title,
            'children' => $category->children->isNotEmpty()
                ? formatCategoriesRecursive($category->children)
                : [],
        ])->toArray();
    }
}
