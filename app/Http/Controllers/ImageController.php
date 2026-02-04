<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function render(Request $request, $path)
    {
        $path = urldecode($path);

        $width  = $request->query('w') ? (int) $request->query('w') : null;
        $height = $request->query('h') ? (int) $request->query('h') : null;

        $disk = Storage::disk(config('filesystems.default'));

        if (! $disk->exists($path)) {
            abort(404);
        }

        $manager = ImageManager::gd();
        $image = $manager->read(
            $disk->path($path)
        );

    if ($width && $height) {
        // Force ratio, crop if needed (Shopify behavior)
        $image->cover($width, $height);
    } elseif ($width || $height) {
        // Maintain aspect ratio automatically
        $image->scale($width, $height);
    }

        return response()->stream(function () use ($image) {
            echo $image->toWebp(85);
        }, 200, [
            'Content-Type'  => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
