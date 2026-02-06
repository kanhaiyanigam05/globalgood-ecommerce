<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

require_once __DIR__.'/admin.php';
require_once __DIR__.'/vendor.php';
require_once __DIR__.'/customer.php';

Route::get('file/{path}', [ImageController::class, 'render'])->name('file.path')->where('path', '.*');
Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('shop', 'shop')->name('shop');
    Route::get('collections', 'collections')->name('collections');
    Route::get('collections/{slug}', 'collectionItem')->name('collection.item');
    Route::get('categories', 'categories')->name('categories');
    Route::get('categories/{slug}', 'categoryItem')->name('category.item');
    Route::get('products/{slug}','productItem')->name('product.item');
});