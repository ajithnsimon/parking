<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            $table->string('vehicle_number');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('driver_license')->nullable();
            $table->float('fee');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('slot_id');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
