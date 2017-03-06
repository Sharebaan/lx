<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoteluri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('hoteluri', function (Blueprint $table) {
          $table->increments('hotel_id')->unsigned();
          $table->integer('oras_id')->unsigned();
          $table->string('hotel_nume');
          $table->string('pret_de_la');
          $table->integer('categorie_id');
          $table->mediumInteger('stele_id');
          $table->text('hotel_descriere');
          $table->longText('hotel_preturi');
          $table->string('tipuri_masa');
          $table->string('website');
          $table->string('cartier');
          $table->date('data_creare');
          $table->date('data_modificare');
          $table->date('data_galerie_update');
          $table->enum('status', ['activ', 'inactiv']);

          //$table->index('status');
      });
      DB::statement('CREATE INDEX  `status`
      ON hoteluri ( `status`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hoteluri');
    }
}
