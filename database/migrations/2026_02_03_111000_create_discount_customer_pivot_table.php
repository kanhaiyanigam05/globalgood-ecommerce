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
        // 1. Create pivot table for Customers
        Schema::create('discount_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Remove JSON column from discounts
        Schema::table('discounts', function (Blueprint $table) {
            if (Schema::hasColumn('discounts', 'customer_ids')) {
                $table->dropColumn('customer_ids');
            }
        });

        // 3. Ensure discount_targets has proper indexing
        // The index already exists from the original migration, so we skip it
        // Schema::table('discount_targets', function (Blueprint $table) {
        //     $table->index(['target_type', 'target_id']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_customer');
        Schema::table('discounts', function (Blueprint $table) {
            $table->json('customer_ids')->nullable()->after('customer_selection');
        });
    }
};
