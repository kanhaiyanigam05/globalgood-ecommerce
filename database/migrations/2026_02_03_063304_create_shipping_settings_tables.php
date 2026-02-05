<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_profile_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('shipping_zone_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('flat'); // flat, weight_based, price_based
            $table->decimal('min_value', 15, 2)->nullable();
            $table->decimal('max_value', 15, 2)->nullable();
            $table->decimal('price', 15, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('shipping_profile_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_profile_products');
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zone_countries');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_profiles');
    }
};
