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
        // 1. Drop unused table
        Schema::dropIfExists('discount_targets');

        // 2. Drop unused columns from discounts table
        Schema::table('discounts', function (Blueprint $table) {
            if (Schema::hasColumn('discounts', 'buy_items')) {
                $table->dropColumn('buy_items');
            }
            if (Schema::hasColumn('discounts', 'get_items')) {
                $table->dropColumn('get_items');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->json('buy_items')->nullable()->after('buy_value');
            $table->json('get_items')->nullable()->after('get_value');
        });

        Schema::create('discount_targets', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('discount_id')->constrained()->onDelete('cascade');
            $blueprint->string('target_type');
            $blueprint->unsignedBigInteger('target_id');
            $blueprint->timestamps();
            $blueprint->index(['target_type', 'target_id']);
        });
    }
};
