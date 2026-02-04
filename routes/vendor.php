<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->as('vendor.')->group(function () {
    Route::middleware('guest.guard:vendor')->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'authenticate'])->name('authenticate');
        Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [AuthController::class, 'store'])->name('store');
    });
    Route::middleware('auth.guard:vendor')->group(function () {
        Route::redirect('/', 'vendor/dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('documents/upload', [DashboardController::class, 'uploadDocuments'])->name('documents.upload');
        Route::post('bank-details/update', [DashboardController::class, 'updateBankDetails'])->name('bank_details.update');

        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{id}/status', [ProductController::class, 'status'])->name('products.status');

        // Shared Data (Categories & Attributes)
        Route::get('/categories/hierarchical-data', [CategoryController::class, 'fetchHierarchicalData'])->name('categories.hierarchical_data');
        Route::get('attributes/by-category', [AttributeController::class, 'getAttributesByCategory'])->name('attributes.by-category');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
