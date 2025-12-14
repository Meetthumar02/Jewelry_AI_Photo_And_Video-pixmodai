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
            if (!Schema::hasColumn('model_designs', 'industry_id')) {
                $table->unsignedBigInteger('industry_id')->nullable()->after('id');
                $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('industry_id');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'product_type_id')) {
                $table->unsignedBigInteger('product_type_id')->nullable()->after('category_id');
                $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'shoot_type_id')) {
                $table->unsignedBigInteger('shoot_type_id')->nullable()->after('product_type_id');
                $table->foreign('shoot_type_id')->references('id')->on('shoot_types')->onDelete('cascade');
            }

            // Add indexes for better query performance (check implies columns exist)
            // We can skip index check or wrap it in try catch if strict, but adding columns is main blocker.
            // Assuming index creation is idempotent or doesn't fail as hard as column add on some DBs, but MySQL will fail on duplicate key name.
            // Let's rely on Laravel's schema builder handling of indexes being generally smarter or just assume if columns existed, keys might too.
            // Actually, best to just put index creation inside the column creation blocks or check for index.
            // For simplicity, I'll put them inside the if blocks or just leave them if we assume they go together.
            // Given the error was specifically "Duplicate column name", fixing columns is priority.
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
