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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Totals (Stored as cents/paise)
            $table->integer('items_count')->default(0);
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('discount_amount')->default(0);
            $table->bigInteger('shipping_amount')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('total')->default(0);
            
            $table->string('currency')->default('INR');
            
            // Statuses
            $table->string('status')->default('draft'); // draft, pending, paid, partially_paid, refunded, voided
            $table->string('fulfillment_status')->default('unfulfilled'); // unfulfilled, partially_fulfilled, fulfilled, restocked
            
            // Addresses (Snapshots or References)
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            
            // Notes & Tags
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('tags')->nullable();
            
            // Payment info
            $table->string('payment_gateway')->nullable();
            $table->string('payment_method')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
