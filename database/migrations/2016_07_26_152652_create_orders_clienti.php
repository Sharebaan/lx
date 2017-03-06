<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersClienti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plata_rezervare_clienti', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('rezervare_id');
          $table->tinyInteger('gender');
          $table->string('lname');
          $table->string('fname');
          $table->string('birthdate');
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
        //
    }
}
