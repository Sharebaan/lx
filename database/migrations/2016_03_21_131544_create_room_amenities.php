<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomAmenities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('room_amenities', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_hotel');
          $table->string('text');
          $table->string('soap_client')->default('HO');
          $table->tinyInteger('update');

          //$table->index('id_hotel');
      });
      DB::statement('CREATE INDEX  `id_hotel`
      ON room_amenities ( `id_hotel`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('room_amenities');
    }
}
