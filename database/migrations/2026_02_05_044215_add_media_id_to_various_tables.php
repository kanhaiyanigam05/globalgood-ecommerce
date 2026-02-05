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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('image');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('set null');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('image');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('set null');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('file');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn('media_id');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn('media_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn('media_id');
        });
    }
};
