<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Store a new image
     */
    public static function store(UploadedFile $file, string $directory, ?string $existingFile = null): ?string
    {
        // Delete existing file if provided
        if ($existingFile) {
            self::destroy($existingFile);
        }

        // Generate unique filename
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        // Store file in the specified directory
        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Update an existing image
     */
    public static function update(UploadedFile $file, string $directory, ?string $existingFile = null): ?string
    {
        return self::store($file, $directory, $existingFile);
    }

    /**
     * Delete an image
     */
    public static function destroy(?string $filePath): bool
    {
        if (! $filePath) {
            return false;
        }

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }
}
