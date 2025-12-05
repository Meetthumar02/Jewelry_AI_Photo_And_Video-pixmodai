<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('ai_photoshoot')->create('ai_photo_shoots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Step 1: Style Selection
            $table->string('industry')->nullable(); // Jewellery, Fashion, etc.
            $table->string('category')->nullable(); // Women Jewellery, Men Jewellery, etc.
            $table->string('product_type')->nullable(); // Necklace, Ring, Earrings, etc.
            $table->string('shoot_type')->nullable(); // Classic, Lifestyle, Luxury, Outdoor

            // Step 2: Model Design Selection
            $table->string('model_design_id')->nullable(); // Selected model template

            // Step 3: Upload & Configure
            $table->string('uploaded_image')->nullable();
            $table->string('aspect_ratio')->default('4:3');
            $table->string('output_format')->default('JPEG'); // JPEG, PNG

            // Generated Results
            $table->text('generated_images')->nullable(); // JSON array of generated image URLs
            $table->text('prompts_used')->nullable(); // JSON of prompts used
            $table->integer('credits_used')->default(0);

            // Status
            $table->enum('status', ['draft', 'processing', 'completed', 'failed'])->default('draft');
            $table->text('error_message')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::connection('ai_photoshoot')->dropIfExists('ai_photo_shoots');
    }
};
