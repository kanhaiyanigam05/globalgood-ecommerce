<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VariantController;
use Illuminate\Support\Facades\Route;

Route::prefix('management')->as('admin.')->group(function () {
    Route::middleware('guest.guard:admin')->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'authenticate'])->name('authenticate');
    });
    Route::middleware('auth.guard:admin')->group(function () {
        Route::redirect('/', 'management/dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        // Categories
        Route::get('/categories/hierarchical-data', [CategoryController::class, 'fetchHierarchicalData'])->name('categories.hierarchical_data');
        Route::put('categories/{category}/status', [CategoryController::class, 'status'])->name('categories.status');
        Route::resource('categories', CategoryController::class);

        // Products
        Route::put('products/{product}/status', [ProductController::class, 'status'])->name('products.status');
        Route::put('products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
        Route::resource('products', ProductController::class);

        // All Variants
        Route::get('variants', [VariantController::class, 'all'])->name('variants.all');

        // Product Variants (nested under products)
        Route::prefix('products/{product}')->as('products.')->group(function () {
            Route::get('variants', [VariantController::class, 'index'])->name('variants.index');
            Route::post('variants/generate', [ProductController::class, 'generateVariants'])->name('variants.generate');
            Route::post('variants/bulk-save', [VariantController::class, 'bulkSave'])->name('variants.bulkSave');
            Route::get('variants/create', [VariantController::class, 'create'])->name('variants.create');
            Route::post('variants', [VariantController::class, 'store'])->name('variants.store');
            Route::get('variants/{variant}/edit', [VariantController::class, 'edit'])->name('variants.edit');
            Route::put('variants/{variant}', [VariantController::class, 'update'])->name('variants.update');
            Route::delete('variants/{variant}', [VariantController::class, 'destroy'])->name('variants.destroy');
        });

        // Attributes
        Route::get('attributes/by-category', [AttributeController::class, 'getAttributesByCategory'])->name('attributes.by-category');
        Route::resource('attributes', AttributeController::class);

        // Attribute Values
        Route::resource('attribute-values', AttributeValueController::class);

        // Collections
        Route::get('collections/search-products', [CollectionController::class, 'searchProducts'])->name('collections.search-products');
        Route::resource('collections', CollectionController::class);

        // Vendors
        Route::resource('vendors', App\Http\Controllers\Admin\VendorController::class)->only(['index', 'show']);
        Route::post('vendors/{id}/status', [App\Http\Controllers\Admin\VendorController::class, 'updateStatus'])->name('vendors.updateStatus');
        Route::post('vendor-documents/{id}/verify', [App\Http\Controllers\Admin\VendorController::class, 'verifyDocument'])->name('vendors.verifyDocument');
        Route::post('vendor-bank/{id}/verify', [App\Http\Controllers\Admin\VendorController::class, 'verifyBank'])->name('vendors.verifyBank');
        // Customers
        Route::resource('customers', CustomerController::class);

        // Orders
        Route::get('orders/search-products', [App\Http\Controllers\Admin\OrderController::class, 'searchProducts'])->name('orders.search-products');
        Route::get('orders/search-customers', [App\Http\Controllers\Admin\OrderController::class, 'searchCustomers'])->name('orders.search-customers');
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    });
});
