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
        Schema::create('credits_topups', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('user_id');
        $table->integer('credits');
        $table->decimal('amount', 10, 2);
        $table->string('order_id')->nullable();
        $table->string('cf_order_id')->nullable();
        $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
        $table->longText('cf_payment_response')->nullable();
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
        Schema::dropIfExists('credits_topups');
    }
};
