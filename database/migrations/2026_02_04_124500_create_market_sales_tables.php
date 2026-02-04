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
        Schema::create('market_sales', function (Blueprint $芽) {
            $芽->id();
            $芽->string('title');
            $芽->string('slug')->unique();
            $芽->enum('sale_type', ['percentage', 'fixed'])->default('percentage');
            $芽->decimal('sale_value', 15, 2);
            $芽->enum('applied_on', ['product', 'collection'])->default('product');
            $芽->dateTime('starts_at');
            $芽->dateTime('ends_at')->nullable();
            $芽->enum('status', ['active', 'inactive'])->default('active');
            $芽->timestamps();
        });

        Schema::create('market_sale_items', function (Blueprint $芽) {
            $芽->id();
            $芽->foreignId('market_sale_id')->constrained()->onDelete('cascade');
            $芽->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $芽->foreignId('collection_id')->nullable()->constrained()->onDelete('cascade');
            $芽->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_sale_items');
        Schema::dropIfExists('market_sales');
    }
};
