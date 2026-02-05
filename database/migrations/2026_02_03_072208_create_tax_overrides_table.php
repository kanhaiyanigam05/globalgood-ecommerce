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
        Schema::create('tax_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_zone_id')->nullable()->constrained('country_zones')->onDelete('cascade');
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('tax_name')->nullable();
            $table->enum('tax_type', ['added', 'instead', 'compounded'])->default('added');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_overrides');
    }
};
