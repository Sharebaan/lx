<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtripOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('etrip_operators', function (Blueprint $table) {
          $table->string('url');
          $table->string('id_operator');
          $table->string('name_operator');
          $table->string('wsdl');
          $table->longText('cached_prices_url');
          $table->longText('file_url');
          $table->string('username');
          $table->string('password');
		  $table->primary('id_operator');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('etrip_operators');
    }
}
