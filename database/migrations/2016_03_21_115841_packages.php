<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Packages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages', function (Blueprint $table) {
          $table->increments('idx');
          $table->integer('id');
          $table->string('name');
          $table->tinyInteger('is_tour');
          $table->tinyInteger('is_bus');
          $table->tinyInteger('is_flight');
          $table->integer('duration');
          $table->integer('id_hotel');
          $table->integer('outbound_transport_duration');
          $table->longText('description')->nullable();
          $table->integer('destination');
          $table->longText('included_services');
          $table->longText('not_included_services');
          $table->string('soap_client')->default('HO');
          $table->tinyInteger('update');
          $table->dateTime('updateOn');
          $table->dateTime('createdOn');
          $table->string('hotel_soap_client');

          //$table->index(['id','id_hotel','destination']);
      });
      DB::statement('CREATE INDEX  `id`
      ON packages ( `id`);');
      DB::statement('CREATE INDEX  `id_hotel`
      ON packages ( `id_hotel`);');
      DB::statement('CREATE INDEX  `destination`
      ON packages ( `destination`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages');
    }
}
