<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesDepartureDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages_departure_dates', function (Blueprint $table) {
          $table->increments('id')->unsigned();
          $table->integer('id_package');
          $table->date('departure_date');
          $table->string('soap_client')->default('HO');

          //$table->index('departure_date');
      });
      DB::statement('CREATE INDEX  `departure_date`
      ON packages_departure_dates ( `departure_date`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages_departure_dates');
    }
}
