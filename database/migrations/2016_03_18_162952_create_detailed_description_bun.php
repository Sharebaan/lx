<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailedDescriptionBun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('detailed_descriptions_bun', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_hotel');
          $table->longText('text');
          $table->integer('index');
          $table->integer('id_package');
          $table->string('soap_client');
          $table->string('label')->nullable();
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
        Schema::drop('detailed_descriptions_bun');
    }
}
