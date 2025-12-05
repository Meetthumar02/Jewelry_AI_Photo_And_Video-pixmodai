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
        Schema::table('creative_ai_generations', function (Blueprint $table) {
            if (!Schema::hasColumn('creative_ai_generations', 'service_response')) {
                $table->json('service_response')->nullable()->after('ai_response');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creative_ai_generations', function (Blueprint $table) {
            if (Schema::hasColumn('creative_ai_generations', 'service_response')) {
                $table->dropColumn('service_response');
            }
        });
    }
};

