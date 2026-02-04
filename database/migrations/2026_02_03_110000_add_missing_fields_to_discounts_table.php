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
        Schema::table('discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('discounts', 'customer_ids')) {
                $table->json('customer_ids')->nullable()->after('customer_selection');
            }
            if (!Schema::hasColumn('discounts', 'apply_on_pos')) {
                $table->boolean('apply_on_pos')->default(false)->after('customer_ids');
            }
            if (!Schema::hasColumn('discounts', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('combinations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['customer_ids', 'apply_on_pos', 'is_featured']);
        });
    }
};
