<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('model_designs', function (Blueprint $table) {
            // Add foreign keys for the hierarchy
            $table->unsignedBigInteger('industry_id')->nullable()->after('id');
            $table->unsignedBigInteger('category_id')->nullable()->after('industry_id');
            $table->unsignedBigInteger('product_type_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('shoot_type_id')->nullable()->after('product_type_id');

            // Add foreign key constraints
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
            $table->foreign('shoot_type_id')->references('id')->on('shoot_types')->onDelete('cascade');

            // Add indexes for better query performance
            $table->index(['industry_id', 'category_id', 'product_type_id', 'shoot_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_designs', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['industry_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['product_type_id']);
            $table->dropForeign(['shoot_type_id']);

            // Drop columns
            $table->dropColumn(['industry_id', 'category_id', 'product_type_id', 'shoot_type_id']);
        });
    }
};
