<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creative_ai_generations', function (Blueprint $table) {
            if (!Schema::hasColumn('creative_ai_generations', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('creative_ai_generations', function (Blueprint $table) {
            if (Schema::hasColumn('creative_ai_generations', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

