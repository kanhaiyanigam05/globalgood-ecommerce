<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Buy X Get Y fields
            $table->string('buy_type')->nullable()->after('type'); // 'quantity' or 'amount'
            $table->decimal('buy_value', 10, 2)->nullable()->after('buy_type');
            $table->json('buy_items')->nullable()->after('buy_value'); // product/collection IDs
            $table->integer('get_quantity')->nullable()->after('buy_items');
            $table->string('get_type')->nullable()->after('get_quantity'); // 'percentage', 'amount_off', 'free'
            $table->decimal('get_value', 10, 2)->nullable()->after('get_type');
            $table->json('get_items')->nullable()->after('get_value'); // product/collection IDs
            $table->integer('max_uses_per_order')->nullable()->after('get_items');
            
            // Free Shipping fields
            $table->string('countries')->default('all')->after('max_uses_per_order'); // 'all' or 'selected'
            $table->json('selected_countries')->nullable()->after('countries');
            $table->decimal('exclude_shipping_over', 10, 2)->nullable()->after('selected_countries');
        });
    }

    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'buy_type', 'buy_value', 'buy_items',
                'get_quantity', 'get_type', 'get_value', 'get_items',
                'max_uses_per_order', 'countries', 'selected_countries', 'exclude_shipping_over'
            ]);
        });
    }
};
