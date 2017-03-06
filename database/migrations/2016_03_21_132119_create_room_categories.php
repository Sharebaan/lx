<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('room_categories', function (Blueprint $table) {
          $table->increments('idx');
          $table->integer('id');
          $table->string('name');
          $table->string('description')->nullable();
          $table->integer('id_hotel');
          $table->string('soap_client')->default('HO');
          $table->tinyInteger('update');

          //$table->index('id_hotel');
      });
      DB::statement('CREATE INDEX  `id_hotel`
      ON room_categories ( `id_hotel`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('room_categories');
    }
}
