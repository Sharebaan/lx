<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelAmenities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hotel_amenities', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_hotel');
          $table->string('text')->nullable();
          $table->string('soap_client');
          $table->tinyInteger('update');

          //$table->index('id_hotel');
      });
      DB::statement('CREATE INDEX  `id_hotel`
      ON hotel_amenities ( `id_hotel`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotel_amenities');
    }
}
