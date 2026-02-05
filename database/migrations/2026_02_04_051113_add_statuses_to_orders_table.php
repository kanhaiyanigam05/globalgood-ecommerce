<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status')->default('open')->after('order_number');
            $table->string('payment_status')->default('pending')->after('status');
        });

        // Data migration: copy existing status to payment_status
        DB::table('orders')->update(['payment_status' => DB::raw('status')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_status', 'payment_status']);
        });
    }
};
