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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('technical_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->tinyInteger('rank_level');
            $table->string('comment')->nullable();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('technical_id')->references('id')->on('users');
            $table->foreign('service_id')->references('id')->on('services');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};
