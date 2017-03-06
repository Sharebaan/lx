<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesDeparturePoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_departure_points', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_package');
          $table->integer('id_geography');
          $table->string('soap_client')->default('HO');

          //$table->index(['id_package','id_geography']);
      });
      DB::statement('CREATE INDEX  `id_package`
      ON packages_departure_points ( `id_package`);');
      DB::statement('CREATE INDEX  `id_geography`
      ON packages_departure_points ( `id_geography`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_departure_points');
    }
}
