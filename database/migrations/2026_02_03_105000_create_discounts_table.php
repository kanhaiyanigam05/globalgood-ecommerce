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
        Schema::create('discounts', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('code')->nullable()->unique();
            $blueprint->string('title')->nullable(); // For automatic discounts description
            $blueprint->enum('type', ['amount_off_products', 'amount_off_order', 'buy_x_get_y', 'free_shipping']);
            $blueprint->enum('method', ['code', 'automatic']);
            $blueprint->enum('value_type', ['percentage', 'fixed_amount'])->default('percentage');
            $blueprint->decimal('value', 15, 2)->default(0);
            
            // Requirements
            $blueprint->enum('min_requirement_type', ['none', 'amount', 'quantity'])->default('none');
            $blueprint->decimal('min_requirement_value', 15, 2)->nullable();
            
            // Customer eligibility
            $blueprint->enum('customer_selection', ['all', 'segments', 'specific'])->default('all');
            $blueprint->json('customer_ids')->nullable();
            $blueprint->boolean('apply_on_pos')->default(false);
            
            // Limits
            $blueprint->integer('usage_limit_total')->nullable();
            $blueprint->boolean('usage_limit_per_customer')->default(false);
            
            // Combining
            $blueprint->json('combinations')->nullable(); // ['order_discounts', 'product_discounts', 'shipping_discounts']
            
            $blueprint->boolean('is_featured')->default(false); // For sales channels
            $blueprint->boolean('is_active')->default(true);
            $blueprint->dateTime('starts_at');
            $blueprint->dateTime('ends_at')->nullable();
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
