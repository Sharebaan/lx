<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesPriceSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_price_sets', function (Blueprint $table) {
          $table->integer('id_package');
          $table->integer('id_price_set')->unsigned();
          $table->string('soap_client')->default('HO');
          /*$table->increments('idx');
          $table->integer('id');
          $table->dateTime('valid_from')->nullable();
          $table->dateTime('valid_to')->nullable();
          $table->string('soap_client')->default('HO');
          $table->string('label');
          $table->longText('description');
          $table->integer('is_local');*/
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_price_sets');
    }
}
