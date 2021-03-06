<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingHotelSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('booking_hotel_searches', function (Blueprint $table) {
          $table->increments('id');
          $table->string('id_hotel');
          $table->string('room_category');
          $table->string('meal_plan');
          $table->float('price',8,2);
          $table->integer('duration');
          $table->date('check_in');
          $table->date('check_out');
          $table->longText('rooms');
          $table->integer('result_index');
          $table->integer('meal_plan_index');
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
        Schema::drop('booking_hotel_searches');
    }
}
