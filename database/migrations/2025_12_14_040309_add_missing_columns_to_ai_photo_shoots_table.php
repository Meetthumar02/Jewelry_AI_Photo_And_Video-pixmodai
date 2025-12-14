<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ai_photo_shoots', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_photo_shoots', 'industry')) {
                $table->string('industry')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'product_type')) {
                $table->string('product_type')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'shoot_type')) {
                $table->string('shoot_type')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'model_design_id')) {
                $table->string('model_design_id')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'uploaded_image')) {
                $table->string('uploaded_image')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'aspect_ratio')) {
                $table->string('aspect_ratio')->default('4:3');
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'output_format')) {
                $table->string('output_format')->default('JPEG');
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'generated_images')) {
                $table->text('generated_images')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'prompts_used')) {
                $table->text('prompts_used')->nullable();
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'credits_used')) {
                $table->integer('credits_used')->default(0);
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'status')) {
                $table->enum('status', ['draft', 'processing', 'completed', 'failed'])->default('draft');
            }
            if (!Schema::hasColumn('ai_photo_shoots', 'error_message')) {
                $table->text('error_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ai_photo_shoots', function (Blueprint $table) {
            //
        });
    }
};
