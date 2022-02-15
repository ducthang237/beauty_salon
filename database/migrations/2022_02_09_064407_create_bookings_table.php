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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('booking_date');
            $table->enum('status', [1, 2, 3])->default(1); // 1: waiting, 2: done, 3: canceled
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('technical_id');
            $table->foreign('salon_id')->references('id')->on('salons');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('technical_id')->references('id')->on('users');
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
        Schema::dropIfExists('bookings');
    }
};
