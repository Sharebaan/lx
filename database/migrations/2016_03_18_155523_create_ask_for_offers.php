<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAskForOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ask_for_offers', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_package')->nullable();
          $table->integer('id_hotel')->nullable();
          $table->string('soap_client');
          $table->string('room_category');
          $table->string('meal_plan');
          $table->longText('rooms');
          $table->string('price');
          $table->date('departure_date');
          $table->date('return_date');
          $table->integer('duration');
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
        Schema::drop('ask_for_offers');
    }
}
