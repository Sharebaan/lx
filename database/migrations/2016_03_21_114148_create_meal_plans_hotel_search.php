<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealPlansHotelSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('meal_plans_hotel_search', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_hotel_search');
          $table->string('name');
          $table->string('code')->nullable();
          $table->tinyInteger('update');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meal_plans_hotel_search');
    }
}
