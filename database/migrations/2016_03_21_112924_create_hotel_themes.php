<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelThemes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hotel_themes', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_hotel');
          $table->string('text')->nullable();
          $table->string('soap_client')->default('HO');
          $table->tinyInteger('update');

          $table->index('id_hotel');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotel_themes');
    }
}
