<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::create('creative_ai_generations', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');

        $table->longText('prompt');

        $table->string('uploaded_image')->nullable();

        $table->string('aspect_ratio');

        $table->string('output_format');

        $table->string('status')->default('processing');

        $table->integer('credits_used')->default(0);

        $table->json('generated_images')->nullable();

        $table->json('ai_response')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creative_ai_generations');
    }
};
