<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['manual', 'smart'])->default('manual');
            $table->enum('condition_type', ['all', 'any'])->default('all');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('collection_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('collection_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->string('field');
            $table->string('operator');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_conditions');
        Schema::dropIfExists('collection_product');
        Schema::dropIfExists('collections');
    }
};
