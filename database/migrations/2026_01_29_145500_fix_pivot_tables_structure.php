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
        // Drop existing tables
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('variant_attribute');
        
        // Recreate product_attribute with correct structure
        Schema::create('product_attribute', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->string('value'); // Store the actual value
            $table->timestamps();

            $table->primary(['product_id', 'attribute_id']);
        });
        
        // Recreate variant_attribute with correct structure
        Schema::create('variant_attribute', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id');
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->string('value'); // Store the actual value
            $table->timestamps();

            $table->primary(['variant_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('variant_attribute');
    }
};
