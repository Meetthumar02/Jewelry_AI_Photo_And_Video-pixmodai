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
        Schema::connection('ai_photoshoot')->table('ai_photo_shoots', function (Blueprint $table) {
            // Drop the foreign key constraint to allow users from the main database.
            if (Schema::connection('ai_photoshoot')->hasColumn('ai_photo_shoots', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ai_photoshoot')->table('ai_photo_shoots', function (Blueprint $table) {
            // Recreate the foreign key if needed (assumes users table exists in ai_photoshoot DB)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

