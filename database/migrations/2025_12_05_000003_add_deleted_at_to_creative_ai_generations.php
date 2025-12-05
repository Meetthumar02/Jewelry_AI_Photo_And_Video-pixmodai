<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('creative_ai')->table('creative_ai_generations', function (Blueprint $table) {
            if (!Schema::connection('creative_ai')->hasColumn('creative_ai_generations', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('creative_ai')->table('creative_ai_generations', function (Blueprint $table) {
            if (Schema::connection('creative_ai')->hasColumn('creative_ai_generations', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

